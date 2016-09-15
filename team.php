<?php

require_once dirname(__FILE__) . "/classes/Template.class.php";
require_once dirname(__FILE__) . "/classes/Request.class.php";

$team = new Template();
$team->setHTML("templates/team.html");

if(Request::isAjaxRequest()){
	header("X-TabernaHTMLTitle: Taberna da Centopeia Perneta - Equipe");
	echo $team;
} else {
	$menu = new Template();
	$menu->setHTML("templates/menu.html");
	$menu->setReplacement("SELECTED_PODCAST", "");
	$menu->setReplacement("SELECTED_TEXT", "");
	$menu->setReplacement("SELECTED_TEAM", "selected");
	$menu->setReplacement("SELECTED_CONTACT", "");

	$index = new Template();
	$index->setHTML("templates/index.html");
	$index->setReplacement("PAGE.TITLE", "Taberna da Centopeia Perneta - Equipe");
	$index->setReplacement("MENU", $menu);
	$index->setReplacement("CONTENT", $team);

	echo $index;
}

?>