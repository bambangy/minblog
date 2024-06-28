<?php

namespace Controllers;

use bin\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view->render('home/index', []);
    }
}
