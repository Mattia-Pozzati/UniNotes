<?php
namespace App\Controller;

use App\Model\User;
use App\View\View;
use App\Service\NoteService;
use App\Model\Like;
use App\Model\File;
use App\Model\Note;
use App\Model\Course;
use Core\Database\Database;
use Core\Helper\SessionManager;
use Core\Helper\Logger;
use Core\Helper\PdfExtractor;
use Service\Ai\ClientLLM;
use Exception;
use App\Model\Comment;
use App\Model\Notification;

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

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        if (!SessionManager::isLoggedIn()) {
            SessionManager::flash('error', 'Devi essere loggato per caricare una nota');
            header('Location: /login');
            exit;
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $courseSel = trim((string)($_POST['course'] ?? ''));
        $newCourse = trim((string)($_POST['new_course'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $university = trim((string)($_POST['university'] ?? ''));
        $note_type = trim((string)($_POST['note_type'] ?? '')) ?: null;
        $format = trim((string)($_POST['format'] ?? ''));
        $visibility = trim((string)($_POST['visibility'] ?? 'public'));

        // Validazione minima
        if ($title === '' || $format === '' || $university === '' || empty($_FILES['file'])) {
            SessionManager::flash('error', 'Campi obbligatori mancanti');
            header('Location: /note/new');
            exit;
        }

        $studentId = SessionManager::userId();

        try {
            Logger::getInstance()->info('Creazione nuova nota - inizio', ['student_id' => $studentId]);

            // Gestione corso (selezionato o nuovo)
            $courseId = null;
            if ($courseSel === '__new' || $courseSel === '') {
                if ($newCourse === '') {
                    throw new Exception('Nome del nuovo corso mancante');
                }
                $courseId = (new Course())->insert([
                    'name' => $newCourse,
                ]);
            } else {
                $courseId = (int)$courseSel;
            }

            // Inserisci la nota
            $noteData = [
                'title' => $title,
                'description' => $description,
                'student_id' => $studentId,
                'note_type' => $note_type,
                'format' => $format,
                'university' => $university,
                'visibility' => $visibility,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $noteId = (new Note())->insert($noteData);
            if (!$noteId) throw new Exception('Impossibile creare la nota');

            // Collega nota e corso (NOTE_COURSE)
            if ($courseId) {
                $pdo = Database::getInstance();
                $stmt = $pdo->prepare('INSERT INTO NOTE_COURSE (note_id, course_id) VALUES (?, ?)');
                $stmt->execute([(int)$noteId, (int)$courseId]);
            }

            if (!empty($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                try {
                    $fileModel = new File();
                    $fileId = $fileModel->createForNote((int)$noteId, [
                        'tmp_path' => $_FILES['file']['tmp_name'],
                        'original_name' => $_FILES['file']['name'],
                        'size' => $_FILES['file']['size']
                    ]);
                    
                    Logger::getInstance()->info("File caricato con successo", [
                        "file_id" => $fileId,
                        "note_id" => $noteId,
                        "filename" => $_FILES['file']['name']
                    ]);
                } catch (Exception $e) {
                    Logger::getInstance()->error("Errore upload file", [
                        "note_id" => $noteId,
                        "error" => $e->getMessage()
                    ]);
                    // Il file non è stato caricato ma la nota esiste già
                    SessionManager::flash('warning', 'Nota creata ma errore durante upload file: ' . $e->getMessage());
                }
            } else {
                Logger::getInstance()->warning("Nessun file caricato o errore upload", [
                    "note_id" => $noteId,
                    "file_error" => $_FILES['file']['error'] ?? 'missing'
                ]);
            }

            SessionManager::flash('success', 'Nota pubblicata con successo');

            $adminUsers = (new User())->select(['id'])
                ->where('role', '=', "admin")
                ->get();

            foreach ($adminUsers as $admin) {
                NotificationController::sendNotification(
                    $noteId,
                    $admin['id'],
                    $studentId,
                    'System',
                    'Nota caricata da ' . SessionManager::get('user')['name']
                );
            }
            header('Location: /note/' . $noteId);
            exit;

        } catch (Exception $e) {
            Logger::getInstance()->error('Errore creazione nota', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            SessionManager::flash('error', 'Errore durante la creazione della nota: ' . $e->getMessage());
            header('Location: /user/dashboard?tab=new-note');
            exit;
        }
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

            // Rimuovi notifica
            (new Notification())
            ->where('note_id','=', $noteId)
            ->where('sender_id','=', $userId)
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

            $authorId = (new Note())->select(['student_id'])
                ->where('id', '=', $noteId)
                ->first()['student_id'] ?? null;

            NotificationController::sendNotification(
                 $noteId,
                toUserId: $authorId,
                fromUserId: $userId,
                type: 'like',
                message: 'La tua nota ha ricevuto un nuovo like!'
            );
            
            Logger::getInstance()->info("Like aggiunto", [
                "user_id" => $userId,
                "note_id" => $noteId
            ]);
        }
        
        header('Location: /note/' . $id);
        exit;
    }

    public function edit(int $id): void {

        if (!SessionManager::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $note = NoteService::getFullNote($id);

        $courses = (new Course())->get();

        if (!$note) {
            http_response_code(404);
            echo "Nota non trovata";
            return;
        }

        // Carica primo file (se esiste)
        $files = (new File())->where('note_id', '=', (int)$id)->get();
        $existingFile = $files[0] ?? null;

        View::render('noteEdit', 'page', [
            'title' => 'Modifica nota',
            'action' => '/note/' . $id . '/update',
            'courses' => $courses,
            'title_value' => $note['title'],
            'description_value' => $note['description'] ?? '',
            'university_value' => $note['university'] ?? '',
            'note_type_value' => $note['note_type'] ?? '',
            'format_value' => $note['format'] ?? '',
            'selected_course_id' => $note['course'] ?? null,
            'visibility_value' => $note['visibility'] ?? 'public',
            'is_edit' => true,
            'existing_file' => $existingFile
        ]);
    }

    public function update($id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /note/' . $id);
            exit;
        }

        if (!SessionManager::isLoggedIn()) {
            SessionManager::flash('error', 'Devi essere loggato per modificare la nota');
            header('Location: /login');
            exit;
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $courseSel = trim((string)($_POST['course'] ?? ''));
        $newCourse = trim((string)($_POST['new_course'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $university = trim((string)($_POST['university'] ?? ''));
        $note_type = trim((string)($_POST['note_type'] ?? '')) ?: null;
        $format = trim((string)($_POST['format'] ?? ''));
        $visibility = trim((string)($_POST['visibility'] ?? 'public'));

        try {
            // Gestione corso
            $courseId = null;
            if ($courseSel === '__new') {
                if ($newCourse === '') throw new Exception('Nome corso mancante');
                $courseId = (new Course())->insert(['name' => $newCourse, 'created_at' => date('Y-m-d H:i:s')]);
            } else {
                $courseId = $courseSel !== '' ? (int)$courseSel : null;
            }

            // Aggiorna nota
            $updateData = [
                'title' => $title,
                'description' => $description,
                'note_type' => $note_type,
                'format' => $format,
                'university' => $university,
                'visibility' => $visibility,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            (new Note())->where('id', '=', (int)$id)->update($updateData);

            // Aggiorna NOTE_COURSE (elimina e reinserisce)
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare('DELETE FROM NOTE_COURSE WHERE note_id = ?');
            $stmt->execute([(int)$id]);
            if ($courseId) {
                $stmt = $pdo->prepare('INSERT INTO NOTE_COURSE (note_id, course_id) VALUES (?, ?)');
                $stmt->execute([(int)$id, (int)$courseId]);
            }

            // File: sostituisci se è stato inviato un nuovo file
            if (!empty($_FILES['file']) && !empty($_FILES['file']['tmp_name'])) {
                $files = (new File())->where('note_id', '=', (int)$id)->get();
                if (!empty($files)) {
                    // sostituisci primo file
                    $first = $files[0];
                    File::replace((int)$first['id'], [
                        'tmp_path' => $_FILES['file']['tmp_name'],
                        'original_name' => $_FILES['file']['name'],
                        'size' => $_FILES['file']['size'] ?? 0
                    ]);
                } else {
                    (new File())->createForNote((int)$id, [
                        'tmp_path' => $_FILES['file']['tmp_name'],
                        'original_name' => $_FILES['file']['name'],
                        'size' => $_FILES['file']['size'] ?? 0
                    ]);
                }
            }

            $downloaderUsers = (new User())
                ->select(
                    ['USER.id']
                )
                ->join('NOTE_DOWNLOAD', 'USER.id', '=', 'NOTE_DOWNLOAD.student_id')
                ->where('NOTE_DOWNLOAD.note_id', '=', (int)$id)
                ->get();

            foreach ($downloaderUsers as $user) {
                $user_id = $user['id'];

                NotificationController::sendNotification(
                    $id,
                    $user_id,
                    SessionManager::get('user')['id'],
                    'System',
                    'La nota che hai scaricato è stata aggiornata. Vai a controllarla'
                );

            }

            
            SessionManager::flash('success', 'Nota aggiornata con successo');
            header('Location: /note/' . $id);
            exit;
        } catch (Exception $e) {
            Logger::getInstance()->error('Errore aggiornamento nota', ['error' => $e->getMessage()]);
            SessionManager::flash('error', 'Errore durante l\'aggiornamento: ' . $e->getMessage());
            header('Location: /note/' . $id . '/edit');
            exit;
        }
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
            (new Comment())->insert([
                'note_id' => $noteId,
                'student_id' => $userId,
                'content' => $content,
                'parent_comment_id' => $parentId,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            NotificationController::sendNotification(
                $noteId,
                toUserId: (new Note())->select(['student_id'])->where('id', '=', $noteId)->first()['student_id'] ?? null,
                fromUserId: $userId,
                type: 'comment',
                message: 'La tua nota ha ricevuto un nuovo commento!'
            );
            
            Logger::getInstance()->info("Commento aggiunto", [
                "user_id" => $userId,
                "note_id" => $noteId,
                "parent_id" => $parentId
            ]);
            
            SessionManager::flash('success', $parentId ? 'Risposta aggiunta!' : 'Commento aggiunto!');
        } catch (Exception $e) {
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

    public function ban($id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /note/' . $id);
            exit;
        }

        if (!SessionManager::isLoggedIn() || !(SessionManager::get('user')['is_admin'] ?? false)) {
            SessionManager::flash('error', 'Permessi insufficienti per bloccare la nota');
            header('Location: /note/' . $id);
            exit;
        }

        try {
            (new Note())->where('id', '=', (int)$id)->update([
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

            Logger::getInstance()->info("Nota bloccata", [
                "note_id" => $id,
                "admin_id" => SessionManager::userId()
            ]);

            SessionManager::flash('success', 'Nota bloccata con successo');
        } catch (Exception $e) {
            Logger::getInstance()->error("Errore nel bloccare la nota", [
                "note_id" => $id,
                "error" => $e->getMessage()
            ]);
            SessionManager::flash('error', 'Errore durante il blocco della nota');
        }

        header('Location: /note/' . $id);
        exit;
    }
    
}