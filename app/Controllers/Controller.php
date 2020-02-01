<?php

namespace App\Controllers;

use Anamorph\Views\View;

/**
 * Class Controller
 * Control all the controller. Use this and extends this on the Controller Class
 * every controller class must extends this class. So, we can view the layout.
 */
class Controller
{
    // protected $_controller, $_method;
    // // public $view;

    // public function __construct($_controller, $_method) {
    //     parent::__construct();
    //     $this->_controller = $_controller;
    //     $this->_method = $_method;
    // }

    public function view($viewName) {
        return new View($viewName);
    }
}