<?php

require_once dirname(__FILE__) . "/classes/Template.class.php";
require_once dirname(__FILE__) . "/classes/Request.class.php";
require_once dirname(__FILE__) . "/classes/SiteDB.class.php";

$id = isset($_GET["id"]) ? $_GET["id"] : null;

$text = new Template();
$text->setHTML("templates/text.html");

$title = "Textos";
$pageDesc = "Que tal alguns artigos para incrementar sua partida de RPG?<br />Textos dos mais variados assuntos para utilizar em suas partidas (se você for mestre/narrador) ou mostrar para seu(a) querido(a) mestre.";

if(is_null($id)){

	$textHeader = new Template();
	$textHeader->setHTML("templates/textHeader.html");	

	$textItem = new Template();
	$textItem->setHTML("templates/textItem.html");

	$texts = SiteDB::getTexts();
	$items = array();
	foreach($texts as $t){
		$textItem->setReplacement("TITLE", $t["title"]);
		$textItem->setReplacement("DATE", date("d/m/Y", strtotime($t["date_published"])));
		$textItem->setReplacement("MOBILE", Request::isMobile() ? "_mob" : "");
		$textItem->setReplacement("GUID", $t["guid"]);
		$textItem->setReplacement("USER", $t["user"]);
		$items[] = (string) $textItem;
	}

	$text->setReplacement("CONTENT", $textHeader . implode("", $items));
}else{
	$textSelected = SiteDB::getTextByGuid($id);

	$textItem = new Template();
	$textItem->setHTML("templates/textContent.html");

	$textContent = new Template();
	$textContent->setHTML("db/content/" . $textSelected["guid"] . ".html");

	$textItem->setReplacement("TITLE", $textSelected["title"]);
	$textItem->setReplacement("DATE", date("d/m/Y", strtotime($textSelected["date_published"])));
	$textItem->setReplacement("MOBILE", Request::isMobile() ? "_mob" : "");
	$textItem->setReplacement("USER", $textSelected["user"]);
	$textItem->setReplacement("GUID", $textSelected["guid"]);
	$textItem->setReplacement("DESCRIPTION", $textContent);

	$title = $textSelected["title"];

	$pageDesc = substr($textContent, 0, 252) + "...";

	$text->setReplacement("CONTENT", $textItem);
}


if(Request::isAjaxRequest()){
	header("X-TabernaHTMLTitle: Taberna da Centopeia Perneta - " . utf8_decode($title));
	echo $text;
} else {
	$menu = new Template();
	$menu->setHTML("templates/menu.html");
	$menu->setReplacement("SELECTED_PODCAST", "");
	$menu->setReplacement("SELECTED_TEXT", "selected");
	$menu->setReplacement("SELECTED_TEAM", "");
	$menu->setReplacement("SELECTED_CONTACT", "");

	$index = new Template();
	$index->setHTML("templates/index.html");
	$index->setReplacement("PAGE.TITLE", "Taberna da Centopeia Perneta - " . $title);
	$index->setReplacement("PAGE.DESC", $pageDesc);
	$index->setReplacement("MENU", $menu);
	$index->setReplacement("CONTENT", $text);

	echo $index;
}

?>