<?php

namespace App\Services;

use App\Models\Note;

class NoteService
{
    public static function createOnCell($content, $cell_id, $hero_id)
    {
        $lines = explode(PHP_EOL, $content);
        $title = mb_substr($lines[0], 0, 50);
        $note = self::create($title, $content, $hero_id);
        CellService::addObject($cell_id, $title, 'page_with_curl', 'Note', $note->id, 8, false, $hero_id);
    }

    public static function create($title, $message, $hero_id)
    {
        $note = new Note();
        $note->title = $title;
        $note->message = $message;
        $note->creator_hero_id = $hero_id;
        $note->save();

        return $note;
    }

    public static function getNote($id)
    {
        return Note::find($id);
    }

    public static function deleteNote($id)
    {
        $note = Note::find($id);
        $note->delete();
    }
}
