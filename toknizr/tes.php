<?php

class Fm {

	public $root = 0;
	public $tree = [];

	public function __construct ($dir) {
		$this->csstreeview = require ('css.php');
		$this->tree = $this->scan_function($dir, 1);
	}

	/*
	 * lorem ipsunm
	 * lorem ipsunm
	 */
	private function scan_function ($dir, $level) {
		$result = [];
		foreach ($scandir as $node) {
			if ($node == '..' or $node == '.') {
				// pass
			} else {
				if (is_file($dir.'/'.$node)) {
					$result[] = [
						'level' => $level,
						'type' => 'file'
					];
			}
		}
		return $result;
	}

}
