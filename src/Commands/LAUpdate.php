<?php

namespace Dwij\Laraadmin\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Dwij\Laraadmin\Helpers\LAHelper;
use Eloquent;
use DB;

/**
 * Class LAInstall
 * @package Dwij\Laraadmin\Commands
 *
 * Command to process updating LaraAdmin package into project which merges files from 'src/Installs' directory to Project
 */
class LAUpdate extends Command
{
	// The command signature.
    protected $signature = 'la:update';

	// The command description.
    protected $description = 'Update LaraAdmin Package files.';

	// Copy From Folder - Package Install Files
	protected $from;

	// Copy to Folder - Project Folder
	protected $to;

	protected $basepath = '';

	protected $composer_path;

	public function __construct()
    {
        parent::__construct();

        $this->basepath = str_replace([base_path() . '\\', 'Commands'], '', __DIR__) . 'Installs';

        $this->setComposerPath();
    }

	public function handle()
	{
		try {
			$this->info('LaraAdmin update started...');

			$from = base_path($this->basepath);
            $to = base_path();

            $this->info('from: ' . $from . " to: " . $to);

			$this->line("\nDB Assistant:");



		} catch (Exception $e) {
			$msg = $e->getMessage();
            if(strpos($msg, 'SQLSTATE') !== false) {
                throw new Exception("LAUpdate: Database is not connected. Connect database (.env) and run 'la:update' again.\n" . $msg, 1);
            } else {
                $this->error("LAUpdate::handle exception: " . $e);
                throw new Exception("LAUpdate::handle Unable to update : " . $msg, 1);
            }
		}
	}

	/**
     * Copy Folder contents
     *
     * @param $from from folder
     * @param $to to folder
     */
    private function copyFolder($from, $to)
    {
        // $this->info("copyFolder: ($from, $to)");
        LAHelper::recurse_copy($from, $to);
    }

    /**
     * Replace Folder contents by deleting content of to folder first
     *
     * @param $from from folder
     * @param $to to folder
     */
    private function replaceFolder($from, $to)
    {
        // $this->info("replaceFolder: ($from, $to)");
        if(file_exists($to)) {
            LAHelper::recurse_delete($to);
        }
        LAHelper::recurse_copy($from, $to);
    }

    /**
     * Copy file contents. If file not exists create it.
     *
     * @param $from from file
     * @param $to to file
     */
    private function copyFile($from, $to)
    {
        // $this->info("copyFile: ($from, $to)");
        if(!file_exists(dirname($to))) {
            $this->info("mkdir: (" . dirname($to) . ")");
            mkdir(dirname($to));
        }
        copy($from, $to);
    }

    /**
     * Get file contents
     *
     * @param $from file name
     * @return string file contents in string
     */
    private function openFile($from)
    {
        $md = file_get_contents($from);
        return $md;
    }

    /**
     * Append content of 'from' file to 'to' file
     *
     * @param $from from file
     * @param $to to file
     */
    private function appendFile($from, $to)
    {
        // $this->info("appendFile: ($from, $to)");

        $md = file_get_contents($from);

        file_put_contents($to, $md, FILE_APPEND);
    }

    /**
     * Copy contents from one file to another
     *
     * @param $from content to be copied from this file
     * @param $to content will be written to this file
     */
    private function writeFile($from, $to)
    {
        $md = file_get_contents($from);
        file_put_contents($to, $md);
    }

    /**
     * does file contains given text
     *
     * @param $filePath file to search text for
     * @param $text text to be searched in file
     * @return bool return true if text found in given file
     */
    private function fileContains($filePath, $text)
    {
        // TODO: Method not working properly

        $fileData = file_get_contents($filePath);
        if(strpos($fileData, $text) === false) {
            return true;
        } else {
            return false;
        }
    }

    private function setComposerPath() {
        if(PHP_OS == "Darwin") {
            $this->composer_path = "/usr/bin/composer.phar";
        } else if(PHP_OS == "Linux") {
            $this->composer_path = "/usr/bin/composer";
        } else if(PHP_OS == "Windows") {
            $this->composer_path = "composer";
        } else {
            $this->composer_path = "composer";
        }
    }

}
