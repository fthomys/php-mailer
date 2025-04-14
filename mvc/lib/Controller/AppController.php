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

        $userId = $_SESSION['user_id'];
        $username = "";
        $otherUsers = [];

        $select = new Select($this->connection);

        $select->from(new User())
            ->columns(['username'])
            ->where('id = :id', ['id' => $userId]);
        $user = $select->fetchAll();
        if ($user) {
            $username = $user[0]['username'];
        }

        $selectOthers = new Select($this->connection);
        $selectOthers->from(new User())
            ->columns(['id', 'username', 'display_name'])
            ->where('id != :id', ['id' => $userId]);

        $otherUsers = $selectOthers->fetchAll();

        $view = new View();
        echo $view->setInnerLayout('../view/app.phtml')
            ->setOuterLayout('../view/outerlayout.phtml')
            ->setTitle("PhpMailer")
            ->setData('username', $username)
            ->setData('users', $otherUsers)
            ->addHeadScript('/js/tailwind.js')
            ->addHeadScript("/js/tailwind.config.js")
            ->addHeadScript("/js/alpine.js")
            ->render();
    }
}
