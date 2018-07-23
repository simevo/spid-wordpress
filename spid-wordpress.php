<?php

session_start();

require_once("../vendor/autoload.php");
require_once dirname(__FILE__) . '/src/SpidWordPress.php';

SpidWordPress::getInstance();
