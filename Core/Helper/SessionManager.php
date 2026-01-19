<?php
namespace Core\Helper;

/**
 * Gestione centralizzata delle sessioni
 */
class SessionManager
{
    private static bool $started = false;

    /**
     * Inizializza la sessione se non già fatto
     */
    public static function start(): void
    {
        if (self::$started) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            // Configurazione sicura della sessione
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');
            ini_set('session.use_strict_mode', '1');
            
            session_start();
            self::$started = true;
            
            // Rigenera ID sessione periodicamente (ogni 30 minuti)
            if (!isset($_SESSION['CREATED'])) {
                $_SESSION['CREATED'] = time();
            } else if (time() - $_SESSION['CREATED'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['CREATED'] = time();
            }
        }
    }

    /**
     * Imposta un valore nella sessione
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Ottieni un valore dalla sessione
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verifica se una chiave esiste
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Rimuovi una chiave
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Flash message - mostra una volta sola
     */
    public static function flash(string $key, ?string $value = null): mixed
    {
        self::start();
        
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }
        
        $flashValue = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $flashValue;
    }

    /**
     * Login utente
     */
    public static function login(array $user): void
    {
        self::start();
        
        // Rigenera ID per prevenire session fixation
        session_regenerate_id(true);
        
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'university' => $user['university'] ?? null,
            'reputation' => $user['reputation'] ?? 0
        ];
        
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        Logger::getInstance()->info("Utente loggato", [
            "user_id" => $user['id'],
            "email" => $user['email']
        ]);
    }

    /**
     * Logout utente
     */
    public static function logout(): void
    {
        self::start();
        
        $userId = $_SESSION['user']['id'] ?? null;
        
        // Distruggi sessione
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        self::$started = false;
        
        Logger::getInstance()->info("Utente disconnesso", ["user_id" => $userId]);
    }

    /**
     * Verifica se l'utente è loggato
     */
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Ottieni utente corrente
     */
    public static function user(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Verifica se l'utente è admin
     */
    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && ($user['role'] ?? '') === 'admin';
    }

    /**
     * Ottieni ID utente corrente
     */
    public static function userId(): ?int
    {
        $user = self::user();
        return $user['id'] ?? null;
    }

    /**
     * Distruggi completamente la sessione
     */
    public static function destroy(): void
    {
        self::logout();
    }

    /**
     * Ottieni tutti i dati della sessione (per debug)
     */
    public static function all(): array
    {
        self::start();
        return $_SESSION;
    }
}