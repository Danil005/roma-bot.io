<?php
include_once("vendor/autoload.php");



$handler = new \Roma\ServerHandler();
$data = json_decode(file_get_contents('php://input'));
$handler->parse($data);