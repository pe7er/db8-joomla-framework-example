# Display Joomla Articles with the Joomla Framework (Headless, No CMS)

This example project demonstrates how to use the Joomla Framework 
(without the full Joomla CMS) to display articles 
from a specific category in a standalone PHP application.

It uses Joomla's database package to connect to an existing Joomla database and fetch content 
directly — ideal for headless or microservice-style use cases.

This project focuses on using Joomla Framework directly, 
without relying on Joomla’s Web Services API or the CMS environment.

## Features
- Headless: No Joomla CMS installation required
- Uses Joomla Framework packages via Composer
- Pulls articles from the `#__content` table
- Responsive HTML output using Bootstrap 5
- Easy to configure and extend


## Requirements
- PHP 8.1 or higher
- Composer
- An existing Joomla 4.x or 5.x database
- Web server with `index.php` accessible

## Installation
1. **Clone the repository:**
```bash
git clone git@github.com:pe7er/db8-joomla-framework-example.git
cd db8-joomla-framework-example
```

2. **Install dependencies with Composer:**
```bash
composer install
```

## Configuration
Open index.php and update the database config inside the `$databaseConfig` array.
> ⚠️ **Warning:** Never commit sensitive credentials to version control. Use `.env` or config files in production!

```php
$databaseConfig = [
    'database' => [
        'driver' => 'mysqli',
        'host' => 'localhost',
        'user' => 'your_db_user',
        'password' => 'your_db_password',
        'name' => 'your_joomla_db',
        'port' => '3306',
        'socket' => '',
        'prefix' => 'jos_', // Change if your table prefix is different
    ],
    'catId' => 11 // ID of the category you want to display articles from
];
```

## Usage
After configuring, run the app by opening `index.php` in your browser 
(via a local server like Apache or PHP's built-in server):

```bash
php -S localhost:8000
```
Then visit: http://localhost:8000

You should see a Bootstrap-styled list of articles from your Joomla database.

## Sample Output


## Joomla Framework Reference
- Website: https://framework.joomla.org/
- GitHub Project: https://github.com/joomla-framework
- Packages on Packagist: https://packagist.org/?query=joomla

## License
This project is licensed under the [GNU GPL v3](https://www.gnu.org/licenses/gpl-3.0.html).
See `LICENSE` for full details.

## Contributing
Improvements, fixes, and feedback are welcome!
- To contribute to **this example**, open a GitHub Issue or Pull Request.
- To contribute to the **Joomla Framework**, visit [joomla-framework on GitHub](https://github.com/joomla-framework).

## Author
This example was made by Peter Martin, https://db8.nl/
