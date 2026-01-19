<?php
namespace App\Controller;

use App\View\View;
use App\Model\User;
use Core\Helper\Logger;
use Core\Helper\SessionManager;

class AuthController
{
    /**
     * Mostra la pagina di login
     */
    public function showLogin()
    {
        $error = SessionManager::flash('error');
        $success = SessionManager::flash('success');
        
        View::render('login', 'page', [
            "title" => "Login",
            "error" => $error,
            "success" => $success
        ]);
    }

    /**
     * Mostra la pagina di registrazione
     */
    public function showRegister()
    {
        $error = SessionManager::flash('error');
        
        View::render('register', 'page', [
            "title" => "Registrazione",
            "error" => $error
        ]);
    }

    /**
     * Gestisce il login dell'utente
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        Logger::getInstance()->info("Tentativo di login", [
            "email" => $email
        ]);

        // Validazione base
        if (empty($email) || empty($password)) {
            Logger::getInstance()->warning("Login fallito - campi vuoti");
            SessionManager::flash('error', 'Email e password sono obbligatori');
            header('Location: /login');
            exit;
        }

        try {
            // Trova utente per email
            $user = (new User())->findByEmail($email);
            
            // DEBUG
            Logger::getInstance()->info("Debug login", [
                "user_found" => $user !== null,
                "user_id" => $user['id'] ?? null,
                "user_email" => $user['email'] ?? null
            ]);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Login riuscito
                SessionManager::login($user);
                
                Logger::getInstance()->info("Login riuscito", [
                    "user_id" => $user['id'],
                    "email" => $email,
                    "role" => $user['role']
                ]);
                
                // Redirect alla dashboard appropriata
                if ($user['role'] === 'admin') {
                    header('Location: /admin');
                } else {
                    header('Location: /');
                }
                exit;
            } else {
                // Login fallito
                Logger::getInstance()->warning("Login fallito - credenziali non valide", [
                    "email" => $email,
                    "user_exists" => $user !== null,
                    "password_verified" => $user ? password_verify($password, $user['password_hash']) : false
                ]);
                
                SessionManager::flash('error', 'Email o password non validi');
                header('Location: /login');
                exit;
            }
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore durante login", [
                "email" => $email,
                "error" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine()
            ]);
            
            SessionManager::flash('error', 'Errore durante il login. Riprova più tardi.');
            header('Location: /login');
            exit;
        }
    }

    /**
     * Gestisce la registrazione dell'utente
     */
    public function register()
    {
        Logger::getInstance()->info("=== INIZIO register() ===", [
            "method" => $_SERVER['REQUEST_METHOD']
        ]);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Logger::getInstance()->info("Metodo non POST, redirect");
            header('Location: /register');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['confirmPassword'] ?? '';

        Logger::getInstance()->info("Dati registrazione ricevuti", [
            "name" => $name,
            "email" => $email,
            "password_length" => strlen($password),
            "password_confirm_length" => strlen($passwordConfirm)
        ]);

        // Validazione
        $errors = [];

        if (empty($name)) {
            $errors[] = "Il nome è obbligatorio";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email non valida";
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = "La password deve essere di almeno 6 caratteri";
        }

        if ($password !== $passwordConfirm) {
            $errors[] = "Le password non coincidono";
        }

        if (!empty($errors)) {
            Logger::getInstance()->warning("Registrazione fallita - validazione", [
                "errors" => $errors
            ]);
            SessionManager::flash('error', implode('. ', $errors));
            header('Location: /register');
            exit;
        }

        Logger::getInstance()->info("Validazione OK, procedo con insert");

        try {
            // Verifica se l'email esiste già
            Logger::getInstance()->info("Controllo email esistente");
            $existingUser = (new User())->findByEmail($email);
            
            if ($existingUser) {
                Logger::getInstance()->warning("Registrazione fallita - email esistente", [
                    "email" => $email,
                    "existing_user_id" => $existingUser['id']
                ]);
                SessionManager::flash('error', 'Email già registrata');
                header('Location: /register');
                exit;
            }

            Logger::getInstance()->info("Email disponibile, genero hash");
            
            // Crea nuovo utente
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
            Logger::getInstance()->info("Hash generato", [
                "hash_length" => strlen($passwordHash),
                "hash_preview" => substr($passwordHash, 0, 20)
            ]);
            
            $userData = [
                'name' => $name,
                'email' => $email,
                'password_hash' => $passwordHash,
                'role' => 'student',
                'reputation' => 0
            ];
            
            Logger::getInstance()->info("Tentativo insert utente", [
                "data_keys" => array_keys($userData)
            ]);
            
            $userId = (new User())->insert($userData);

            Logger::getInstance()->info("Insert completato", [
                "user_id" => $userId,
                "result_type" => gettype($userId)
            ]);

            if ($userId) {
                Logger::getInstance()->info("Registrazione completata con successo", [
                    "user_id" => $userId,
                    "email" => $email
                ]);

                SessionManager::flash('success', 'Registrazione completata! Effettua il login.');
                header('Location: /login');
                exit;
            } else {
                Logger::getInstance()->error("Insert ha restituito false/0");
                throw new \Exception("Impossibile creare l'utente - insert ha restituito false");
            }

        } catch (\Exception $e) {
            Logger::getInstance()->error("ECCEZIONE durante registrazione", [
                "email" => $email,
                "error_message" => $e->getMessage(),
                "error_file" => $e->getFile(),
                "error_line" => $e->getLine(),
                "error_trace" => $e->getTraceAsString()
            ]);

            SessionManager::flash('error', 'Errore durante la registrazione: ' . $e->getMessage());
            header('Location: /register');
            exit;
        }
    }

    /**
     * Gestisce il logout dell'utente
     */
    public function logout()
    {
        SessionManager::logout();
        
        Logger::getInstance()->info("Logout effettuato");
        
        SessionManager::flash('success', 'Logout effettuato con successo');
        header('Location: /login');
        exit;
    }
}

?>