<?php

use Anamorph\Router\Router;

/**
 * This is the place you do routing for your website.
 */

 Router::get('/home/{?id}', function($id = 'Daniel') {
    echo '/home (Work) ' . $id;
 });
 Router::get('/', 'HomeController@index');