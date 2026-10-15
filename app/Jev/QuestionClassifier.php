<?php

namespace App\Jev;

use InvalidArgumentException;
use Laravel\Ai\Classification;
use Laravel\Ai\Classification\Boolean;
use Laravel\Ai\Responses\Data\BooleanAnswer;

class QuestionClassifier
{
    public const string MissingKeyMessage = 'OPENROUTER_API_KEY is not set. Add it to your .env file before calling Jev.';

    public const string UnexpectedAnswerMessage = 'Jev did not return a yes/no probability.';

    public function keyIsConfigured(): bool
    {
        $key = config('ai.providers.openrouter.key');

        return is_string($key) && ! blank($key);
    }

    public function classify(string $text): ClassificationResult
    {
        if (! $this->keyIsConfigured()) {
            return new ClassificationResult(null, self::MissingKeyMessage);
        }

        $response = Classification::of($text)
            ->question('is_question', new Boolean('Is this a question?'))
            ->classify('openrouter');

        $answer = $response->answer('is_question');

        if (! $answer instanceof BooleanAnswer) {
            return new ClassificationResult(null, self::UnexpectedAnswerMessage);
        }

        return new ClassificationResult($answer->probability, null);
    }

    /**
     * @return ExampleRun The data is the answer for this question.
     */
    public function classifyExample(string $type, string $text): ExampleRun
    {
        if (! $this->keyIsConfigured()) {
            return new ExampleRun(null, self::MissingKeyMessage);
        }

        $response = Classification::of($text)
            ->question($type, JevExamples::question($type))
            ->classify('openrouter');

        try {
            return new ExampleRun($response->answer($type)->toArray(), null);
        } catch (InvalidArgumentException) {
            return new ExampleRun(null, 'Jev did not return an answer for '.$type.'.');
        }
    }

    /**
     * @return ExampleRun The data is the yes/no probability for the stored picture.
     */
    public function classifyImage(): ExampleRun
    {
        if (! $this->keyIsConfigured()) {
            return new ExampleRun(null, self::MissingKeyMessage);
        }

        $encoded = base64_encode((string) file_get_contents(public_path('demo/circle.jpg')));

        $response = Classification::of([
            'Photo:',
            [
                'type' => 'image_url',
                'image_url' => ['url' => 'data:image/jpeg;base64,'.$encoded],
            ],
        ])
            ->question('image', JevExamples::question('image'))
            ->withHeaders([JevExamples::ImageHeader => 'true'])
            ->classify('openrouter', JevExamples::ImageModel);

        $answer = $response->answer('image');

        if (! $answer instanceof BooleanAnswer) {
            return new ExampleRun(null, self::UnexpectedAnswerMessage);
        }

        return new ExampleRun($answer->toArray(), null);
    }
}
