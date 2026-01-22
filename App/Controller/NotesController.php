<?php
namespace App\Controller;
use App\Model\Note;
use Core\Helper\Logger;

class NotesController
{
    public static function getMyNotes(int $studentId, array $opts = []): array
    {
        $order = $opts['order'] ?? ['field' => 'created_at', 'direction' => 'DESC'];
        $perPage = $opts['per_page'] ?? 12;
        $page = $opts['page'] ?? 1;

        return (new Note())
            ->select([
                'NOTE.*',
                'USER.name AS student_name',
                'COURSE.name AS course_name',
                // conteggio totale dei like per la nota
                '(SELECT COUNT(DISTINCT `LIKE`.student_id) FROM `LIKE` WHERE `LIKE`.note_id = NOTE.id) AS likes',
                // conteggio totale dei download per la nota
                '(SELECT COUNT(DISTINCT NOTE_DOWNLOAD.student_id) FROM NOTE_DOWNLOAD WHERE NOTE_DOWNLOAD.note_id = NOTE.id) AS downloads'
            ])
            ->join('USER', 'NOTE.student_id', '=', 'USER.id')
                ->join('NOTE_COURSE', 'NOTE.id', '=', 'NOTE_COURSE.note_id')
                ->leftJoin('COURSE', 'NOTE_COURSE.course_id', '=', 'COURSE.id')
            ->where('NOTE.student_id', '=', $studentId)
            ->where('NOTE.deleted_at', 'IS', null)
            ->order_by($order['field'], $order['direction'])
            ->paginate($perPage, $page);
    }

    public static function getDownloadedNotes(int $studentId, array $opts = []): array
    {
        $order = $opts['order'] ?? ['field' => 'created_at', 'direction' => 'DESC'];
        $perPage = $opts['per_page'] ?? 12;
        $page = $opts['page'] ?? 1;

        return (new Note())
            ->select([
                'NOTE.*',
                'USER.name AS student_name',
                'COURSE.name AS course_name',
                // conteggio totale dei like per la nota
                '(SELECT COUNT(DISTINCT `LIKE`.student_id) FROM `LIKE` WHERE `LIKE`.note_id = NOTE.id) AS likes',
                // conteggio totale dei download per la nota
                '(SELECT COUNT(DISTINCT NOTE_DOWNLOAD.student_id) FROM NOTE_DOWNLOAD WHERE NOTE_DOWNLOAD.note_id = NOTE.id) AS downloads'
            ])
            ->join('USER', 'NOTE.student_id', '=', 'USER.id')
            // join per filtrare le note che sono state scaricate dallo studente
            ->join('NOTE_DOWNLOAD', 'NOTE.id', '=', 'NOTE_DOWNLOAD.note_id')
            ->leftJoin('NOTE_COURSE', 'NOTE.id', '=', 'NOTE_COURSE.note_id')
            ->leftJoin('COURSE', 'NOTE_COURSE.course_id', '=', 'COURSE.id')
            ->where('NOTE_DOWNLOAD.student_id', '=', $studentId)
            ->where('NOTE.deleted_at', 'IS', null)
            ->order_by($order['field'], $order['direction'])
            ->paginate($perPage, $page);
    }


    public static function searchNotes(array $filters = [], array $paginate = []): array
    {
        $qText = trim((string) ($filters['text'] ?? ''));
        $format = trim((string) ($filters['format'] ?? ''));
        $course = trim((string) ($filters['course'] ?? ''));
        $noteType = trim((string) ($filters['note_type'] ?? ''));
        $perPage = $paginate['per_page'] ?? 12;
        $page = $paginate['page'] ?? 1;

        $qb = (new Note())
            ->select([
                'NOTE.*',
                'USER.name AS student_name',
                'COURSE.name AS course_name',
                'COUNT(DISTINCT `LIKE`.student_id) AS likes',
                'COUNT(DISTINCT NOTE_DOWNLOAD.student_id) AS downloads'
            ])
            ->leftJoin('USER', 'NOTE.student_id', '=', 'USER.id')
            ->leftJoin('NOTE_COURSE', 'NOTE.id', '=', 'NOTE_COURSE.note_id')
            ->leftJoin('COURSE', 'NOTE_COURSE.course_id', '=', 'COURSE.id')
            ->leftJoin('`LIKE`', 'NOTE.id', '=', '`LIKE`.note_id')
            ->leftJoin('NOTE_DOWNLOAD', 'NOTE.id', '=', 'NOTE_DOWNLOAD.note_id')
            ->where('NOTE.deleted_at', 'IS', null)
            ->where('NOTE.visibility', '=', 'public')
            ->group_by('NOTE.id');

        if ($qText !== '') {
            // Basic text search on title (keeps connectors simple)
            $qb->where('NOTE.title', 'LIKE', '%' . $qText . '%');
        }
        
        if ($noteType !== '') {
            $qb->where('NOTE.note_type', '=', $noteType);
        }
        
        if ($format !== '') {
            $qb->where('NOTE.format', '=', $format);
        }


        if ($course !== '')
            $qb->where('COURSE.name', '=', $course, false);

        return $qb->paginate($perPage, $page);
    }

}
?>