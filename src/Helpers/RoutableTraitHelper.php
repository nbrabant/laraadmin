<?php namespace Dwij\Laraadmin\Helpers;

use Dwij\Laraadmin\Models\Route;

trait RoutableTraitHelper
{
	/**
     * Get all of the routes category.
     */
    public function routes()
    {
        return $this->morphMany('Dwij\Laraadmin\Models\Route', 'routable');
    }

    public function getRoutableAttribute() {
        if (isset($this->routes)) {
            return $this->routes->first();
        }

        return false;
    }

    public function getRoutable($request) {
    	$attr = [];
    	foreach ($request as $field => $value) {
			if (strpos($field, 'route_') === false) {
				continue;
			}

			if (in_array($field, ['route_robots_index', 'route_robots_follow'])) {
				continue;
			}

			if (in_array($field, ['route_robots_index_hidden', 'route_robots_follow_hidden'])) {
				$value = (int)$value;
			}

			$attr[str_replace(['route_', '_hidden'], '', $field)] = $value;
    	}

    	return $attr;
    }
}
