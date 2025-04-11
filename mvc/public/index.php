<?php

require_once('../lib/autoloader.php');
spl_autoload_register('\PhpMailer\autoloader');

use PhpMailer\Dispatcher;

session_start();
Dispatcher::dispatch();

