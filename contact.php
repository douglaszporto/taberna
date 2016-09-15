<?php

require_once dirname(__FILE__) . "/classes/Template.class.php";
require_once dirname(__FILE__) . "/classes/Request.class.php";
require_once dirname(__FILE__) . "/classes/SiteDB.class.php";

$name = isset($_POST["data-name"]) ? $_POST["data-name"] : null;
$email = isset($_POST["data-email"]) ? $_POST["data-email"] : null;
$message = isset($_POST["data-message"]) ? $_POST["data-message"] : null;

if(!empty($name) || !empty($email) || !empty($message)){
	$contactMessage = $name . "\n" . $email . "\n" . $message;

	if(SiteDB::saveMessageInbox($name, $email, $message)){

		$to      = 'taberneirotcp@gmail.com';
		$subject = 'Contato pelo Site - Taberna';
		$headers = 'From: taberneirotcp@gmail.com' . "\r\n" .
			'Reply-To: taberneirotcp@gmail.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $contactMessage, $headers);
	}

	exit;
}

$contact = new Template();
$contact->setHTML("templates/contact.html");

if(Request::isAjaxRequest()){
	header("X-TabernaHTMLTitle: Taberna da Centopeia Perneta - Contato");
	echo $contact;
} else {
	$menu = new Template();
	$menu->setHTML("templates/menu.html");
	$menu->setReplacement("SELECTED_PODCAST", "");
	$menu->setReplacement("SELECTED_TEXT", "");
	$menu->setReplacement("SELECTED_TEAM", "");
	$menu->setReplacement("SELECTED_CONTACT", "selected");

	$index = new Template();
	$index->setHTML("templates/index.html");
	$index->setReplacement("PAGE.TITLE", "Taberna da Centopeia Perneta - Contato");
	$index->setReplacement("MENU", $menu);
	$index->setReplacement("CONTENT", $contact);

	echo $index;
}

?>