# Laravel Source Encrypter

[![StyleCI](https://github.styleci.io/repos/248479627/shield?branch=master)](https://github.styleci.io/repos/248479627)
[![Latest Stable Version](https://poser.pugx.org/sbamtr/laravel-source-encrypter/v/stable)](https://packagist.org/packages/sbamtr/laravel-source-encrypter)
[![License](https://poser.pugx.org/sbamtr/laravel-source-encrypter/license)](https://github.com/SiavashBamshadnia/Laravel-Source-Encrypter)
[![CodeFactor](https://www.codefactor.io/repository/github/siavashbamshadnia/laravel-source-encrypter/badge)](https://www.codefactor.io/repository/github/siavashbamshadnia/laravel-source-encrypter)


![](cover.jpg)

This package encrypts your php code with [phpBolt](https://phpbolt.com) 

*For Laravel and Lumen 6, 7, 8*

* [Installation](#installation)
* [Usage](#usage)

## Installation

### Step 1
At the first, You have to [install phpBolt](https://phpbolt.com/download-phpbolt/).


### Step 2
Require the package with composer using the following command:
```bash
composer require --dev sbamtr/laravel-source-encrypter
```
### Step 3
#### For Laravel
The service provider will automatically get registered. Or you may manually add the service provider in your `config/app.php` file:
```php
'providers' => [
    // ...
    \sbamtr\LaravelSourceEncrypter\SourceEncryptServiceProvider::class,
];
```

#### For Lumen
Add this line of code under the `Register Service Providers` section of your `bootstrap/app.php`:
```php
$app->register(\sbamtr\LaravelSourceEncrypter\SourceEncryptServiceProvider::class);
```


### Step 4 (Optional)
You can publish the config file with this following command:
```bash
php artisan vendor:publish --provider="sbamtr\LaravelSourceEncrypter\SourceEncryptServiceProvider" --tag=config
```
**Note:** If you are using Lumen, you have to use [this package](https://github.com/laravelista/lumen-vendor-publish).

## Usage
Open terminal in project root and run this command: 
```bash
php artisan encrypt-source
```
This command encrypts files and directories in `config/source-encrypter.php` file. Default values are `app`, `database`, `routes`.

The default destination directory is `encrypted`. You can change it in `config/source-encrypter.php` file.

Also the default encryption key length is `6`. You can change it in `config/source-encrypter.php` file. `6` is the recommended key length.

This command has these optional options:

| Option      | Description                                                          | Example                 |
|-------------|----------------------------------------------------------------------|-------------------------|
| source      | Path(s) to encrypt                                                   | app,routes,public/a.php |
| destination | Destination directory                                                | encrypted               |
| keylength   | Encryption key length                                                | 6                       |
| force       | Force the operation to run when destination directory already exists |                         |

### Usage Examples

| Command                                                       | Description                                                                                                       |
|---------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------|
| `php artisan encrypt-source`                                  | Encrypts with default source, destination and keylength. If the destination directory exists, asks for delete it. |
| `php artisan encrypt-source --force`                          | Encrypts with default source, destination and keylength. If the destination directory exists, deletes it.         |
| `php artisan encrypt-source --source=app`                     | Encrypts `app` directory to the default destination with default keylength.                                       |
| `php artisan encrypt-source --destination=dist`               | Encrypts with default source and key length to `dist` directory.                                                  |
| `php artisan encrypt-source --destination=dist --keylength=8` | Encrypts default source to `dist` directory and the encryption key length is `8`.                                 |

Written with ♥ by Siavash Bamshadnia.

Please support me by staring this repository.