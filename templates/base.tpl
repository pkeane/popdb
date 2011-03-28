<!doctype html>
<html lang="en">
<head>
<base href="{$app_root}">
<meta charset="utf-8">
{block name="head-meta"}{/block}

<title>{block name="title"}{$main_title}{/block}</title>

<link rel="stylesheet" href="www/css/base.css">
<link rel="stylesheet" href="www/css/style.css">
{block name="head-links"}{/block}

{block name="head-js"}
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
{/block}

{block name="head"}{/block}
<script src="www/js/script.js"></script>

</head>
<body>
<div id="container">
<div id="header">
{block name="header"}
<h1>{$main_title}</h1>
<a href="attributes">attributes</a> | 
<a href="items">items</a> 
<form method="get" action="search">
search
<input type="text" name="q">
<input type="submit" value="go">
</form>
{/block}
</div>
<div id="main">{block name="main"}{/block}</div>
<div class="clear"></div>
<div id="footer">{block name="footer"}{/block}</div>
</div>
</body>
</html>
