<?php
    header('Content-Type: application/rss+xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>'; 
?>
<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
    xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>Taberna da Centopeia Perneta</title>
        <atom:link href="http://tabernadacentopeiaperneta.com.br/feed/" rel="self" type="application/rss+xml" />
        <link>http://tabernadacentopeiaperneta.com.br</link>
        <description>Entre na mais insana taberna do reinado, aprecie a música de nossos bardos ou então arranje briga com o Anão bêbado!</description>
        <copyright>Copyright &#xA9; 2013-<?php echo(date("Y")); ?> Taberna da Centopeia Perneta </copyright>
        <managingEditor>taberneiro@gmail.com (Taberneiro)</managingEditor>
        <webMaster>taberneirotcp@gmail.com (Taberneiro)</webMaster>
        <language>pt-br</language>
        <image>
            <url>http://tabernadacentopeiaperneta/img/capa_f.jpg</url>
            <title>Taberna da Centopeia Perneta</title>
            <link>http://tabernadacentopeiaperneta.com.br</link>
            <width>144</width>
            <height>144</height>
        </image>
        <itunes:subtitle>A Taberna do seu RPG</itunes:subtitle>
        <itunes:summary>Entre na mais insana taberna do reinado, aprecie a música de nossos bardos ou então arranje briga com o Anão bêbado!</itunes:summary>
        <itunes:keywords>RPG, roleplay, humor, games, livros, filmes, animes</itunes:keywords>
        <itunes:category text="Games &amp; Hobbies">
            <itunes:category text="Hobbies" /></itunes:category>
        <itunes:category text="Comedy" />
        <itunes:author>tabernadacentopeiaperneta.com.br</itunes:author>
        <itunes:owner>
            <itunes:name>tabernadacentopeiaperneta.com.br</itunes:name>
            <itunes:email>taberneirotcp@gmail.com</itunes:email>
        </itunes:owner>
        <itunes:block>no</itunes:block>
        <itunes:explicit>no</itunes:explicit>
        <itunes:image href="http://tabernadacentopeiaperneta.com.br/img/capa.jpg" />
<?php
require_once dirname(__FILE__) . "/classes/SiteDB.class.php";
$podcasts = SiteDB::getPodcasts();
foreach($podcasts as $cast){
?>
        <item>
            <title><?php echo $cast["title"]; ?></title>
            <link><?php echo $cast["link"]; ?></link>
            <guid>http://tabernadacentopeiaperneta.com.br/podcast/<?php echo $cast["guid"]; ?></guid>
            <pubDate><?php echo date("r",strtotime($cast["date_published"])); ?></pubDate>
            <description>
                <![CDATA[<?php echo $cast["description"]; ?>]]>
            </description>
            <enclosure url="<?php echo $cast["url"]; ?>" type="audio/mpeg" length="<?php echo $cast["size"]; ?>" />
            <itunes:duration><?php echo $cast["duration"]; ?></itunes:duration>
            <itunes:author>tabernadacentopeiaperneta.com.br</itunes:author>
            <itunes:explicit>no</itunes:explicit>
            <itunes:block>no</itunes:block>
        </item>
<?php } ?>

    </channel>
</rss>