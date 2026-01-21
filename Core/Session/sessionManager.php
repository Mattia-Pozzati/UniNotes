<?php
// Core/Session/SessionManager.php

namespace Core\Session;

class SessionManager
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Controlla se l'utente è loggato
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }

    /**
     * Ottiene il nome dell'utente loggato
     */
    public function getUserName(): ?string
    {
        return $_SESSION['user_name'] ?? null;
    }

    /**
     * Ottiene l'ID dell'utente loggato
     */
    public function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Ottiene l'email dell'utente loggato
     */
    public function getUserEmail(): ?string
    {
        return $_SESSION['user_email'] ?? null;
    }

    /**
     * Imposta l'utente loggato (da usare quando fai il login)
     */
    public function login(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['is_logged_in'] = true;
        
        // Rigenera l'ID di sessione per sicurezza
        session_regenerate_id(true);
    }

    /**
     * Logout dell'utente
     */
    public function logout(): void
    {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }

    /**
     * Ottiene tutti i dati dell'utente
     */
    public function getUser(): ?array
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return [
            'id' => $this->getUserId(),
            'name' => $this->getUserName(),
            'email' => $this->getUserEmail()
        ];
    }
}