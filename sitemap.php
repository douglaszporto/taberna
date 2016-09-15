<?php
	header('Content-Type: application/xml; charset=utf-8');
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url><loc>http://tabernadacentopeiaperneta.com.br/</loc></url>
	<url><loc>http://tabernadacentopeiaperneta.com.br/podcast</loc></url>
	<url><loc>http://tabernadacentopeiaperneta.com.br/textos</loc></url>
	<url><loc>http://tabernadacentopeiaperneta.com.br/equipe</loc></url>
	<url><loc>http://tabernadacentopeiaperneta.com.br/contato</loc></url>
		<?php
			require_once dirname(__FILE__) . "/classes/SiteDB.class.php";
			$podcasts = SiteDB::getPodcasts();
			foreach($podcasts as $cast){
		?><url><loc>http://tabernadacentopeiaperneta.com.br/podcast/<?php echo $cast["guid"]; ?></loc></url>
		<?php 
			} 
			require_once dirname(__FILE__) . "/classes/SiteDB.class.php";
			$texts = SiteDB::getTexts();
			foreach($texts as $text){
		?><url><loc>http://tabernadacentopeiaperneta.com.br/textos/<?php echo $text["guid"]; ?></loc></url>
		<?php } ?>
</urlset>