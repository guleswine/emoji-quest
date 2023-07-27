<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Repositories\MapRepository;
use App\Services\CellService;
use App\Services\NoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function createOnCell($cell_id, Request $request)
    {
        $user = Auth::user();
        $hero = Hero::where('user_id', $user->id)->first();
        $content = $request->input('content');
        NoteService::createOnCell($content, $cell_id, $hero->id);
    }

    public function getNote($id)
    {
        $note = NoteService::getNote($id);

        return response()->json($note);
    }

    public function deleteNoteFromCell($cell_id, $note_id)
    {
        NoteService::deleteNote($note_id);
        CellService::removeObject($cell_id, 'Note', $note_id);
        $cell = MapRepository::getFormatedCell($cell_id);

        return response()->json($cell);

    }
}
