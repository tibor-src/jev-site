<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Laravel\Ai\Classification;
use Laravel\Ai\Responses\Data\BooleanAnswer;
use Tests\TestCase;

class AskJevTest extends TestCase
{
    public function test_demo_page_shows_questions_and_the_repo_link(): void
    {
        config()->set('ai.providers.openrouter.key', null);

        $this->get(route('jev.create'))
            ->assertSee('text-[#f53003] dark:text-[#FF4433]">Jev - A Decision Model', false)
            ->assertSee('<title>Jev - A Decision Model</title>', false)
            ->assertSeeInOrder([
                'Jev - A Decision Model',
                'Demo',
                'FAQs',
                'What is Jev?',
                'Resources',
                'Lore',
                'Jev is a decision model from TypeSafe.',
                'Classify a message',
            ])
            ->assertSee('aria-current="page"', false)
            ->assertSee('href="'.route('jev.faqs').'"', false)
            ->assertSee('Question')
            ->assertSee('>Input:</span>', false)
            ->assertSee('<code data-input', false)
            ->assertSeeInOrder([
                'Input:',
                'Is the deploy finished?',
                'Yes: The text asks for information or a decision.',
                'No: The text states a fact, gives an instruction, or is not asking anything.',
            ])
            ->assertSee('Answer')
            ->assertSee('Run to see the answer.')
            ->assertSee('bg-[#f53003]', false)
            ->assertSee('<li>Yes: The text asks for information or a decision.</li>', false)
            ->assertSee('<li>No: The text states a fact, gives an instruction, or is not asking anything.</li>', false)
            ->assertSee('<li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">billing</span> Invoices, payments, and refunds</li>', false)
            ->assertSee('<li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">technical</span> Bugs, outages, and integrations</li>', false)
            ->assertSee('<li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">sales</span> Pricing, plans, and upgrades</li>', false)
            ->assertSee('<li>Calm, stating facts</li>', false)
            ->assertSee('<li>Annoyed but polite</li>', false)
            ->assertSee('<li>Angry or threatening to leave</li>', false)
            ->assertSee('src="/demo/circle.jpg"', false)
            ->assertSee('alt="A red circle centered on a white square."', false)
            ->assertSee('Is there a red circle in the photo?')
            ->assertSee('A picture in the state, behind the images header.')
            ->assertSee('Is the deploy finished?')
            ->assertSee('Can you check whether the deploy finished')
            ->assertDontSee('Can you check whether the deploy finished?', false)
            ->assertSee('The last invoice payment failed and I need a refund.')
            ->assertSee('I am cancelling.')
            ->assertSee('Request and response')
            ->assertSee('No API key: Run replays a recorded answer and lets it vary a little.')
            ->assertSee('Live: check out')
            ->assertSee('https://github.com/tibor-src/jev-site', false)
            ->assertSee('https://typesafe.ai/', false)
            ->assertSee('https://packagist.org/packages/laravel/ai', false)
            ->assertSee('Jev by TypeSafe')
            ->assertSee('Laravel AI SDK')
            ->assertDontSee('>laravel/ai</a>', false)
            ->assertSee('Lang:', false)
            ->assertSee('data-lang="js"', false)
            ->assertSee('data-lang="php"', false)
            ->assertSee('https://docs.typesafe.ai/sdk/javascript', false)
            ->assertSee('TypeSafe JavaScript SDK')
            ->assertSee('https://openrouter.ai/', false)
            ->assertSee('made by', false)
            ->assertSee('>tibor.io</a>', false)
            ->assertDontSee('typesafe-sdk-js', false)
            ->assertDontSee('Jev package')
            ->assertDontSee("classify('openrouter')", false);
    }

    public function test_missing_key_replays_a_yes_inside_the_recorded_range(): void
    {
        config()->set('ai.providers.openrouter.key', null);

        Classification::fake();
        Http::fake();

        $probability = $this->postJson(route('jev.store', 'boolean'), ['scenario' => 0])
            ->assertOk()
            ->assertJsonPath('demo', true)
            ->json('data.probability');

        $this->assertGreaterThanOrEqual(0.9, $probability);
        $this->assertLessThanOrEqual(0.99, $probability);

        Classification::assertNothingClassified();
        Http::assertNothingSent();
    }

    public function test_missing_key_keeps_a_question_without_a_mark_above_the_yes_line(): void
    {
        config()->set('ai.providers.openrouter.key', null);

        Classification::fake();

        $probability = $this->postJson(route('jev.store', 'boolean'), ['scenario' => 2])
            ->assertOk()
            ->assertJsonPath('demo', true)
            ->json('data.probability');

        $this->assertGreaterThan(0.8, $probability);
        $this->assertLessThan(0.9, $probability);
    }

    public function test_missing_key_keeps_a_statement_below_the_yes_line(): void
    {
        config()->set('ai.providers.openrouter.key', null);

        Classification::fake();

        $probability = $this->postJson(route('jev.store', 'boolean'), ['scenario' => 1])
            ->assertOk()
            ->json('data.probability');

        $this->assertGreaterThanOrEqual(0.01, $probability);
        $this->assertLessThanOrEqual(0.09, $probability);
    }

    public function test_missing_key_keeps_the_recorded_choice(): void
    {
        config()->set('ai.providers.openrouter.key', null);

        Classification::fake();

        $data = $this->postJson(route('jev.store', 'choice'), ['scenario' => 0])
            ->assertOk()
            ->assertJsonPath('demo', true)
            ->assertJsonPath('data.choice', 'billing')
            ->json('data');

        $this->assertGreaterThanOrEqual(0.82, $data['probabilities']['billing']);
        $this->assertLessThanOrEqual(0.97, $data['probabilities']['billing']);
        $this->assertSame(1.0, round(array_sum($data['probabilities']), 3));
        $this->assertGreaterThan($data['probabilities']['technical'], $data['probabilities']['billing']);
        $this->assertGreaterThan($data['probabilities']['sales'], $data['probabilities']['billing']);
    }

    public function test_missing_key_keeps_the_recorded_score_near_the_top_level(): void
    {
        config()->set('ai.providers.openrouter.key', null);

        Classification::fake();

        $data = $this->postJson(route('jev.store', 'score'), ['scenario' => 0])
            ->assertOk()
            ->json('data');

        $this->assertGreaterThanOrEqual(1.65, $data['score']);
        $this->assertLessThanOrEqual(2, $data['score']);
        $this->assertGreaterThanOrEqual(0.62, $data['probabilities'][2]);
        $this->assertGreaterThan($data['probabilities'][0], $data['probabilities'][2]);
        $this->assertGreaterThan($data['probabilities'][1], $data['probabilities'][2]);
    }

    public function test_live_run_returns_the_classifier_answer(): void
    {
        config()->set('ai.providers.openrouter.key', 'test-key');

        Classification::fake([
            ['boolean' => new BooleanAnswer(0.97)],
        ]);

        $this->postJson(route('jev.store', 'boolean'), ['scenario' => 0])
            ->assertOk()
            ->assertJsonPath('demo', false)
            ->assertJsonPath('data.probability', 0.97);

        Classification::assertClassified(
            fn ($prompt) => $prompt->asks('boolean') && $prompt->contains('Is the deploy finished?')
        );
    }

    public function test_missing_key_replays_the_picture_as_a_yes(): void
    {
        config()->set('ai.providers.openrouter.key', null);

        Classification::fake();

        $probability = $this->postJson(route('jev.store', 'image'), ['scenario' => 0])
            ->assertOk()
            ->assertJsonPath('demo', true)
            ->json('data.probability');

        $this->assertGreaterThanOrEqual(0.9, $probability);
        $this->assertLessThanOrEqual(0.99, $probability);
        Classification::assertNothingClassified();
    }

    public function test_live_image_run_sends_the_picture_and_the_images_header(): void
    {
        config()->set('ai.providers.openrouter.key', 'test-key');

        Classification::fake([
            ['image' => new BooleanAnswer(0.97)],
        ]);

        $this->postJson(route('jev.store', 'image'), ['scenario' => 0])
            ->assertOk()
            ->assertJsonPath('demo', false)
            ->assertJsonPath('data.probability', 0.97);

        Classification::assertClassified(function ($prompt): bool {
            $image = $prompt->state[1]['image_url']['url'] ?? '';

            return $prompt->model === 'typesafe/jev-1.14'
                && $prompt->asks('image')
                && ($prompt->state[1]['type'] ?? null) === 'image_url'
                && str_starts_with($image, 'data:image/jpeg;base64,')
                && ($prompt->provider->additionalConfiguration()['headers']['TypeSafe-Danger-Zone-Images'] ?? null) === 'true';
        });
    }

    public function test_unknown_question_is_not_found(): void
    {
        $this->postJson('/not-a-question', ['scenario' => 0])->assertNotFound();
    }
}
