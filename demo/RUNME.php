<?php

include '../src/TinyBinder.php';

$tinyBinder = new TinyBinder('template.html');
$tinyBinder->addAsset('pageName', 'Demo Page');
$tinyBinder->addAsset('heading', '<h1>Demo Page</h1>');

echo $tinyBinder->getHtml();
