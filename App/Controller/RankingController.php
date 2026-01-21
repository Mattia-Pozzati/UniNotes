<?php
namespace App\Controller;

use App\View\View;
use App\Model\User;
use Core\Helper\Logger;

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
        return (new User())
            ->select([
                'USER.*',
                'COUNT(NOTE.id) AS note_count'
            ])
            ->leftJoin('NOTE', 'NOTE.student_id', '=', 'USER.id')
            ->where('NOTE.deleted_at', 'IS', null)
            ->group_by('USER.id')
            ->order_by('note_count', 'DESC')
            ->limit($limit)
            ->get();
    }
    
    private function getTopLiked(int $limit): array
    {
        return (new User())
            ->select([
                'USER.*',
                'COUNT(DISTINCT `LIKE`.note_id) AS like_count'
            ])
            ->leftJoin('NOTE', 'NOTE.student_id', '=', 'USER.id')
            ->leftJoin('`LIKE`', '`LIKE`.note_id', '=', 'NOTE.id')
            ->where('NOTE.deleted_at', 'IS', null)
            ->group_by('USER.id')
            ->order_by('like_count', 'DESC')
            ->limit($limit)
            ->get();
    }
}