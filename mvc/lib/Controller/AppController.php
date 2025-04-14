<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;
use PhpMailer\Database\User;
use PhpMailer\View;
use PhpMailer\Select;

class AppController extends Controller
{
    public function indexAction(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $username = "";

        $select = new Select($this->connection);
        $select->from(new User())
            ->where('id = :id', ['id' => $_SESSION['user_id']]);
        $user = $select->fetchAll();
        if ($user) {
            $username = $user[0]['username'];
        }

        $view = new View();
        echo $view->setInnerLayout('../view/app.phtml')
            ->setOuterLayout('../view/outerlayout.phtml')
            ->setTitle("PhpMailer")
            ->setData('username', $username)
            ->addHeadScript('/js/tailwind.js')
            ->addHeadScript("/js/tailwind.config.js")
            ->addHeadScript("/js/alpine.js")
            ->render();


    }
}
