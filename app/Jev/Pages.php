<?php

namespace App\Jev;

use InvalidArgumentException;

class Pages
{
    public const string Origin = 'https://jev.tibor.io';

    public const string Image = 'https://jev.tibor.io/preview.png?v=2';

    public const int ImageWidth = 383;

    public const int ImageHeight = 329;

    public const string ImageAlt = 'Jev - A Decision Model. Classify a message as a boolean, choice, or score.';

    public const string SiteName = 'Jev - A Decision Model';

    public const string LaunchPost = 'https://typesafe.ai/blog/introducing-system-one-models-and-jev';

    public const string ReleasePost = 'https://typesafe.ai/blog/jev-1-14';

    /**
     * @return array<string, array{path: string, title: string, description: string, type: string, og: string}>
     */
    public static function catalog(): array
    {
        return [
            'demo' => [
                'path' => '/',
                'title' => 'Jev - A Decision Model',
                'description' => 'Jev is a decision model from TypeSafe. Classify a message as a boolean, choice, or score, and read the probability.',
                'type' => 'WebPage',
                'og' => 'website',
            ],
            'faqs' => [
                'path' => '/faqs',
                'title' => 'FAQs — Jev - A Decision Model',
                'description' => 'Answers about Jev: what a decision model returns, how Noul, Choice, Score, and Box differ, and how this site runs.',
                'type' => 'FAQPage',
                'og' => 'website',
            ],
            'what' => [
                'path' => '/what-is-jev',
                'title' => 'What is Jev? — Jev - A Decision Model',
                'description' => 'Jev is TypeSafe’s decision model. It reads a state, answers typed questions, and returns probabilities your code can branch on.',
                'type' => 'AboutPage',
                'og' => 'article',
            ],
            'resources' => [
                'path' => '/resources',
                'title' => 'Resources — Jev - A Decision Model',
                'description' => 'Where to read about Jev and where to call it: TypeSafe docs, the launch post, Laravel AI, the JavaScript SDK, and OpenRouter.',
                'type' => 'CollectionPage',
                'og' => 'website',
            ],
            'lore' => [
                'path' => '/lore',
                'title' => 'Lore — Jev - A Decision Model',
                'description' => 'Where the names System One and Jev come from, in TypeSafe’s own account: Kahneman’s fast judgment, and Jevons on coal.',
                'type' => 'Article',
                'og' => 'article',
            ],
        ];
    }

    /**
     * @return array{path: string, title: string, description: string, type: string, og: string, url: string}
     */
    public static function page(string $key): array
    {
        $page = self::catalog()[$key] ?? throw new InvalidArgumentException("Unknown page [{$key}].");
        $page['url'] = self::url($page['path']);

        return $page;
    }

    public static function url(string $path): string
    {
        if ($path === '/') {
            return self::Origin.'/';
        }

        return self::Origin.$path;
    }

    /**
     * @return array<string, mixed>
     */
    public static function graph(string $key): array
    {
        $page = self::page($key);

        $node = [
            '@type' => $page['type'],
            '@id' => $page['url'].'#page',
            'url' => $page['url'],
            'name' => $page['title'],
            'description' => $page['description'],
            'isPartOf' => ['@id' => self::Origin.'/#website'],
            'about' => ['@id' => self::Origin.'/#jev'],
            'publisher' => ['@id' => 'https://tibor.io/#organization'],
            'inLanguage' => 'en',
            'primaryImageOfPage' => ['@id' => self::Image.'#image'],
        ];

        if ($key === 'faqs') {
            $node['mainEntity'] = array_map(
                fn (array $faq): array => [
                    '@type' => 'Question',
                    '@id' => $page['url'].'#'.$faq['id'],
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => self::answerText($faq),
                    ],
                ],
                self::faqs(),
            );
        }

        if ($key === 'what') {
            $node['mainEntity'] = [
                '@type' => 'DefinedTermSet',
                'name' => 'Jev question types',
                'hasDefinedTerm' => [
                    [
                        '@type' => 'DefinedTerm',
                        'name' => 'Noul',
                        'description' => 'Is this true? The answer is a probability from 0 to 1.',
                    ],
                    [
                        '@type' => 'DefinedTerm',
                        'name' => 'Choice',
                        'description' => 'Which one of these? The Demo page asks which team should handle a message: billing, technical, or sales. The answer names one option and shows the probability of each.',
                    ],
                    [
                        '@type' => 'DefinedTerm',
                        'name' => 'Score',
                        'description' => 'Where on this scale? The Demo page asks how frustrated a customer is, from calm to threatening to leave. The number can fall between two named levels.',
                    ],
                    [
                        '@type' => 'DefinedTerm',
                        'name' => 'Box',
                        'description' => 'Where is it in the image? One bounding box: top, bottom, left, and right, as fractions from 0 to 1 of the image size.',
                    ],
                ],
            ];
        }

        if ($key === 'resources') {
            $items = [];

            foreach (self::readingList() as $index => $item) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'url' => $item['url'],
                ];
            }

            $node['mainEntity'] = [
                '@type' => 'ItemList',
                'name' => 'Jev reading list',
                'itemListElement' => $items,
            ];
        }

        if ($key === 'lore') {
            $node['headline'] = 'Lore';
            $node['author'] = ['@id' => 'https://tibor.io/#organization'];
            $node['citation'] = [
                '@type' => 'BlogPosting',
                'headline' => 'Introducing System One Models & Jev',
                'url' => self::LaunchPost,
                'datePublished' => '2026-09-15',
                'author' => [
                    '@type' => 'Person',
                    'name' => 'Diogo Almeida',
                ],
            ];
        }

        $graph = [
            [
                '@type' => 'WebSite',
                '@id' => self::Origin.'/#website',
                'name' => self::SiteName,
                'url' => self::Origin.'/',
                'description' => self::page('demo')['description'],
                'publisher' => ['@id' => 'https://tibor.io/#organization'],
                'inLanguage' => 'en',
            ],
            [
                '@type' => 'Organization',
                '@id' => 'https://tibor.io/#organization',
                'name' => 'tibor.io',
                'url' => 'https://tibor.io',
            ],
            [
                '@type' => 'ImageObject',
                '@id' => self::Image.'#image',
                'url' => self::Image,
                'width' => self::ImageWidth,
                'height' => self::ImageHeight,
                'caption' => self::ImageAlt,
            ],
            [
                '@type' => 'SoftwareApplication',
                '@id' => self::Origin.'/#jev',
                'name' => 'Jev',
                'applicationCategory' => 'DeveloperApplication',
                'description' => 'TypeSafe’s decision model. It reads a state and answers typed questions with probabilities, instead of writing text.',
                'url' => 'https://typesafe.ai/',
                'provider' => [
                    '@type' => 'Organization',
                    'name' => 'TypeSafe',
                    'url' => 'https://typesafe.ai/',
                ],
            ],
            $node,
        ];

        if ($key === 'demo') {
            $graph[] = [
                '@type' => 'SoftwareApplication',
                '@id' => self::Origin.'/#demo',
                'name' => self::SiteName,
                'applicationCategory' => 'DeveloperApplication',
                'operatingSystem' => 'Web',
                'url' => self::Origin.'/',
                'description' => $page['description'],
                'featureList' => ['Boolean', 'Choice', 'Score'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /**
     * Links that also appear as anchors on the resources page.
     *
     * @return list<array{name: string, url: string}>
     */
    public static function readingList(): array
    {
        return [
            ['name' => 'Introducing System One Models & Jev', 'url' => self::LaunchPost],
            ['name' => 'Jev 1.14', 'url' => self::ReleasePost],
            ['name' => 'Introduction', 'url' => 'https://docs.typesafe.ai/introduction'],
            ['name' => 'System One', 'url' => 'https://docs.typesafe.ai/concepts/system-one'],
            ['name' => 'Primitives', 'url' => 'https://docs.typesafe.ai/primitives'],
            ['name' => 'Noul', 'url' => 'https://docs.typesafe.ai/primitives/noul'],
            ['name' => 'Choice', 'url' => 'https://docs.typesafe.ai/primitives/choice'],
            ['name' => 'Score', 'url' => 'https://docs.typesafe.ai/primitives/score'],
            ['name' => 'Box', 'url' => 'https://docs.typesafe.ai/primitives/box'],
            ['name' => 'Confidence', 'url' => 'https://docs.typesafe.ai/confidence'],
            ['name' => 'State', 'url' => 'https://docs.typesafe.ai/concepts/state'],
            ['name' => 'Images', 'url' => 'https://docs.typesafe.ai/images'],
            ['name' => 'How to build with System One', 'url' => 'https://docs.typesafe.ai/concepts/how-to-build-with-system-one'],
            ['name' => 'Jev 1.13 jaggedness', 'url' => 'https://docs.typesafe.ai/model-jaggedness/jev-1.13'],
            ['name' => 'Models', 'url' => 'https://docs.typesafe.ai/models'],
            ['name' => 'Speculative fan-out', 'url' => 'https://docs.typesafe.ai/patterns/fan-out'],
            ['name' => 'Confidence-gated routing', 'url' => 'https://docs.typesafe.ai/patterns/confidence-routing'],
            ['name' => 'Composite scoring', 'url' => 'https://docs.typesafe.ai/patterns/composite-scoring'],
            ['name' => 'Intent routing', 'url' => 'https://docs.typesafe.ai/patterns/intent-routing'],
            ['name' => 'Laravel AI SDK', 'url' => 'https://packagist.org/packages/laravel/ai'],
            ['name' => 'TypeSafe JavaScript SDK', 'url' => 'https://docs.typesafe.ai/sdk/javascript'],
            ['name' => 'TypeSafe Python SDK', 'url' => 'https://docs.typesafe.ai/sdk/python'],
            ['name' => 'HTTP API', 'url' => 'https://docs.typesafe.ai/api'],
            ['name' => 'Jev on OpenRouter', 'url' => 'https://openrouter.ai/docs/guides/community/jev'],
        ];
    }

    /**
     * @return list<array{id: string, question: string, paragraphs: list<string>, points?: list<array{term: string, text: string}>}>
     */
    public static function faqs(): array
    {
        return [
            [
                'id' => 'what-does-jev-return',
                'question' => 'What does Jev return?',
                'paragraphs' => [
                    'A typed answer and a probability, not a paragraph. You send a state (the thing being judged) and one or more questions. Jev evaluates each question against that state and returns a value your code can branch on. Choice, Score, and Box also return a confidence. A yes/no answer does not: its probability is the whole signal.',
                ],
            ],
            [
                'id' => 'what-is-a-system-one-model',
                'question' => 'What is a System One model?',
                'paragraphs' => [
                    'TypeSafe’s name for a model built to make fast, structured decisions for software. It still reads natural language. It does not write a reply, produce code, or explain itself. Jev is the first model in that class, and the flagship. The name points at Daniel Kahneman’s fast judgment, not at a smaller chatbot. The '.self::link(route('jev.lore'), 'lore page').' goes into both names.',
                ],
            ],
            [
                'id' => 'noul-choice-or-score',
                'question' => 'When do I use Noul, Choice, or Score?',
                'paragraphs' => [
                    'Match the question to the shape of the answer you need. Box is a further type, for a place in an image rather than a yes, an option, or a score.',
                ],
                'points' => [
                    ['term' => 'Noul', 'text' => 'is a yes or no. The answer is the probability that the answer is yes, from 0 to 1. Use it when the useful signal is “how true is this?”'],
                    ['term' => 'Choice', 'text' => 'picks one option from a set you define, with no order between the options. You get the selected option, a probability for each, and a confidence.'],
                    ['term' => 'Score', 'text' => 'places the state on an ordered scale whose levels you describe. The score can sit between two levels. You also get a probability for each level, and a confidence.'],
                ],
            ],
            [
                'id' => 'why-boolean',
                'question' => 'Why does this site say Boolean?',
                'paragraphs' => [
                    'Same question, two names. TypeSafe calls the yes/no primitive Noul. The Laravel AI SDK exposes that classification as a boolean, and this site’s PHP snippet reads it with '.self::code("answer('boolean')->isTrue(0.8)").'. The JavaScript snippet reads '.self::code('noul').'. On this page, yes means the probability is above 0.8.',
                ],
            ],
            [
                'id' => 'what-085-means',
                'question' => 'What does 0.85 mean?',
                'paragraphs' => [
                    'For a yes/no question, 0.85 is the probability of yes. It is above this site’s 0.8 line, so the answer is still yes, just less sure than 0.98. Near 0 is a strong no. Near 0.5 means the model gives yes and no about equal weight. That is uncertainty, not a middle category. If you wanted “medium,” ask a Score with a middle level.',
                    'TypeSafe trains these probabilities to be calibrated across many predictions: of the answers given as 0.9, about nine in ten should be a yes. Calibration describes a set of answers. It does not promise that one particular answer is right. The threshold is yours. 0.8 is this site’s line, not a rule of the model.',
                ],
            ],
            [
                'id' => 'confidence',
                'question' => 'What is confidence, and why is it missing on yes/no?',
                'paragraphs' => [
                    'Confidence summarizes how peaked a Choice or Score distribution is. A Choice that puts almost all of its probability on one option is confident. A Choice split across three options is not, even if one option is slightly ahead. Use it to decide whether to act or to hand the case to a person. A Noul answer has no separate confidence field. The probability already says how strongly the model leans yes. A Box answer has its own confidence, from 0 to 1. TypeSafe has not published a threshold for it.',
                ],
            ],
            [
                'id' => 'score-between-levels',
                'question' => 'Can a score land between two levels?',
                'paragraphs' => [
                    'Yes. A Score is a probability-weighted position on your scale. If the levels are 0, 1, and 2, a score of 1.4 sits between the middle level and the top one. The distribution tells you how the weight was split. This site’s frustration scale is Calm, Annoyed but polite, and Angry or threatening to leave.',
                ],
            ],
            [
                'id' => 'one-question-or-several',
                'question' => 'One big question, or several small ones?',
                'paragraphs' => [
                    'Several small ones. Each question should be a judgment a knowledgeable person could make in a few seconds. “Rate this pitch” hides three judgments. Ask about market, feasibility, and differentiation, then combine the scores in your code. When the weighting changes, you change a coefficient, not a prompt. Questions in one request are independent and run in parallel against the same state. Adding one barely changes the wait. If a later question truly depends on an earlier answer, that is a second request.',
                ],
            ],
            [
                'id' => 'does-jev-explain-itself',
                'question' => 'Does Jev explain itself?',
                'paragraphs' => [
                    'No. There is no reasoning trace and no written justification. The answer is constrained to the options or levels you supplied, so it cannot invent a fourth team or a level you did not define. If you need prose for a person to read, that is a different model, and Jev can sit in front of it as a check.',
                ],
            ],
            [
                'id' => 'what-can-i-send',
                'question' => 'What can I send?',
                'paragraphs' => [
                    'Text, and images when you opt in. A string, a JSON object, or an array of text is the ordinary state. An image is listed once in the request’s assets, and a reference to that asset can sit in the state, in a question’s instructions, or in its criteria. Any request with an image sends the header '.self::code('TypeSafe-Danger-Zone-Images: true').'. Jev 1.13 and Jev 1.14 both accept images that way. Audio and video are still not accepted. When the state has several parts, name the field in the question, for example the customer’s message rather than “the ticket.” Question IDs are for your code. They are not sent to the model, so the instructions have to carry the whole question.',
                ],
            ],
            [
                'id' => 'when-to-use-a-language-model',
                'question' => 'When is a language model the better tool?',
                'paragraphs' => [
                    'When the product is the text: a reply, a draft, an explanation, code a person will read. Jev is the decision inside the workflow: route, score, verify, gate. TypeSafe’s published comparison puts a frontier chat model at seconds to minutes, and a System One call in a band of about 70 to 500 milliseconds, with output tokens they describe as free. Those figures are theirs, from the launch post, not a measurement of this site.',
                ],
            ],
            [
                'id' => 'what-is-a-box',
                'question' => 'What is a Box?',
                'paragraphs' => [
                    'A Box finds where something is in an image. The answer is one bounding box: top, bottom, left, and right. Top and bottom are fractions of the height, and left and right are fractions of the width, each from 0 to 1, measured from the top-left. It also returns a confidence from 0 to 1. There is no separate “not found” answer, so a missing target still comes back as four edges. A Box needs an image in the request, the header '.self::code('TypeSafe-Danger-Zone-Images: true').', and the model '.self::code('jev-1.14.0').' or the alias '.self::code('jev-preview').'. Jev 1.13 does not answer Box questions. TypeSafe calls images and Box a danger zone: opt in per request, and they do not recommend them for production yet.',
                ],
            ],
            [
                'id' => 'how-long-can-a-request-be',
                'question' => 'How long can a request be?',
                'paragraphs' => [
                    'Two token limits. The default is 64k for the whole request and 32k for the state plus any one question. The header '.self::code('TypeSafe-Danger-Zone-Long-Context: true').' raises both to 262k. It works on Jev 1.13 and Jev 1.14. TypeSafe says a request that uses it is slower and less accurate than one inside the default limits, and that the price per token does not change.',
                ],
            ],
            [
                'id' => 'which-model-id',
                'question' => 'Which model id should I send?',
                'paragraphs' => [
                    'Jev 1.14.0 was released on 14 October 2026. The model id is '.self::code('jev-1.14.0').'. The alias '.self::code('jev-preview').' points at that id. The alias '.self::code('jev-latest').' still points at '.self::code('jev-1.13.0').'. The models page calls '.self::code('jev-latest').' the most recent stable release. The 14 October post said '.self::code('jev-latest').' would move to '.self::code('jev-1.14.0').' a week after the announcement. Input is $42 per Btok, or $0.042 per Mtok, on both versions. A Btok is a billion tokens and an Mtok is a million. Output tokens are free. The price did not change with this release.',
                ],
            ],
            [
                'id' => 'run-without-a-key',
                'question' => 'How does Run work here with no API key?',
                'paragraphs' => [
                    'The public site has no OpenRouter key, so Run replays a recorded answer and lets the numbers move a little, without changing the decision. A strong yes stays a strong yes. The unmarked question stays above 0.8 and below 0.9. A statement stays a no. Set '.self::code('OPENROUTER_API_KEY').' in a checkout of the '.self::link('https://github.com/tibor-src/jev-site', 'repository').' and Run calls Jev.',
                ],
            ],
        ];
    }

    /**
     * @param  array{id: string, question: string, paragraphs: list<string>, points?: list<array{term: string, text: string}>}  $faq
     */
    public static function answerText(array $faq): string
    {
        $parts = array_map(
            fn (string $paragraph): string => trim(html_entity_decode(strip_tags($paragraph))),
            $faq['paragraphs'],
        );

        foreach ($faq['points'] ?? [] as $point) {
            $parts[] = $point['term'].' '.$point['text'];
        }

        return implode("\n\n", $parts);
    }

    public static function robots(): string
    {
        return "User-agent: *\nAllow: /\n\nSitemap: ".self::Origin."/sitemap.xml\n";
    }

    public static function sitemap(): string
    {
        $urls = '';

        foreach (self::catalog() as $page) {
            $location = htmlspecialchars(self::url($page['path']), ENT_XML1);
            $urls .= "  <url><loc>{$location}</loc></url>\n";
        }

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .$urls
            .'</urlset>'."\n";
    }

    public static function llms(): string
    {
        $lines = [
            '# '.self::SiteName,
            '',
            '> Jev is TypeSafe’s decision model. It reads a state and answers a typed question with a probability, instead of writing text. This site is an independent reading of Jev, published by https://tibor.io.',
            '',
            'Jev does not write a reply. Noul is a yes/no probability. Choice picks one option from a set you define. Score is a position on an ordered scale and can fall between two levels. This site calls a Noul a boolean. Yes means the probability is above 0.8.',
            '',
            'Jev 1.14.0 (alias jev-preview) was released on 14 October 2026. jev-latest still points at jev-1.13.0. A Box question returns one bounding box: top, bottom, left, and right, as fractions from 0 to 1 of the image size. It needs jev-1.14.0 or jev-preview, and the header TypeSafe-Danger-Zone-Images: true. Images can also appear in the state, the instructions, or the criteria, on Jev 1.13 and Jev 1.14, with that same header. TypeSafe-Danger-Zone-Long-Context: true raises the context from 64k tokens per request and 32k per question to 262k, on both versions, and TypeSafe says that is slower and less accurate. Input is $42 per Btok ($0.042 per Mtok). A Btok is a billion tokens and an Mtok is a million. Output tokens are free.',
            '',
            '## Pages',
            '',
        ];

        foreach (self::catalog() as $page) {
            $lines[] = '- ['.$page['title'].']('.self::url($page['path']).'): '.$page['description'];
        }

        $lines[] = '';
        $lines[] = '## FAQs';
        $lines[] = '';

        foreach (self::faqs() as $faq) {
            $lines[] = '- ['.$faq['question'].']('.self::url('/faqs').'#'.$faq['id'].')';
        }

        $lines[] = '';
        $lines[] = '## Primary sources';
        $lines[] = '';

        foreach (self::readingList() as $item) {
            $lines[] = '- ['.$item['name'].']('.$item['url'].')';
        }

        $lines[] = '';

        return implode("\n", $lines);
    }

    public static function json(string $key): string
    {
        return json_encode(
            self::graph($key),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_THROW_ON_ERROR,
        );
    }

    private static function link(string $href, string $label): string
    {
        return '<a href="'.e($href).'" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">'.e($label).'</a>';
    }

    private static function code(string $value): string
    {
        return '<code class="text-[#1b1b18] dark:text-[#EDEDEC]">'.e($value).'</code>';
    }
}
