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

		// @TODO : make this code dynamic :
		// list lang resources
		// If file exists, add only the new localisation lines
		// overelse, copy them on the directory
		$this->copyFile($from . "/resources/lang/fr.json", 				$to . "/resources/lang/fr.json");
		$this->copyFile($from . "/resources/lang/fr/global.php", 		$to . "/resources/lang/fr/global.php");
		$this->copyFile($from . "/resources/lang/fr/auth.php",   		$to . "/resources/lang/fr/auth.php");
		$this->copyFile($from . "/resources/lang/fr/errors.php", 		$to . "/resources/lang/fr/errors.php");
		$this->copyFile($from . "/resources/lang/fr/emails.php", 		$to . "/resources/lang/fr/emails.php");
		$this->copyFile($from . "/resources/lang/fr/pagination.php", 	$to . "/resources/lang/fr/pagination.php");

		$this->copyFile($from . "/resources/lang/en.json", 				$to . "/resources/lang/en.json");
		$this->copyFile($from . "/resources/lang/en/global.php", 		$to . "/resources/lang/en/global.php");
		$this->copyFile($from . "/resources/lang/en/auth.php",   		$to . "/resources/lang/en/auth.php");
		$this->copyFile($from . "/resources/lang/en/errors.php", 		$to . "/resources/lang/en/errors.php");
		$this->copyFile($from . "/resources/lang/en/emails.php", 		$to . "/resources/lang/en/emails.php");
		$this->copyFile($from . "/resources/lang/en/pagination.php", 	$to . "/resources/lang/en/pagination.php");
		// end todo

	}

	public static function mergeTranslations() {
	}



}
