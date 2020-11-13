<?php

class Fs {

	public $tree = [];
	public $csstreeview;
	public $root;

	public function __construct ($dir) {
		$this->csstreeview = require ('css.php');
		$this->root = $dir;
		$this->tree = $this->scan_function($dir, 1);
	}

	private function scan_function ($dir, $level) {
		$result = [];
		$scandir = scandir($dir);
		foreach ($scandir as $node) {
			if ($node == '..' or $node == '.') {
				// pass
			} else {
				if (is_file($dir.'/'.$node)) {
					$result[] = [
						'level' => $level,
						'name' => $node,
						'type' => 'file'
					];
				} elseif (is_dir($dir.'/'.$node)) {
					$result[] = [
						'level' => $level,
						'name' => $node,
						'type' => 'directory',
						'children' => $this->scan_function ($dir.'/'.$node, ++$level)
					];
					$level--;
				}
			}
		}
		return $result;
	}

	private $htmlIndentation = "  ";

	public function htmlview ($prettify = false) {
		$res = "<style>" . $this->csstreeview . "</style>";
		if ($prettify) {
			return $res
				. "\n\n<div class='treeview'>\n"
				. $this->htmlIndentation . "<div class='basescan'>" . $this->root . "</div>\n"
				. $this->htmlIndentation . "<ul>\n"
				. $this->htmlview_fn($this->tree, $prettify) . $this->htmlIndentation . "</ul>\n</div>\n";
		}
		return $res . "<div class='treeview'><ul>" . $this->htmlview_fn($this->tree, $prettify) . "</ul></div>";
	}

	private function htmlview_fn ($dir, $prettify = false) {
		$res = '';
		foreach ($dir as $value) {
			if ($value['type'] == 'file') {
				$prettify and $res .= str_repeat ($this->htmlIndentation, $value['level']*2);
				$res .= "<li><span class='f'>" . $value['name'] . "</span></li>";
				$prettify and $res .= "\n";
			} elseif ($value['type'] == 'directory') {
				$prettify and $res .= str_repeat ($this->htmlIndentation, $value['level']*2);
				$res .= "<li><span class='d'>" . $value['name'] . "</span>";
				$prettify and $res .= "\n" . str_repeat ($this->htmlIndentation, $value['level']*2+1);
				$res .= "<ul>";
				$prettify and $res .= "\n";
				$res .= $this->htmlview_fn ($value['children'], $prettify);
				$prettify and $res .= str_repeat ($this->htmlIndentation, $value['level']*2+1);
				$res .= "</ul>";
				$prettify and $res .= "\n" . str_repeat ($this->htmlIndentation, $value['level']*2);
				$res .= "</li>";
				$prettify and $res .= "\n";
			}
		}
		return $res;
	}

}
