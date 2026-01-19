<?php
namespace App\Controller;

use App\View\View;
use App\Service\NoteService;
use App\Model\Like;
use App\Model\File;
use Core\Helper\SessionManager;
use Core\Helper\Logger;
use Core\Helper\PdfExtractor;
use Service\Ai\ClientLLM;

class NoteController {
    
    public function show($id): void {
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
        
        // Verifica se l'utente corrente ha già messo like
        $userId = SessionManager::userId();
        if ($userId) {
            $existingLike = (new Like())
                ->where('student_id', '=', $userId)
                ->where('note_id', '=', (int)$id)
                ->first();
            $noteData['user_has_liked'] = $existingLike !== null;
        }
        
        // Conta i like
        $likesCount = (new Like())
            ->where('note_id', '=', (int)$id)
            ->count();
        $noteData['likes_count'] = $likesCount;
        
        View::render('noteDetail', 'page', [
            "title" => $noteData['title'],
            "note" => $noteData,
            "currentUserId" => $userId,
            "isLoggedIn" => SessionManager::isLoggedIn()
        ]);
    }

    public function toggleLike($id): void {
        if (!SessionManager::isLoggedIn()) {
            SessionManager::flash('error', 'Devi essere loggato per mettere like');
            header('Location: /login');
            exit;
        }
        
        $userId = SessionManager::userId();
        $noteId = (int)$id;
        
        // Verifica se il like esiste già
        $existingLike = (new Like())
            ->where('student_id', '=', $userId)
            ->where('note_id', '=', $noteId)
            ->first();
        
        if ($existingLike) {
            // Rimuovi il like
            (new Like())
                ->where('student_id', '=', $userId)
                ->where('note_id', '=', $noteId)
                ->delete();
            
            Logger::getInstance()->info("Like rimosso", [
                "user_id" => $userId,
                "note_id" => $noteId
            ]);
        } else {
            // Aggiungi il like
            (new Like())->insert([
                'student_id' => $userId,
                'note_id' => $noteId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            Logger::getInstance()->info("Like aggiunto", [
                "user_id" => $userId,
                "note_id" => $noteId
            ]);
        }
        
        header('Location: /note/' . $id);
        exit;
    }

    public function chat($id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /note/' . $id);
            exit;
        }
        
        $question = trim($_POST['question'] ?? '');
        
        if (empty($question)) {
            SessionManager::flash('error', 'La domanda non può essere vuota');
            header('Location: /note/' . $id);
            exit;
        }
        
        $noteId = (int)$id;
        
        try {
            Logger::getInstance()->info("Chat AI - Inizio", ["note_id" => $noteId, "question" => $question]);
            
            // Carica la nota
            $noteData = NoteService::getFullNote($noteId);
            
            if (!$noteData) {
                SessionManager::flash('error', 'Nota non trovata');
                header('Location: /note/' . $id);
                exit;
            }
            
            // Carica i file della nota
            $files = (new File())->where('note_id', '=', $noteId)->get();
            Logger::getInstance()->info("Chat AI - File trovati", ["count" => count($files)]);
            
            if (empty($files)) {
                SessionManager::flash('error', 'Nessun file disponibile per fare domande all\'AI. La nota deve avere almeno un file PDF o TXT allegato.');
                header('Location: /note/' . $id);
                exit;
            }
            
            // Estrai testo dal primo file
            $firstFile = $files[0];
            $filePath = dirname(__DIR__, 2) . '/' . ltrim($firstFile['filepath'], '/');
            Logger::getInstance()->info("Chat AI - File path", ["path" => $filePath, "format" => $firstFile['format']]);
            
            if (!file_exists($filePath)) {
                SessionManager::flash('error', 'File non trovato: ' . $filePath);
                header('Location: /note/' . $id);
                exit;
            }
            
            // Estrai contenuto in base al formato
            $fileContent = '';
            $format = strtolower($firstFile['format'] ?? '');
            
            if ($format === 'pdf') {
                Logger::getInstance()->info("Chat AI - Estrazione PDF");
                $fileContent = PdfExtractor::extract($filePath);
            } else if (in_array($format, ['txt', 'md'])) {
                Logger::getInstance()->info("Chat AI - Lettura TXT/MD");
                $fileContent = file_get_contents($filePath);
            } else {
                SessionManager::flash('error', 'Formato file non supportato per AI: ' . $format);
                header('Location: /note/' . $id);
                exit;
            }
            
            Logger::getInstance()->info("Chat AI - Contenuto estratto", ["length" => strlen($fileContent)]);
            
            if (empty(trim($fileContent))) {
                SessionManager::flash('error', 'Il file non contiene testo leggibile');
                header('Location: /note/' . $id);
                exit;
            }
            
            // Chiama l'AI
            Logger::getInstance()->info("Chat AI - Chiamata API");
            $aiResponse = ClientLLM::callLLM($firstFile['filename'], $fileContent, $question);
            Logger::getInstance()->info("Chat AI - Risposta ricevuta", ["length" => strlen($aiResponse)]);
            
            // Verifica se l'utente corrente ha già messo like
            $userId = SessionManager::userId();
            if ($userId) {
                $existingLike = (new Like())
                    ->where('student_id', '=', $userId)
                    ->where('note_id', '=', $noteId)
                    ->first();
                $noteData['user_has_liked'] = $existingLike !== null;
            }
            
            // Conta i like
            $likesCount = (new Like())
                ->where('note_id', '=', $noteId)
                ->count();
            $noteData['likes_count'] = $likesCount;
            
            // Mostra la risposta
            View::render('noteDetail', 'page', [
                "title" => $noteData['title'],
                "note" => $noteData,
                "currentUserId" => $userId,
                "isLoggedIn" => SessionManager::isLoggedIn(),
                "aiResponse" => $aiResponse
            ]);
            
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore AI chat", [
                "note_id" => $noteId,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
            
            // Mostra errore dettagliato (solo in development)
            $errorMsg = 'Errore: ' . $e->getMessage();
            SessionManager::flash('error', $errorMsg);
            header('Location: /note/' . $id);
            exit;
        }
    }
    
    public function addComment($id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /note/' . $id);
            exit;
        }
        
        if (!SessionManager::isLoggedIn()) {
            SessionManager::flash('error', 'Devi essere loggato per commentare');
            header('Location: /login');
            exit;
        }
        
        $content = trim($_POST['content'] ?? '');
        $parentId = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        
        if (empty($content)) {
            SessionManager::flash('error', 'Il commento non può essere vuoto');
            header('Location: /note/' . $id);
            exit;
        }
        
        $userId = SessionManager::userId();
        $noteId = (int)$id;
        
        try {
            // Inserisci il commento
            (new \App\Model\Comment())->insert([
                'note_id' => $noteId,
                'student_id' => $userId,
                'content' => $content,
                'parent_comment_id' => $parentId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            Logger::getInstance()->info("Commento aggiunto", [
                "user_id" => $userId,
                "note_id" => $noteId,
                "parent_id" => $parentId
            ]);
            
            SessionManager::flash('success', $parentId ? 'Risposta aggiunta!' : 'Commento aggiunto!');
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore aggiunta commento", [
                "error" => $e->getMessage()
            ]);
            SessionManager::flash('error', 'Errore durante l\'aggiunta del commento');
        }
        
        header('Location: /note/' . $id);
        exit;
    }

    public function deleteComment($noteId, $commentId): void {
        if (!SessionManager::isLoggedIn()) {
            SessionManager::flash('error', 'Devi essere loggato');
            header('Location: /login');
            exit;
        }
        
        $userId = SessionManager::userId();
        $userRole = SessionManager::get('user')['role'] ?? 'student';
        $commentId = (int)$commentId;
        
        try {
            // Trova il commento
            $comment = \App\Model\Comment::find($commentId);
            
            if (!$comment) {
                SessionManager::flash('error', 'Commento non trovato');
                header('Location: /note/' . $noteId);
                exit;
            }
            
            // Verifica permessi: proprietario o admin
            $isOwner = $comment['student_id'] == $userId;
            $isAdmin = $userRole === 'admin';
            
            if (!$isOwner && !$isAdmin) {
                SessionManager::flash('error', 'Non hai i permessi per eliminare questo commento');
                header('Location: /note/' . $noteId);
                exit;
            }
            
            // Elimina il commento
            (new \App\Model\Comment())
                ->where('id', '=', $commentId)
                ->delete();
            
            Logger::getInstance()->info("Commento eliminato", [
                "user_id" => $userId,
                "comment_id" => $commentId,
                "is_admin" => $isAdmin
            ]);
            
            SessionManager::flash('success', 'Commento eliminato');
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore eliminazione commento", [
                "error" => $e->getMessage()
            ]);
            SessionManager::flash('error', 'Errore durante l\'eliminazione');
        }
        
        header('Location: /note/' . $noteId);
        exit;
    }
}