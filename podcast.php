<?php
sleep(2);
require_once dirname(__FILE__) . "/classes/Template.class.php";
require_once dirname(__FILE__) . "/classes/Request.class.php";
require_once dirname(__FILE__) . "/classes/SiteDB.class.php";

$id = isset($_GET["id"]) ? $_GET["id"] : null;

$podcast = new Template();
$podcast->setHTML("templates/podcast.html");

$title = "Podcasts";
$pageDesc = "";

if(is_null($id)){

	$podcastHeader = new Template();
	$podcastHeader->setHTML("templates/podcastHeader.html");

	$podcastList = new Template();
	$podcastList->setHTML("templates/podcastList.html");

	$podcasts = array();

	$episodes = SiteDB::getPodcasts();

	foreach($episodes as $item){
		$podcastList->setReplacement("TITLE", $item["title"]);
		$podcastList->setReplacement("GUID", strtolower($item["guid"]));
		$podcastList->setReplacement("MOBILE", Request::isMobile() ? "_mob" : "");
		$podcastList->setReplacement("DATE", date("d/m/Y", strtotime($item["date_published"])));

		$podcasts[] = (string) $podcastList;
	}

	$pageDesc = "Nada melhor que uma boa conversa de Taberna para arranjar confusão e iniciar aventura, não?<br />Entre rolagens de dados, sempre há espaço para boas histórias e conversas.<br />Puxe uma cadeira, faça seu pedido à nossa orc graçonete Orcarina e divirta-se!";

	$podcast->setReplacement("CONTENT", $podcastHeader . implode("",$podcasts));
}else{
	$podcastItem = new Template();
	$podcastItem->setHTML("templates/podcastItem.html");

	$episode = SiteDB::getPodcastByGuid($id);

	$podcastItem->setReplacement("TITLE", $episode["title"]);
	$podcastItem->setReplacement("GUID", strtolower($episode["guid"]));
	$podcastItem->setReplacement("MOBILE", Request::isMobile() ? "_mob" : "");
	$podcastItem->setReplacement("DATE", date("d/m/Y", strtotime($episode["date_published"])));
	$podcastItem->setReplacement("DESCRIPTION", $episode["description"]);
	$podcastItem->setReplacement("URL", $episode["url"]);

	$pageDesc = substr($episode["description"], 0, 252) . "...";

	$title = $episode["title"];

	$podcast->setReplacement("CONTENT", $podcastItem);
}




if(Request::isAjaxRequest()){
	header("X-TabernaHTMLTitle: Taberna da Centopeia Perneta - " . utf8_decode($title));
	echo $podcast;
} else {
	$menu = new Template();
	$menu->setHTML("templates/menu.html");
	$menu->setReplacement("SELECTED_PODCAST", "selected");
	$menu->setReplacement("SELECTED_TEXT", "");
	$menu->setReplacement("SELECTED_TEAM", "");
	$menu->setReplacement("SELECTED_CONTACT", "");

	$index = new Template();
	$index->setHTML("templates/index.html");
	$index->setReplacement("PAGE.TITLE", "Taberna da Centopeia Perneta - " . $title);
	$index->setReplacement("PAGE.DESC", $pageDesc);
	$index->setReplacement("MENU", $menu);
	$index->setReplacement("CONTENT", $podcast);

	echo $index;
}

?>