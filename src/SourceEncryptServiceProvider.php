<?php

/**
 * Laravel Source Encrypter.
 *
 * @author      Siavash Bamshadnia
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @link        https://github.com/SiavashBamshadnia/Laravel-Source-Encrypter
 */

namespace sbamtr\LaravelSourceEncrypter;

use Illuminate\Support\ServiceProvider;

class SourceEncryptServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register hard-delete-expired artisan command
        $this->commands([
            SourceEncryptCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $configPath = __DIR__.'/../config/source-encrypter.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('source-encrypter.php');
        } else {
            $publishPath = base_path('config/source-encrypter.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }
}
