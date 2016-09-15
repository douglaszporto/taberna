<?php

require_once dirname(__FILE__) . "/classes/Template.class.php";
require_once dirname(__FILE__) . "/classes/Request.class.php";
require_once dirname(__FILE__) . "/classes/SiteDB.class.php";

$home = new Template();
$home->setHTML("templates/home.html");

$homeHeader = new Template();
$homeHeader->setHTML("templates/homeHeader.html");

$homeItem = new Template();
$homeItem->setHTML("templates/homeItem.html");

$items = SiteDB::getHomeItems();

$data = array();

foreach($items as $item){
	$homeItem->setReplacement("TITLE", $item["title"]);
	$homeItem->setReplacement("GUID", strtolower($item["guid"]));
	$homeItem->setReplacement("MOBILE", Request::isMobile() ? "_mob" : "");
	$homeItem->setReplacement("DATE", date("d/m/Y", strtotime($item["date_published"])));
	$homeItem->setReplacement("TYPE", $item["type"]);

	$data[] = (string) $homeItem;
}

$home->setReplacement("CONTENT", $homeHeader . implode("", $data));

if(Request::isAjaxRequest()){
	echo $home;
	header("X-TabernaHTMLTitle: Taberna da Centopeia Perneta");
} else {
	$menu = new Template();
	$menu->setHTML("templates/menu.html");
	$menu->setReplacement("SELECTED_PODCAST", "");
	$menu->setReplacement("SELECTED_TEXT", "");
	$menu->setReplacement("SELECTED_TEAM", "");
	$menu->setReplacement("SELECTED_CONTACT", "");

	$index = new Template();
	$index->setHTML("templates/index.html");
	$index->setReplacement("PAGE.TITLE", "Taberna da Centopeia Perneta");
	$index->setReplacement("PAGE.DESC", "Nada melhor que uma boa conversa de Taberna para arranjar confusão e iniciar aventura, não?<br />Entre rolagens de dados, sempre há espaço para boas histórias e conversas.<br />Puxe uma cadeira, faça seu pedido à nossa orc graçonete Orcarina e divirta-se!");
	$index->setReplacement("MENU", $menu);
	$index->setReplacement("CONTENT", $home);

	echo $index;
}

?>