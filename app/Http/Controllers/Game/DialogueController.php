<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Hero;
use App\Models\Question;
use App\Services\ConditionService;
use App\Services\EventActionService;
use Illuminate\Support\Facades\Auth;

class DialogueController extends Controller
{
    public function getQuestion($question_id)
    {
        $question = Question::find($question_id);
        $answers = Answer::where('question_id', $question_id)->get();
        $hero = Hero::where('user_id', Auth::user()->id)->first();
        $filtered_answers = collect();
        $CS = new ConditionService($hero);
        foreach ($answers as $answer) {
            if ($answer->condition_group_id) {
                $res = $CS->execute($answer->condition_group_id);
                if (!$res) {
                    continue;
                }
            }
            $filtered_answers->push($answer);
        }

        return response()->json([
            'question'=>$question,
            'answers'=>$filtered_answers,
        ]);
    }

    public function selectAnswer($answer_id)
    {
        $answer = Answer::find($answer_id);

        if ($answer->event_id) {
            $hero = Hero::where('user_id', Auth::user()->id)->first();
            $EAS = new EventActionService($hero);
            $EAS->execute($answer->event_id);
        }
        if ($answer->next_question_id) {
            return $this->getQuestion($answer->next_question_id);
        }

        return response('');
    }
}
