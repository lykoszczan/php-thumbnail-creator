<?php

require_once __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL & ~E_NOTICE);

$app = new Application((string)$_GET['action']);
$app->setHandler((string)$_POST['service']);
$app->setDirectory((string)$_POST['directory']);
$app->setFile((array)$_FILES['image']);

$app->render();