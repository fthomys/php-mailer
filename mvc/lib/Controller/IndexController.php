<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;
use PhpMailer\View;

class IndexController extends Controller
{

    public function indexAction()
    {
        $view = new View();
        echo $view->setInnerLayout('../view/mainpage.phtml')
            ->setOuterLayout('../view/outerlayout.phtml')
            ->setTitle("Registrieren")
            ->addHeadScript('/js/tailwind.js')
            ->addHeadScript("/js/tailwind.config.js")
            ->addHeadScript("/js/alpine.js")
            ->render();
    }
}