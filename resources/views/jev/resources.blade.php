@php($page = \App\Jev\Pages::page('resources'))
<x-layouts.site
    :title="$page['title']"
    :description="$page['description']"
    :canonical="$page['url']"
    :og-type="$page['og']"
    schema-key="resources"
>
    <article class="flex max-w-xl flex-col gap-8">
        <header class="flex flex-col gap-2">
            <h2 class="text-xl font-medium tracking-tight">Resources</h2>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                Primary sources first. TypeSafe writes the model. This site only shows it.
            </p>
        </header>

        <section id="read" class="flex flex-col gap-4">
            <h3 class="text-lg font-medium">Read</h3>
            <ul class="flex flex-col gap-4 text-sm leading-6">
                <li>
                    <a href="https://typesafe.ai/blog/introducing-system-one-models-and-jev" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Introducing System One Models &amp; Jev</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Diogo Almeida’s launch post, <time datetime="2026-09-15">15 September 2026</time>. The names, the training claim (RLCD), the speed and price figures, and the comparison with chat models.</p>
                </li>
                <li>
                    <a href="https://typesafe.ai/blog/jev-1-14" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Jev 1.14</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]"><time datetime="2026-10-14">14 October 2026</time>. Jev 1.14.0, under the alias <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-preview</code>. Images, a Box type, and a longer context, each behind a danger-zone header. The price is unchanged.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/introduction" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Introduction</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">The short version: state and typed questions in, structured answers out.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/concepts/system-one" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">System One</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">What the model class is for, and how it differs from a language model.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/primitives" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Primitives</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">How to choose Noul, Choice, Score, or Box, and how to ask several at once. The pages for <a href="https://docs.typesafe.ai/primitives/noul" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Noul</a>, <a href="https://docs.typesafe.ai/primitives/choice" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Choice</a>, <a href="https://docs.typesafe.ai/primitives/score" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Score</a>, and <a href="https://docs.typesafe.ai/primitives/box" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Box</a> have the response fields.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/confidence" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Confidence</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">How confidence is derived from a distribution, and how to use it as the line between acting and escalating.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/concepts/state" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">State</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">What to send as context, and how a question points at a nested field.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/images" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Images</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">An image is listed in <code class="text-[#1b1b18] dark:text-[#EDEDEC]">assets</code>, and a reference to it can sit in the state, in the instructions, or in the criteria. Send <code class="text-[#1b1b18] dark:text-[#EDEDEC]">TypeSafe-Danger-Zone-Images: true</code>. Jev 1.13 and Jev 1.14 both accept images. A Box question needs 1.14 and the same header.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/concepts/how-to-build-with-system-one" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">How to build with System One</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Keep the workflow in code. Give the model narrow judgments.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/model-jaggedness/jev-1.13" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Jev 1.13 jaggedness</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">TypeSafe’s own list of rough edges on Jev 1.13. Worth reading before you trust a new question shape. Jev 1.14 is the newer release. This page is still about 1.13.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/models" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Models</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]"><code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-1.14.0</code> is the <time datetime="2026-10-14">14 October 2026</time> release. The alias <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-preview</code> points at it. <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-latest</code> still points at <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-1.13.0</code>. Input is $42 per Btok, which is $0.042 per Mtok. Output tokens are free. The default context is 64k tokens per request and 32k per question. The header <code class="text-[#1b1b18] dark:text-[#EDEDEC]">TypeSafe-Danger-Zone-Long-Context: true</code> raises both to 262k on 1.13 and 1.14. TypeSafe says that path is slower and less accurate.</p>
                </li>
            </ul>
        </section>

        <section id="patterns" class="flex flex-col gap-4">
            <h3 class="text-lg font-medium">Patterns</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                These four are the ones that change how you design the call. The <a href="https://docs.typesafe.ai/patterns" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">patterns index</a> and the <a href="https://docs.typesafe.ai/cookbooks" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">cookbooks</a> go further.
            </p>
            <ul class="flex list-disc flex-col gap-2 pl-5 text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                <li><a href="https://docs.typesafe.ai/patterns/fan-out" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Speculative fan-out</a>. Ask every question you might need, including ones some inputs will ignore.</li>
                <li><a href="https://docs.typesafe.ai/patterns/confidence-routing" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Confidence-gated routing</a>. The answer says what. Confidence says whether to act.</li>
                <li><a href="https://docs.typesafe.ai/patterns/composite-scoring" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Composite scoring</a>. Split one vague rating into atomic scores and weight them yourself.</li>
                <li><a href="https://docs.typesafe.ai/patterns/intent-routing" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Intent routing</a>. Send a request to code, to a specialist model, or to a person.</li>
            </ul>
        </section>

        <section id="call-it" class="flex flex-col gap-4">
            <h3 class="text-lg font-medium">Call it</h3>
            <ul class="flex flex-col gap-4 text-sm leading-6">
                <li>
                    <a href="https://packagist.org/packages/laravel/ai" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Laravel AI SDK</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">What this site uses. Boolean, choice, and score classifications. A boolean is the Noul question under Laravel’s name.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/sdk/javascript" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">TypeSafe JavaScript SDK</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">The official client. <code class="text-[#1b1b18] dark:text-[#EDEDEC]">noul()</code>, <code class="text-[#1b1b18] dark:text-[#EDEDEC]">choice()</code>, and <code class="text-[#1b1b18] dark:text-[#EDEDEC]">score()</code>. The snippets on the Demo page follow it.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/sdk/python" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">TypeSafe Python SDK</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">The same questions as Python objects, synchronous or asynchronous.</p>
                </li>
                <li>
                    <a href="https://docs.typesafe.ai/api" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">HTTP API</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]"><code class="text-[#1b1b18] dark:text-[#EDEDEC]">POST /v1/systemone</code>. The <code class="text-[#1b1b18] dark:text-[#EDEDEC]">model</code> field selects Jev. The docs’ examples use <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-latest</code>, which still names <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-1.13.0</code>. Box examples use <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-preview</code> or <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-1.14.0</code>. Images need <code class="text-[#1b1b18] dark:text-[#EDEDEC]">TypeSafe-Danger-Zone-Images: true</code>. A longer context needs <code class="text-[#1b1b18] dark:text-[#EDEDEC]">TypeSafe-Danger-Zone-Long-Context: true</code>.</p>
                </li>
                <li>
                    <a href="https://openrouter.ai/docs/guides/community/jev" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Jev on OpenRouter</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">How to call <code class="text-[#1b1b18] dark:text-[#EDEDEC]">~typesafe/jev-latest</code> with an OpenRouter key. That is the path this site takes when a key is set. <a href="https://openrouter.ai/" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">OpenRouter</a> bills the account.</p>
                </li>
            </ul>
        </section>

        <section id="this-site" class="flex flex-col gap-4">
            <h3 class="text-lg font-medium">This site</h3>
            <ul class="flex flex-col gap-4 text-sm leading-6">
                <li>
                    <a href="{{ route('jev.create') }}" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">Demo</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">Three questions, recorded answers on the public site, live answers when you bring a key.</p>
                </li>
                <li>
                    <a href="https://github.com/tibor-src/jev-site" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">github.com/tibor-src/jev-site</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">The Laravel app behind these pages.</p>
                </li>
                <li>
                    <a href="https://tibor.io" class="font-medium text-[#1b1b18] underline dark:text-[#EDEDEC]">tibor.io</a>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">The person who published the site.</p>
                </li>
            </ul>
        </section>
    </article>
</x-layouts.site>
