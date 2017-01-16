<?php

require '../../vendor/autoload.php';

// Run app
$app = (new Action\MyApp())->get();
$app->run();

