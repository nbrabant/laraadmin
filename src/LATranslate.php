<?php

namespace Dwij\Laraadmin;

class LATranslate
{
  	// Warning : basicly, the trans method will get the matched key on the locale.json associative array on lang directory
  	public static function getTranslation($key, $fallback = '')
	{
		if (is_null($key) || strlen($key) === 0) {
			return trans($fallback);
		}

		return trans($key);
	}

	public static function createTranslation($key, $value, $locale = null) {

	}


}
