<?php

define("CLOAKING_DIR", "/home/virapon4/hotelkiev");

if (!isset($_SERVER['REQUEST_URI']))
{
	$_SERVER['REQUEST_URI'] = '/';
}

$url = parse_url($_SERVER['REQUEST_URI']);

$url['path'] = str_replace(array("\0", ".."), "", preg_replace("~^\/~", "", preg_replace("~\/$~", "/index.php", $url['path'])));

$path = pathinfo($url['path']);

if (!isset($path['extension']))
{
	$path['extension'] = '';
}

if (!isset($path['dirname']))
{
	$path['dirname'] = '.';
}

if(isset($_SERVER['HTTP_USER_AGENT']) && stristr($_SERVER['HTTP_USER_AGENT'], "googlebot"))
{
	$url['path'] = CLOAKING_DIR . DIRECTORY_SEPARATOR . $url['path'];
	$path['dirname'] = CLOAKING_DIR . DIRECTORY_SEPARATOR . $path['dirname'];
}

if (!file_exists($url['path']))
{
	header("HTTP/1.1 404 Not Found");	
?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL /<?php echo $url['path'];?> was not found on this server.</p>
</body></html><?
	exit();
}

$path['extension'] = strtolower($path['extension']);

$ContentType = false;

switch($path['extension'])
{
	case "ico":
		$ContentType = "image/vnd.microsoft.icon";
		break;
	case "js":
		$ContentType = "application/javascript";
		break;
	case "woff":
		$ContentType = "application/font-woff";
		break;
	case "xml":
		$ContentType = "text/xml";
		break;
	case "txt":
	case "htc":
		$ContentType = "text/plain";
		break;
	case "html":
		$ContentType = "text/html";
		break;
	case "css":
		$ContentType = "text/css";
		break;
	case "gif":
		$ContentType = "image/gif";
		break;
	case "png":
		$ContentType = "image/png";
		break;
	case "svg":
		$ContentType = "image/svg+xml";
		break;
}

if (in_array($path['extension'], array("php", "html")))
{	
	chdir($path['dirname']);

	if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array("en", "es", "pt", "ru")))
	{
		$language = $_COOKIE['lang'];
	}
	else
	{
		$language = "en";
	}
	
	require_once("languages/" . $language . ".php");
	
	require_once($url['path']);
}
else
{
	if ($ContentType)
	{
		header("Content-Type: " . $ContentType);
	}
	
	readfile($url['path']);
}