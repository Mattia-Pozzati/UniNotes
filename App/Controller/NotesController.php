<?php
namespace App\Controller;
use App\Model\Note;

class NotesController
{
    /**
     * 
     * @return array
     */
    /**
     * Lista paginata di note
     *
     * Opzioni supportate in $opts:
     *  - page (int) default 1
     *  - per_page (int) default 12
     *  - visibility (string|null) es. 'public' (null = tutti)
     *  - order (array) ['field'=>'created_at','direction'=>'DESC']
     * @param array $opts
     * @return array ['data'=>array, 'meta'=>array]
     */
    public static function getAllNotes(array $opts = []): array
    {
        // Ordine
        $order = $opts['order'] ?? ['field' => 'created_at', 'direction' => 'DESC'];
        $visibility = array_key_exists('visibility', $opts) ? $opts['visibility'] : 'public';
        $perPage = $opts['per_page'] ?? 12;
        $page = $opts['page'] ?? 1;

        return (new Note())
            ->select(['note.*', 'user.name AS student_name'])
            ->join('user', 'note.student_id', '=', 'user.id')
            ->where('note.deleted_at', 'IS', null)
            ->where('note.visibility', '=', $visibility)
            ->order_by($order['field'], $order['direction'])
            ->paginate($perPage, $page);
    }

    /**
     * Note di un utente (paginato).
     *
     * @param int $studentId
     * @param array $opts page/per_page/order
     * @return array ['data'=>array, 'meta'=>array]
     */
    public static function getMyNotes(int $studentId, array $opts = []): array
    {
        // Ordine
        $order = $opts['order'] ?? ['field' => 'created_at', 'direction' => 'DESC'];
        $perPage = $opts['per_page'] ?? 12;
        $page = $opts['page'] ?? 1;

        return (new Note())
            ->select(['note.*', 'user.name AS student_name'])
            ->join('user', 'note.student_id', '=', 'user.id')
            ->where('note.student_id', '=', $studentId)
            ->where('note.deleted_at', 'IS', null)
            ->order_by($order['field'], $order['direction'])
            ->paginate($perPage, $page);
    }

    /**
     * Recupera singola nota (o null).
     *
     * @param int $id
     * @return Note|null
     */
    public static function getNote(int $id): array|null
    {
        $res = (new Note())
            ->select(['note.*', 'user.name AS student_name'])
            ->join('user', 'note.student_id', '=', 'user.id')
            ->where('note.id', '=', $id)
            ->where('note.deleted_at', 'IS', null)
            ->first();

        return $res ?: null;
    }

    /**
     * Ricerca note con filtri.
     *
     * Filtro in $filters:
     *  - text, university, format
     * 
     * Paginazione in $paginate:
     *  - page, per_page, order
     * 
     *
     * @param array $filters
     * @return array ['data'=>array, 'meta'=>array]
     */
    public static function searchNotes(array $filters = [], array $paginate = []): array
    {
        // Filtri
        $qText = trim((string) ($filters['text'] ?? ''));
        $university = trim((string) ($filters['university'] ?? ''));
        $format = trim((string) ($filters['format'] ?? ''));
        $perPage = $paginate['per_page'] ?? 12;
        $page = $paginate['page'] ?? 1;

        // Query base
        $qb = (new Note())
            ->select([
                'note.*',
                'user.name AS student_name',
                'course.name AS course_name',
                'COUNT(DISTINCT `like`.student_id) AS likes',
                'COUNT(DISTINCT note_download.student_id) AS downloads'
            ])
            ->leftJoin('user', 'note.student_id', '=', 'user.id')
            ->leftJoin('note_course', 'note.id', '=', 'note_course.note_id')
            ->leftJoin('course', 'note_course.course_id', '=', 'course.id')
            ->leftJoin('`like`', 'note.id', '=', 'like.note_id')
            ->leftJoin('note_download', 'note.id', '=', 'note_download.note_id')
            ->where('note.deleted_at', 'IS', null)
            ->where('note.visibility', '=', 'public');

        // When using aggregate COUNT(...) we must GROUP BY note.id so each note is a row
        $qb->group_by('note.id');

        // Filtro sulla barra di ricerca sia per titolo della nota sia per corso
        if ($qText !== '') {
            $qb->where('note.title', 'LIKE', '%' . $qText . '%', false);
            $qb->where('course.name', 'LIKE', '%' . $qText . '%', false);
        }

        // Altri filtri
        if ($university !== '')
            $qb->where('note.university', 'LIKE', '%' . $university . '%', false);
        if ($format !== '')
            $qb->where('note.format', '=', $format, false);

        return $qb->paginate($perPage, $page);
    }

    /**
     * Inserisce una nota
     * 
     * @param array $data
     * @return bool|string false se fallisce, altrimenti l'ID della nota inserita
     */
    public static function insertNote(array $data): string|bool
    {
        return (new Note())->insert($data);
    }

    /**
     * Aggiorna una nota
     * 
     * @param array $data
     * @return bool false se fallisce, true se riesce
     */
    public static function updateNote(array $data): bool
    {
        $id = $data['id'] ?? null;

        if ($id === null) {
            return false;
        }

        unset($data['id']);

        return (new Note())
            ->where('id', '=', $id)
            ->update($data);
    }

    /**
     * Cancella la nota
     * 
     * @param int $id
     * @return bool true se riesce, false se fallisce
     */
    public static function deleteNote(int $id): bool
    {
        return (new Note())
            ->where('id', '=', $id)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
?>