<?php

require_once dirname(__FILE__) . "/../config.php";

class Template{

	private $replaces;
	private $file;
	private $content;

	public function __construct(){
		$this->replaces = array();

		$this->setReplacement("DOMAIN",DOMAIN);
		$this->setReplacement("YEAR",date("Y"));
	}

	public function __toString(){
		return str_replace(array_keys($this->replaces), array_values($this->replaces), $this->content);
	}

	public function setReplacement($key, $value) {
		if($key === null)
			return;

		$this->replaces["{#{".$key."}#}"] = $value;
	}

	public function setHTML($HTMLfile){
		$content = file_get_contents($HTMLfile);

		$this->content = $content === FALSE ? "" : $content;
		$this->file    = $HTMLfile;
	}

	public function getTemplateContent(){
		return $this->content;
	}

}

?>