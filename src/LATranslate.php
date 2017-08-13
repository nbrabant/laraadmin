<?php

namespace Dwij\Laraadmin;


class LATranslate
{
	use \Dwij\Laraadmin\Helpers\FileManager;


	private static $_instance = null;


	private function __construct() {}


	public static function getInstance() {
	    if(is_null(self::$_instance)) {
	        self::$_instance = new LATranslate();
	    }

	    return self::$_instance;
	}

	public static function createTranslation($key, $value, $locale = null) {

	}

	public function copyTranslations($from, $to) {
		if (!is_dir($from . '/resources/lang') || empty($list = \File::directories($from . '/resources/lang'))) {
			return false;
		}

		foreach ($list as $directory) {
			foreach (\File::files($directory) as $file) {
				$this->copyFile($file, str_replace($from, $to, $file));
			}
		}
	}

	public function mergeTranslations() {

	}



}
