<?php
namespace App\Controller;
use App\View\View;

class PageController
{
    public function index()
    {
        View::render('home',"page", ["title" => "Home"]);
    }
}

?>


