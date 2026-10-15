<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: string, 2: string, 3: string, 4: string, 5: string, 6: string}>
     */
    public static function pages(): array
    {
        return [
            'demo' => [
                'jev.create',
                'https://jev.tibor.io/',
                'Jev - A Decision Model',
                'Jev is a decision model from TypeSafe. Classify a message as a boolean, choice, or score, and read the probability.',
                'WebPage',
                'website',
                'Classify a message',
            ],
            'faqs' => [
                'jev.faqs',
                'https://jev.tibor.io/faqs',
                'FAQs — Jev - A Decision Model',
                'Answers about Jev: what a decision model returns, how Noul, Choice, Score, and Box differ, and how this site runs.',
                'FAQPage',
                'website',
                'A Noul answer has no separate confidence field.',
            ],
            'what' => [
                'jev.what',
                'https://jev.tibor.io/what-is-jev',
                'What is Jev? — Jev - A Decision Model',
                'Jev is TypeSafe’s decision model. It reads a state, answers typed questions, and returns probabilities your code can branch on.',
                'AboutPage',
                'article',
                'typed probabilistic decisions out',
            ],
            'resources' => [
                'jev.resources',
                'https://jev.tibor.io/resources',
                'Resources — Jev - A Decision Model',
                'Where to read about Jev and where to call it: TypeSafe docs, the launch post, Laravel AI, the JavaScript SDK, and OpenRouter.',
                'CollectionPage',
                'website',
                'Jev 1.13 jaggedness',
            ],
            'lore' => [
                'jev.lore',
                'https://jev.tibor.io/lore',
                'Lore — Jev - A Decision Model',
                'Where the names System One and Jev come from, in TypeSafe’s own account: Kahneman’s fast judgment, and Jevons on coal.',
                'Article',
                'article',
                'William Stanley Jevons',
            ],
        ];
    }

    #[DataProvider('pages')]
    public function test_page_renders_its_title_menu_and_share_card(
        string $name,
        string $url,
        string $title,
        string $description,
        string $type,
        string $ogType,
        string $marker,
    ): void {
        $response = $this->get(route($name));
        $html = $response->getContent();

        $response->assertOk();
        $response->assertSee('<title>'.$title.'</title>', false);
        $response->assertSee('rel="icon" href="/favicon.ico"', false);
        $response->assertSee('rel="icon" href="/favicon.svg" type="image/svg+xml"', false);
        $response->assertSee('rel="apple-touch-icon" href="/apple-touch-icon.png"', false);
        $response->assertSee('name="description" content="'.$description.'"', false);
        $response->assertSee('rel="canonical" href="'.$url.'"', false);
        $response->assertSee('property="og:type" content="'.$ogType.'"', false);
        $response->assertSee('property="og:url" content="'.$url.'"', false);
        $response->assertSee('property="og:site_name" content="Jev - A Decision Model"', false);
        $response->assertSee('property="og:image" content="https://jev.tibor.io/preview.png?v=2"', false);
        $response->assertSee('property="og:image:type" content="image/png"', false);
        $response->assertSee('property="og:image:width" content="383"', false);
        $response->assertSee('property="og:image:height" content="329"', false);
        $response->assertSee('property="og:image:alt" content="Jev - A Decision Model. Classify a message as a boolean, choice, or score."', false);
        $response->assertSee('name="twitter:card" content="summary"', false);
        $response->assertSee('name="twitter:image" content="https://jev.tibor.io/preview.png?v=2"', false);
        $response->assertSee('"width":383', false);
        $response->assertSee('"height":329', false);
        $response->assertSee('name="twitter:title" content="'.$title.'"', false);
        $response->assertSee('name="twitter:description" content="'.$description.'"', false);
        $response->assertSee('"@type":"'.$type.'"', false);
        $response->assertSee($marker, false);
        $response->assertSeeInOrder(['Demo', 'FAQs', 'What is Jev?', 'Resources', 'Lore']);
        $response->assertSee('made by', false);
        $response->assertSee('https://tibor.io', false);

        $this->assertSame(1, substr_count($html, 'aria-current="page"'));
        $this->assertMatchesRegularExpression(
            '/<a\s+href="'.preg_quote(route($name), '/').'"[^>]*aria-current="page"/',
            $html,
        );
    }

    public function test_faq_answers_match_the_visible_page(): void
    {
        $response = $this->get(route('jev.faqs'));
        $html = $response->getContent();
        $visible = $this->plainText($html);
        $faqPage = collect($this->structuredData($html)['@graph'])->firstWhere('@type', 'FAQPage');

        $this->assertIsArray($faqPage);
        preg_match_all('/<h3[^>]*>(.*?)<\/h3>/', $html, $headings);
        $questions = array_map(
            fn (string $heading): string => trim(html_entity_decode(strip_tags($heading))),
            $headings[1],
        );

        $this->assertSame($questions, array_column($faqPage['mainEntity'], 'name'));
        $this->assertContains('What does Jev return?', $questions);
        $this->assertStringContainsString('id="what-does-jev-return"', $html);
        $this->assertStringContainsString('datetime="2026-09-15"', $html);

        foreach ($faqPage['mainEntity'] as $entity) {
            $this->assertStringContainsString($entity['@id'], $html);
            $this->assertStringContainsString($this->plainText($entity['acceptedAnswer']['text']), $visible);
        }
    }

    public function test_what_page_defines_noul_choice_and_score(): void
    {
        $html = $this->get(route('jev.what'))->assertOk()->getContent();
        $about = collect($this->structuredData($html)['@graph'])->firstWhere('@type', 'AboutPage');

        $this->assertSame(
            ['Noul', 'Choice', 'Score', 'Box'],
            array_column($about['mainEntity']['hasDefinedTerm'], 'name'),
        );

        foreach ($about['mainEntity']['hasDefinedTerm'] as $term) {
            $this->assertStringContainsString($term['description'], $html);
        }

        $this->assertStringContainsString('id="function-call"', $html);
        $this->assertStringContainsString('id="three-questions"', $html);
    }

    public function test_resources_page_links_its_reading_list(): void
    {
        $response = $this->get(route('jev.resources'));
        $html = $response->getContent();
        $list = collect($this->structuredData($html)['@graph'])->firstWhere('@type', 'CollectionPage')['mainEntity'];
        $urls = array_column($list['itemListElement'], 'url');

        $this->assertSame('ItemList', $list['@type']);
        $this->assertSame([
            'https://typesafe.ai/blog/introducing-system-one-models-and-jev',
            'https://typesafe.ai/blog/jev-1-14',
            'https://docs.typesafe.ai/introduction',
            'https://docs.typesafe.ai/concepts/system-one',
            'https://docs.typesafe.ai/primitives',
            'https://docs.typesafe.ai/primitives/noul',
            'https://docs.typesafe.ai/primitives/choice',
            'https://docs.typesafe.ai/primitives/score',
            'https://docs.typesafe.ai/primitives/box',
            'https://docs.typesafe.ai/confidence',
            'https://docs.typesafe.ai/concepts/state',
            'https://docs.typesafe.ai/images',
            'https://docs.typesafe.ai/concepts/how-to-build-with-system-one',
            'https://docs.typesafe.ai/model-jaggedness/jev-1.13',
            'https://docs.typesafe.ai/models',
            'https://docs.typesafe.ai/patterns/fan-out',
            'https://docs.typesafe.ai/patterns/confidence-routing',
            'https://docs.typesafe.ai/patterns/composite-scoring',
            'https://docs.typesafe.ai/patterns/intent-routing',
            'https://packagist.org/packages/laravel/ai',
            'https://docs.typesafe.ai/sdk/javascript',
            'https://docs.typesafe.ai/sdk/python',
            'https://docs.typesafe.ai/api',
            'https://openrouter.ai/docs/guides/community/jev',
        ], $urls);

        foreach ($urls as $index => $url) {
            $this->assertSame($index + 1, $list['itemListElement'][$index]['position']);
            $response->assertSee('href="'.$url.'"', false);
        }

        $response->assertSee('https://github.com/tibor-src/jev-site', false);
        $response->assertSee('datetime="2026-09-15"', false);
    }

    public function test_lore_page_cites_the_launch_post(): void
    {
        $html = $this->get(route('jev.lore'))->assertOk()->getContent();

        $this->assertStringContainsString('datetime="2026-09-15"', $html);
        $this->assertStringContainsString('https://typesafe.ai/blog/introducing-system-one-models-and-jev', $html);
        $this->assertStringContainsString('"datePublished":"2026-09-15"', $html);
        $this->assertStringContainsString('Diogo Almeida', $html);
        $this->assertStringContainsString('id="system-one"', $html);
        $this->assertStringContainsString('id="jevons"', $html);
    }

    public function test_demo_page_describes_boolean_choice_and_score(): void
    {
        $this->get(route('jev.create'))
            ->assertOk()
            ->assertSee('"featureList":["Boolean","Choice","Score"]', false)
            ->assertSee('Lang:', false);
    }

    public function test_sitemap_lists_the_five_pages(): void
    {
        $response = $this->get(route('jev.sitemap'));
        $xml = simplexml_load_string($response->getContent());
        $locations = [];

        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }

        $response->assertOk();
        $response->assertHeader('content-type', 'application/xml; charset=UTF-8');
        $this->assertSame([
            'https://jev.tibor.io/',
            'https://jev.tibor.io/faqs',
            'https://jev.tibor.io/what-is-jev',
            'https://jev.tibor.io/resources',
            'https://jev.tibor.io/lore',
        ], $locations);
    }

    public function test_llms_txt_lists_pages_faqs_and_sources(): void
    {
        $response = $this->get(route('jev.llms'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/plain; charset=UTF-8');
        $response->assertSee('# Jev - A Decision Model', false);
        $response->assertSee('](https://jev.tibor.io/)', false);
        $response->assertSee('](https://jev.tibor.io/faqs)', false);
        $response->assertSee('](https://jev.tibor.io/what-is-jev)', false);
        $response->assertSee('](https://jev.tibor.io/resources)', false);
        $response->assertSee('](https://jev.tibor.io/lore)', false);
        $response->assertSee('#what-does-jev-return', false);
        $response->assertSee('https://typesafe.ai/blog/introducing-system-one-models-and-jev', false);
        $response->assertSee('https://typesafe.ai/blog/jev-1-14', false);
        $response->assertSee('TypeSafe-Danger-Zone-Images: true', false);
        $response->assertSee('262k', false);
    }

    public function test_pages_include_the_jev_1_14_release_facts(): void
    {
        $faqs = $this->get(route('jev.faqs'));
        $what = $this->get(route('jev.what'));
        $resources = $this->get(route('jev.resources'));

        $faqs->assertSee('id="what-is-a-box"', false);
        $faqs->assertSee('id="how-long-can-a-request-be"', false);
        $faqs->assertSee('id="which-model-id"', false);
        $faqs->assertSee('TypeSafe-Danger-Zone-Images: true', false);
        $faqs->assertSee('TypeSafe-Danger-Zone-Long-Context: true', false);
        $faqs->assertSee('jev-1.14.0', false);
        $faqs->assertSee('jev-preview', false);
        $faqs->assertSee('top, bottom, left, and right', false);
        $faqs->assertSee('64k', false);
        $faqs->assertSee('32k', false);
        $faqs->assertSee('262k', false);
        $faqs->assertSee('$42 per Btok', false);
        $faqs->assertSee('$0.042 per Mtok', false);
        $faqs->assertSee('Audio and video are still not accepted', false);
        $faqs->assertDontSee('Images, audio, and video are not supported yet', false);
        $faqs->assertSee('datetime="2026-10-14"', false);

        $what->assertSee('Where is it in the image?', false);
        $what->assertSee('TypeSafe-Danger-Zone-Images: true', false);
        $what->assertSee('TypeSafe-Danger-Zone-Long-Context: true', false);
        $what->assertSee('datetime="2026-10-14"', false);

        $resources->assertSee('https://docs.typesafe.ai/images', false);
        $resources->assertSee('https://docs.typesafe.ai/primitives/box', false);
        $resources->assertSee('https://docs.typesafe.ai/models', false);
        $resources->assertSee('64k tokens per request', false);
        $resources->assertSee('32k per question', false);
        $resources->assertSee('262k', false);
        $resources->assertDontSee('No images yet', false);
    }

    public function test_robots_allows_crawlers_and_points_at_the_sitemap(): void
    {
        $response = $this->get(route('jev.robots'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/plain; charset=UTF-8');
        $this->assertSame(
            "User-agent: *\nAllow: /\n\nSitemap: https://jev.tibor.io/sitemap.xml\n",
            $response->getContent(),
        );
        $this->assertSame($response->getContent(), file_get_contents(public_path('robots.txt')));
    }

    public function test_unknown_path_is_not_found(): void
    {
        $this->get('/missing-page')->assertNotFound();
    }

    public function test_returns_405_when_a_page_path_is_posted(): void
    {
        $this->postJson('/faqs', ['scenario' => 0])->assertMethodNotAllowed();
        $this->postJson('/what-is-jev', ['scenario' => 0])->assertMethodNotAllowed();
    }

    /**
     * @return array<string, mixed>
     */
    private function structuredData(string $html): array
    {
        $this->assertSame(1, preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches));

        return json_decode(html_entity_decode($matches[1]), true, flags: JSON_THROW_ON_ERROR);
    }

    private function plainText(string $html): string
    {
        $text = html_entity_decode(strip_tags($html));

        return preg_replace('/\s+/', ' ', $text) ?? $text;
    }
}
