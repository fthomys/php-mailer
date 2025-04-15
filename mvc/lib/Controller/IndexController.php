<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;
use PhpMailer\Database\User;
use PhpMailer\Select;
use PhpMailer\View;

class IndexController extends Controller
{

    public function indexAction()
    {

        $username = "";

        if (isset($_SESSION['user_id'])) {
            $select = new Select($this->connection);
            $select->from(new User())
                ->where('id = :id', ['id' => $_SESSION['user_id']]);
            $user = $select->fetchAll();
            if ($user) {
                $username = $user[0]['username'];
            }
        }


        $view = new View();
        echo $view->setInnerLayout('../view/mainpage.phtml')
            ->setOuterLayout('../view/outerlayout.phtml')
            ->setTitle("Registrieren")
            ->addHeadScript('/js/tailwind.js')
            ->setData("username", $user[0]['username'] ?? "")
            ->addHeadScript("/js/tailwind.config.js")
            ->addHeadScript("/js/alpine.js")
            ->render();
    }
}