<?php
class Config {
	public static function get($path = null) {
		if($path) {
			$config = $GLOBALS['config'];
			$path = explode('/',$path);

			foreach ($path as $data) {
				if(isset($config[$data])) {$config = $config[$data];}
			}
			return $config;
		}
		return false;
	}
}
?>