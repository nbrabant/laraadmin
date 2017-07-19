<?php

namespace Dwij\Laraadmin;

class LATranslate
{
	// Warning : basicly, the trans method will get the matched key on the locale.json associative array on lang directory
    public function getTranslation($key, $fallback = '') {
		if (is_null($key) || strlen($key) === 0) {
			return trans($fallback);
		}

		return trans($key);
    }

    public function createTranslation($key, $value, $locale = null) {

    }


}
