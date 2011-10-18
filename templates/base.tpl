<!doctype html>
<html lang="en">
	<head>
		<base href="{$app_root}">
		<meta charset="utf-8">
		{block name="head-meta"}{/block}

		<title>{block name="title"}{/block}</title>

		<link rel="stylesheet" href="www/css/base.css">
		<link rel="stylesheet" href="www/css/style.css">
		{block name="head-links"}{/block}
        <link rel="shortcut icon" href="www/images/favicon.ico">

		{block name="head-js"}
		{/block}

		{block name="head"}{/block}
		<script src="www/js/jquery.js"></script>
		<script src="www/js/showdown.js"></script>
		<script src="www/js/script.js"></script>

	</head>
	<body>
		<div id="container">{block name="main"}{/block}</div>
	</body>
</html>
