@php($page = \App\Jev\Pages::page('what'))
<x-layouts.site
    :title="$page['title']"
    :description="$page['description']"
    :canonical="$page['url']"
    :og-type="$page['og']"
    schema-key="what"
>
    <article class="flex max-w-xl flex-col gap-8">
        <header class="flex flex-col gap-2">
            <h2 class="text-xl font-medium tracking-tight">What is Jev?</h2>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                Jev is a decision model. It reads a state, answers the questions you defined, and returns values and probabilities. It does not write the reply.
            </p>
        </header>

        <section id="function-call" class="flex flex-col gap-3">
            <h3 class="text-lg font-medium">A function call, not a chat</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                Language models are built to produce text for a person to read. When software needs a judgment, that text has to be parsed back into something a program can trust, and the model can still wander off the shape you asked for. Jev is TypeSafe’s answer to that mismatch. Diogo Almeida, founder of TypeSafe, described it at launch as a frontier-intelligence function call: unstructured state in, typed probabilistic decisions out.
            </p>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                Jev is the flagship, and the first of what TypeSafe calls System One models. The class is named for fast, focused judgment. The model still understands natural language. The output is the decision, already typed.
            </p>
        </section>

        <section id="three-questions" class="flex flex-col gap-3">
            <h3 class="text-lg font-medium">Question types</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                Every question has an id, a type, and instructions. Choice and Score also take criteria: the options, or the ordered levels. A yes/no question may say what yes and no mean. A Box does not take criteria. You can mix types in one request. Each is judged on its own, against the same state, in parallel.
            </p>
            <ul class="flex list-disc flex-col gap-2 pl-5 text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                <li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">Noul.</span> Is this true? The answer is a probability from 0 to 1. The Demo page’s “Is this a question?” is that kind of question. Laravel calls it a boolean. TypeSafe calls it a Noul.</li>
                <li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">Choice.</span> Which one of these? The Demo page asks which team should handle a message: billing, technical, or sales. The answer names one option and shows the probability of each.</li>
                <li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">Score.</span> Where on this scale? The Demo page asks how frustrated a customer is, from calm to threatening to leave. The number can fall between two named levels.</li>
                <li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">Box.</span> Where is it in the image? One bounding box: top, bottom, left, and right, as fractions from 0 to 1 of the image size. It needs <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-1.14.0</code> or <code class="text-[#1b1b18] dark:text-[#EDEDEC]">jev-preview</code>, and the header <code class="text-[#1b1b18] dark:text-[#EDEDEC]">TypeSafe-Danger-Zone-Images: true</code>. The Demo page does not ask one.</li>
            </ul>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                Jev 1.14.0 was released on <time datetime="2026-10-14">14 October 2026</time>. Images and a longer context stay off until the request opts in. An image is named in <code class="text-[#1b1b18] dark:text-[#EDEDEC]">assets</code>, and that reference can sit in the state, the instructions, or the criteria. Jev 1.13 accepts images with the same header. Box does not run on 1.13. The header <code class="text-[#1b1b18] dark:text-[#EDEDEC]">TypeSafe-Danger-Zone-Long-Context: true</code> raises the context limits on both versions. The <a href="{{ route('jev.faqs') }}#how-long-can-a-request-be" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">FAQs</a> have the token counts and the price.
            </p>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                Pick the type your code can act on. A Choice maps onto branches. A Score maps onto a threshold. A Noul maps onto an if. A Box maps onto a crop.
            </p>
        </section>

        <section id="composed-in-code" class="flex flex-col gap-3">
            <h3 class="text-lg font-medium">Small judgments, composed in code</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                A useful question is one a knowledgeable person could answer in a few seconds with the right context. “Does this message ask for a refund?” is that kind of question. “Decide what we should do about this customer” is not. Split the second one. Ask each factor on its own, then weight the answers in ordinary code. When the policy changes, you change the weights.
            </p>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                The possible answers are yours. Jev returns a distribution over the options or levels you supplied. It does not add an option, and it does not attach an essay. Confidence, on Choice and Score, tells you how concentrated that distribution is, which is how you decide whether to act or escalate.
            </p>
        </section>

        <section id="this-site" class="flex flex-col gap-3">
            <h3 class="text-lg font-medium">What this site is</h3>
            <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                The Demo page runs those three questions on a message, and one question on a picture. The live site has no API key, so Run replays a recorded answer. A checkout with <code class="text-[#1b1b18] dark:text-[#EDEDEC]">OPENROUTER_API_KEY</code> set calls Jev through the Laravel AI SDK. The <a href="{{ route('jev.create') }}" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">Demo</a> page is where an answer shows up. The <a href="{{ route('jev.faqs') }}" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">FAQs</a> cover thresholds, confidence, and the boolean name. The <a href="{{ route('jev.resources') }}" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">resources</a> point at TypeSafe’s own docs.
            </p>
        </section>
    </article>
</x-layouts.site>
