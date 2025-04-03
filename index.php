<?php
/**
 * @package     db8 Joomla Framework Example
 * @author      Peter Martin <joomla@db8.nl>
 * @copyright   Copyright (C) 2025 Peter Martin. All rights reserved.
 * @license     GNU General Public License version 3
 * @link        https://db8.nl
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Joomla\Application\AbstractApplication;
use Joomla\Database;
use Joomla\Registry\Registry;

// Load environment variables from .env
if (file_exists(__DIR__ . '/.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__)->load();
}

// Configuration from environment variables set in .env
$db8Config = [
    'database' => [
        'driver' => $_ENV['DB_DRIVER'],
        'host' => $_ENV['DB_HOST'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'database' => $_ENV['DB_NAME'],
        'port' => $_ENV['DB_PORT'],
        'socket' => $_ENV['DB_SOCKET'],
        'prefix' => $_ENV['DB_PREFIX']
    ],
    'content' => [
        'catId' => (int)$_ENV['CATEGORY_ID']
    ]
];

class Db8JoomlaFrameworkExample extends AbstractApplication
{
    protected Database\DatabaseDriver $db;

    /**
     * @param \Joomla\Registry\Registry $config
     */
    public function __construct(Registry $config)
    {
        $this->config = $config;

        parent::__construct($config);
    }

    /**
     * Initialise the database
     * @return void
     */
    protected function initialise(): void
    {
        $dbFactory = new Database\DatabaseFactory;
        $databaseConfig = $this->config->toArray()['database'] ?? [];
        $this->db = $dbFactory->getDriver($databaseConfig['driver'], $databaseConfig);
    }

    /**
     * @return void
     */
    protected function doExecute(): void
    {
        header('Content-Type: text/html; charset=utf-8');

        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'title', 'introtext']))
            ->from($this->db->quoteName('#__content'))
            ->where($this->db->quoteName('catid') . ' = ' . $this->config->get('content.catId', 0, 'int'));
        $this->db->setQuery($query);
        $iterator = $this->db->getIterator();

        $articlesHtml = '';
        $hasArticles = false;

        foreach ($iterator as $row) {
            $hasArticles = true;
            $title = htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');
            $intro = htmlspecialchars(strip_tags($row->introtext), ENT_QUOTES, 'UTF-8');
            $articlesHtml .= <<<HTML
<div class="col-md-6 col-lg-4">
  <div class="card h-100 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">{$title}</h5>
      <p class="card-text">{$intro}</p>
    </div>
  </div>
</div>
HTML;
        }

        if (!$hasArticles) {
            $articlesHtml = '<p class="text-muted">No articles found in this category.</p>';
        }

        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Joomla Framework Article Viewer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <header class="bg-primary text-white py-4">
    <div class="container text-center">
      <h1 class="display-5">Joomla Framework Article Viewer</h1>
      <p class="lead">Powered by Joomla 5 Framework + Bootstrap</p>
    </div>
  </header>
  <main class="container my-5">
    <div class="row g-4">
      {$articlesHtml}
    </div>
  </main>
  <footer class="bg-dark text-white text-center py-3">
    &copy; 2025 by Peter Martin / <a href="https://db8.nl" target="_blank" title="db8 Website Support">db8.nl</a>. All rights reserved. Licensed under the GNU GPL v3.
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    }
}

$config = new Registry($db8Config);
$app = new Db8JoomlaFrameworkExample($config);
$app->execute();
