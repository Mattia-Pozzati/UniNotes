<?php
namespace App\Controller;

use App\View\View;
use App\Model\User;
use Core\Helper\Logger;

class AuthController
{
    /**
     * Mostra la pagina di login
     */
    public function showLogin()
    {
        View::render('login', 'page', ["title" => "Login"]);
    }

    /**
     * Mostra la pagina di registrazione
     */
    public function showRegister()
    {
        View::render('register', 'page', ["title" => "Registrazione"]);
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

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $isAdmin = isset($_POST['admin']);

        Logger::getInstance()->info("Tentativo di login", [
            "email" => $email,
            "admin" => $isAdmin ? 'yes' : 'no'
        ]);

        // TODO: Implementare logica di login
        // Esempio di query:
        // $user = (new User())->where('email', '=', $email)->first();
        // if ($user && password_verify($password, $user->password_hash())) {
        //     session_start();
        //     $_SESSION['user_id'] = $user->id();
        //     $_SESSION['user_role'] = $user->role();
        //     header('Location: /dashboard');
        //     exit;
        // } else {
        //     View::render('login', 'page', [
        //         "title" => "Login",
        //         "error" => "Credenziali non valide"
        //     ]);
        //     return;
        // }

        // Placeholder per ora
        echo "Login in corso...";
    }

    /**
     * Gestisce la registrazione dell'utente
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        Logger::getInstance()->info("Tentativo di registrazione", [
            "email" => $email,
            "name" => $name
        ]);

        // Validazione base
        if ($password !== $passwordConfirm) {
            View::render('register', 'page', [
                "title" => "Registrazione",
                "error" => "Le password non coincidono"
            ]);
            return;
        }

        // TODO: Implementare inserimento database
        // Esempio:
        // $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        // $userId = (new User())->insert([
        //     'name' => $name,
        //     'email' => $email,
        //     'password_hash' => $passwordHash,
        //     'role' => 'student'
        // ]);
        // 
        // if ($userId) {
        //     header('Location: /login?registered=1');
        //     exit;
        // } else {
        //     View::render('register', 'page', [
        //         "title" => "Registrazione",
        //         "error" => "Errore durante la registrazione"
        //     ]);
        // }

        echo "Registrazione in corso...";
    }

    /**
     * Gestisce il logout dell'utente
     */
    public function logout()
    {
        session_start();
        session_destroy();
        
        Logger::getInstance()->info("Logout effettuato");
        
        header('Location: /login');
        exit;
    }
}

?>
