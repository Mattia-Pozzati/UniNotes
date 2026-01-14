<?php
namespace App\Controller;

use App\View\View;
use App\Service\NoteService;

class PageController
{
    public function index()
    {
        $notes = NoteService::getNotesForHome();
        
        View::render('home', 'page', [
            "title" => "Home",
            "notes" => $notes
        ]);
    }
}
