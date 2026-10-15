<?php

namespace App\Http\Controllers;

use App\Jev\JevExamples;
use App\Jev\QuestionClassifier;
use App\Jev\RecordedAnswer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AskJevController extends Controller
{
    public function create(QuestionClassifier $classifier): View
    {
        return view('jev.ask', [
            'live' => $classifier->keyIsConfigured(),
            'questions' => JevExamples::presentation(),
        ]);
    }

    public function store(Request $request, string $type, QuestionClassifier $classifier): JsonResponse
    {
        abort_unless(in_array($type, JevExamples::Types, true), 404);

        $scenarios = JevExamples::demo()[$type]['scenarios'];

        $validated = $request->validate([
            'scenario' => ['required', 'integer', 'min:0', 'max:'.(count($scenarios) - 1)],
        ]);

        $scenario = $scenarios[$validated['scenario']];

        if (! $classifier->keyIsConfigured()) {
            return response()->json([
                'demo' => true,
                'data' => RecordedAnswer::vary($type, $scenario['response']),
            ]);
        }

        $result = $type === 'image'
            ? $classifier->classifyImage()
            : $classifier->classifyExample($type, $scenario['text']);

        if ($result->error !== null) {
            return response()->json(['message' => $result->error], 422);
        }

        return response()->json([
            'demo' => false,
            'data' => $result->data,
        ]);
    }
}
