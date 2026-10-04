<?php
use think\App;
require __DIR__ . '/../vendor/autoload.php';
$app = new App();
$app->http->run()->send();