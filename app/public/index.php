<?php
namespace MCPI;

define('APP_ENVIRONMENT', 'HTML');

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "code" . DIRECTORY_SEPARATOR . "autoload.php";

Core_Controller::route();
