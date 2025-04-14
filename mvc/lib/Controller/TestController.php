<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;
use PhpMailer\View;
use PhpMailer\Library\Snowflake\Snowflake;

class TestController extends Controller
{

    public function indexAction()
    {
        echo "<pre>";
        $flakegen = Snowflake::getInstance();
        $flake = $flakegen->generate();
        $decoded = $flakegen->decode($flake, 'Y-m-d H:i:s', 'Europe/Berlin');

        echo "Flake: $flake\n";

        echo "Decoded:\n";
        print_r($decoded);

        echo "Timestamp: " . $decoded['datetime'] . "\n";
    }

    public function testAction()
    {
        if (isset($_SESSION['user_id'])) {
            echo "<pre>";
            echo "Session is set.\n";
            echo "User ID: " . $_SESSION['user_id'] . "\n";
            echo "Session Created At: " . $_SESSION['session_created_at'] . "\n";
            echo "Session ID: " . session_id() . "\n";

            print_r($_SESSION);
        } else {
            echo "Session is not set.\n";
        }
    }


}