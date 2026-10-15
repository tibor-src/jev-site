<?php

namespace App\Jev;

use InvalidArgumentException;
use Laravel\Ai\Classification\Boolean;
use Laravel\Ai\Classification\Choice;
use Laravel\Ai\Classification\Score;
use Laravel\Ai\Contracts\Question;

class JevExamples
{
    /**
     * @var list<string>
     */
    public const array Types = ['boolean', 'choice', 'score', 'image'];

    public const string ImageModel = 'typesafe/jev-1.14';

    public const string ImageHeader = 'TypeSafe-Danger-Zone-Images';

    /**
     * Recorded answers from a local run, plus alternate texts so the dropdown can change the result.
     *
     * @return array<string, array{title: string, summary: string, image?: string, alt?: string, scenarios: list<array{text: string, response: array<string, mixed>}>}>
     */
    public static function demo(): array
    {
        $levels = [
            'Calm, stating facts',
            'Annoyed but polite',
            'Angry or threatening to leave',
        ];

        return [
            'boolean' => [
                'title' => 'Boolean',
                'summary' => 'Yes or no, as a probability.',
                'scenarios' => [
                    [
                        'text' => 'Is the deploy finished?',
                        'response' => ['probability' => 0.98],
                    ],
                    [
                        'text' => 'The deploy finished this morning.',
                        'response' => ['probability' => 0.04],
                    ],
                    [
                        'text' => 'Can you check whether the deploy finished',
                        'response' => ['probability' => 0.85],
                    ],
                ],
            ],
            'choice' => [
                'title' => 'Choice',
                'summary' => 'One option, with a probability for each.',
                'scenarios' => [
                    [
                        'text' => 'The last invoice payment failed and I need a refund.',
                        'response' => [
                            'choice' => 'billing',
                            'probabilities' => ['billing' => 1, 'technical' => 0, 'sales' => 0],
                            'confidence' => 1,
                        ],
                    ],
                    [
                        'text' => 'The API returns 500 right after login.',
                        'response' => [
                            'choice' => 'technical',
                            'probabilities' => ['billing' => 0.05, 'technical' => 0.9, 'sales' => 0.05],
                            'confidence' => 0.86,
                        ],
                    ],
                    [
                        'text' => 'What does the pro plan cost if we add three seats?',
                        'response' => [
                            'choice' => 'sales',
                            'probabilities' => ['billing' => 0.08, 'technical' => 0.07, 'sales' => 0.85],
                            'confidence' => 0.78,
                        ],
                    ],
                ],
            ],
            'score' => [
                'title' => 'Score',
                'summary' => 'A point on a scale, including between levels.',
                'scenarios' => [
                    [
                        'text' => 'This is the third outage this week and nobody has replied. Fix it today or I am cancelling.',
                        'response' => [
                            'score' => 2,
                            'probabilities' => [0, 0, 1],
                            'legend' => $levels,
                            'confidence' => 1,
                        ],
                    ],
                    [
                        'text' => 'The export was a day late. No rush, just flagging it.',
                        'response' => [
                            'score' => 0.1,
                            'probabilities' => [0.9, 0.1, 0],
                            'legend' => $levels,
                            'confidence' => 0.82,
                        ],
                    ],
                    [
                        'text' => 'This is annoying, but I can wait until tomorrow.',
                        'response' => [
                            'score' => 1.1,
                            'probabilities' => [0.15, 0.7, 0.15],
                            'legend' => $levels,
                            'confidence' => 0.55,
                        ],
                    ],
                ],
            ],
            'image' => [
                'title' => 'Image',
                'summary' => 'A picture in the state, behind the images header.',
                'image' => '/demo/circle.jpg',
                'alt' => 'A red circle centered on a white square.',
                'scenarios' => [
                    [
                        'text' => 'A red circle centered on a white square.',
                        'response' => ['probability' => 0.96],
                    ],
                ],
            ],
        ];
    }

    public static function question(string $type): Question
    {
        return match ($type) {
            'boolean' => new Boolean('Is this a question?', [
                'true' => 'The text asks for information or a decision.',
                'false' => 'The text states a fact, gives an instruction, or is not asking anything.',
            ]),
            'choice' => new Choice('Which team should handle this?', [
                'billing' => 'Invoices, payments, and refunds',
                'technical' => 'Bugs, outages, and integrations',
                'sales' => 'Pricing, plans, and upgrades',
            ]),
            'score' => new Score('How frustrated is the customer?', [
                'Calm, stating facts',
                'Annoyed but polite',
                'Angry or threatening to leave',
            ]),
            'image' => new Boolean('Is there a red circle in the photo?', [
                'true' => 'A red circle is visible.',
                'false' => 'There is no red circle.',
            ]),
            default => throw new InvalidArgumentException("Unknown Jev example [{$type}]."),
        };
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function presentation(): array
    {
        $presentation = [];

        foreach (self::demo() as $type => $example) {
            $presentation[$type] = [
                'title' => $example['title'],
                'summary' => $example['summary'],
                'question' => self::question($type)->toArray(),
                'scenarios' => $example['scenarios'],
                'image' => $example['image'] ?? null,
                'alt' => $example['alt'] ?? null,
            ];
        }

        return $presentation;
    }
}
