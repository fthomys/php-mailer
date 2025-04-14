<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;

class AppController extends Controller
{
    public function indexAction(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
    }
}
