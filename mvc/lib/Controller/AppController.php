<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;

class AppController extends Controller
{
    public function indexAction(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }
    }
}
