<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;
use PhpMailer\View;
use PhpMailer\Library\Snowflake\Snowflake;

class TestController extends Controller
{

    public function indexAction()
    {
        $flakegen = Snowflake::getInstance();
        $flake = $flakegen->generate();
        $decoded = $flakegen->decode($flake, 'Y-m-d H:i:s', 'Europe/Berlin');

        echo "Flake: $flake\n";

        echo "Decoded:\n";
        print_r($decoded);

        echo "Timestamp: " . $decoded['datetime'] . "\n";
    }


}