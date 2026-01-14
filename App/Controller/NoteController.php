<?php
namespace App\Controller;

use App\View\View;
use App\Service\NoteService;
use Core\Helper\Logger;

class NoteController {
  public function show($id) {
    Logger::getInstance()->info("Visualizzazione nota", ["note_id" => $id]);
    
    $noteData = NoteService::getFullNote((int)$id);
    
    if (!$noteData) {
        http_response_code(404);
        echo "<!DOCTYPE html><html><body>";
        echo "<h1>404 - Nota non trovata</h1>";
        echo "<a href='/'>Torna alla home</a>";
        echo "</body></html>";
        return;
    }
    
    View::render('note-detail', 'page', [
        "title" => $noteData['title'],
        "note" => $noteData
    ]);
  }

  public function toggleLike($id) {
    header('Location: /note/' . $id);
    exit;
  }

  public function addComment($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /note/' . $id);
        exit;
    }
    
    header('Location: /note/' . $id);
    exit;
  }
}
?>
