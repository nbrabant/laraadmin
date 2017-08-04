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
  use \Dwij\Laraadmin\Helpers\FileManager;

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


      // Ask to copy language file

      // If not, ask merging this files


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
