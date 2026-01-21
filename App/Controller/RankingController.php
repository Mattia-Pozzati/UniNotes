<?php
namespace App\Controller;

use App\View\View;
use Core\Helper\Logger;
use Core\Database\Database;

class RankingController
{
    public function show(): void
    {
        Logger::getInstance()->info("Visualizzazione ranking");
        
        // Query per utenti con più note caricate
        $topUploaders = $this->getTopUploaders(10);
        
        // Query per utenti con più like ricevuti
        $topLiked = $this->getTopLiked(10);
        
        View::render('ranking', 'page', [
            'title' => 'Classifica',
            'topUploaders' => $topUploaders,
            'topLiked' => $topLiked
        ]);
    }
    
    private function getTopUploaders(int $limit): array
    {
        try {
            $db = Database::getInstance();
            
            $sql = "
                SELECT 
                    u.id,
                    u.name,
                    u.email,
                    u.university,
                    COUNT(n.id) AS note_count
                FROM USER u
                LEFT JOIN NOTE n ON n.student_id = u.id AND n.deleted_at IS NULL
                GROUP BY u.id, u.name, u.email, u.university
                ORDER BY note_count DESC
                LIMIT :limit
            ";
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore getTopUploaders", [
                "error" => $e->getMessage()
            ]);
            return [];
        }
    }
    
    private function getTopLiked(int $limit): array
    {
        try {
            $db = Database::getInstance();
            
            // Stessa logica di calculateUserReputation ma per tutti gli utenti
            $sql = "
                SELECT 
                    u.id,
                    u.name,
                    u.email,
                    u.university,
                    COUNT(l.student_id) AS like_count
                FROM USER u
                LEFT JOIN NOTE n ON n.student_id = u.id AND n.deleted_at IS NULL
                LEFT JOIN `LIKE` l ON l.note_id = n.id
                GROUP BY u.id, u.name, u.email, u.university
                ORDER BY like_count DESC
                LIMIT :limit
            ";
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        } catch (\Exception $e) {
            Logger::getInstance()->error("Errore getTopLiked", [
                "error" => $e->getMessage()
            ]);
            return [];
        }
    }
}