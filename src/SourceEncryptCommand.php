<?php

/**
 * Laravel Source Encrypter
 *
 * @author      Siavash Bamshadnia
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @link        https://github.com/SiavashBamshadnia/Laravel-Source-Encrypter
 */

namespace sbamtr\LaravelSourceEncrypter;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Illuminate\Support\Str;

class SourceEncryptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrypt-source
                { --source= : Path(s) to encrypt }
                { --destination= : Destination directory }
                { --keylength= : Encryption key length }';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypts PHP files';

    /**
     * Execute the console command.
     */
    function handle()
    {
        if (!extension_loaded('bolt')) {
            $this->error('Please install bolt.so https://phpBolt.com');
            $this->error('PHP Version ' . phpversion());
            $this->error('INI file location ' . php_ini_scanned_files());
            $this->error('Extension dir: ' . ini_get('extension_dir'));
            return;
        }

        if (empty($this->option('source'))) {
            $sources = config('source-encrypter.source');
        } else {
            $sources = $this->option('source');
            $sources = explode(',', $sources);
        }
        if (empty($this->option('destination'))) {
            $destination = config('source-encrypter.destination');
        } else {
            $destination = $this->option('destination');
        }
        if (empty($this->option('keylength'))) {
            $keyLength = config('source-encrypter.key_length');
        } else {
            $keyLength = $this->option('keylength');
        }

        File::deleteDirectory(base_path($destination));
        File::makeDirectory(base_path($destination));

        foreach ($sources as $source) {
            @File::makeDirectory($destination . '/' . File::dirname($source), 493, true);

            if (File::isFile($source)) {
                self::encryptFile($source, $destination, $keyLength);
                continue;
            }
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path($source)));
            foreach ($files as $file) {
                $filePath = Str::replaceFirst(base_path(), '', $file->getRealPath());
                self::encryptFile($filePath, $destination, $keyLength);
            }
        }
        $this->info('Encrypting Completed Successfully!');
        $this->info("Destination directory: $destination");
    }

    private static function encryptFile($filePath, $destination, $keyLength)
    {
        $key = Str::random($keyLength);
        if (File::isDirectory(base_path($filePath))) {
            if (!File::exists(base_path($destination . $filePath))) {
                File::makeDirectory(base_path("$destination/$filePath"), 493, true);
            }
            return;
        }

        if (File::extension($filePath) != 'php') {
            File::copy(base_path($filePath), base_path("$destination/$filePath"));
            return;
        }

        $fileContents = File::get(base_path($filePath));

        $prepend = "<?php
bolt_decrypt( __FILE__ , '$key'); return 0;
##!!!##";
        $pattern = '/\<\?php/m';
        preg_match($pattern, $fileContents, $matches);
        if (!empty($matches[0])) {
            $fileContents = preg_replace($pattern, '', $fileContents);
        }
        /*$cipher = bolt_encrypt('?> ' . $fileContents, $key);*/
        $cipher = bolt_encrypt($fileContents, $key);
        File::put(base_path("$destination/$filePath"), $prepend . $cipher);

        unset($cipher);
        unset($fileContents);
    }
}
