@php($page = \App\Jev\Pages::page('demo'))
<x-layouts.site
    :title="$page['title']"
    :description="$page['description']"
    :canonical="$page['url']"
    :og-type="$page['og']"
    schema-key="demo"
>
    <x-slot:lang>
        <div class="flex shrink-0 items-center gap-2 text-sm">
            <span class="text-[#706f6c] dark:text-[#A1A09A]">Lang:</span>
            <div class="flex gap-1">
                <button type="button" data-lang="js" aria-pressed="false" class="rounded-md border border-[#e3e3e0] px-2 py-1 font-medium dark:border-[#3E3E3A]">JS</button>
                <button type="button" data-lang="php" aria-pressed="true" class="rounded-md bg-[#f53003] px-2 py-1 font-medium text-white dark:bg-[#FF4433]">PHP</button>
            </div>
        </div>
    </x-slot:lang>

    <div class="flex flex-col gap-8">
        <div class="flex max-w-xl flex-col gap-4">
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                Jev is a decision model from TypeSafe. It reads a message and answers a typed question with a probability, instead of writing text.
            </p>
            <div class="flex flex-col gap-2">
                <h2 class="text-xl font-medium tracking-tight">Classify a message</h2>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    Boolean, choice, score, and a picture. Pick a message or look at the picture, run the question, and read the answer.
                </p>
            </div>
        </div>

        @foreach ($questions as $type => $question)
            <section data-jev="{{ $type }}" class="flex flex-col gap-4 rounded-lg border border-[#e3e3e0] bg-white p-5 dark:border-[#3E3E3A] dark:bg-[#161615]">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-lg font-medium">{{ $question['title'] }}</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $question['summary'] }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        @unless ($type === 'image')
                            <label class="sr-only" for="{{ $type }}-text">Message for {{ $question['title'] }}</label>
                            <select id="{{ $type }}-text" class="w-56 max-w-full rounded-md border border-[#e3e3e0] bg-white px-2 py-1.5 text-sm dark:border-[#3E3E3A] dark:bg-[#0a0a0a]">
                                @foreach ($question['scenarios'] as $index => $scenario)
                                    <option value="{{ $index }}" title="{{ $scenario['text'] }}">{{ $scenario['text'] }}</option>
                                @endforeach
                            </select>
                        @endunless
                        <button type="button" class="shrink-0 rounded-md bg-[#f53003] px-3 py-1.5 text-sm font-medium text-white disabled:opacity-60 dark:bg-[#FF4433]">
                            Run
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-xs font-medium tracking-wider text-[#706f6c] uppercase dark:text-[#A1A09A]">Question</p>
                    <p class="text-sm font-medium">{{ $question['question']['instructions'] }}</p>
                    @if ($type === 'image')
                        <img
                            src="{{ $question['image'] }}"
                            alt="{{ $question['alt'] }}"
                            width="400"
                            height="400"
                            class="mt-2 w-48 rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A]"
                        >
                    @else
                        <p class="text-sm">
                            <span class="font-medium">Input:</span>
                            <code data-input class="rounded bg-[#FDFDFC] px-1.5 py-0.5 font-mono text-[13px] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">{{ $question['scenarios'][0]['text'] }}</code>
                        </p>
                    @endif
                    @if ($type === 'boolean' || $type === 'image')
                        <ul class="flex list-disc flex-col gap-1 pl-5 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <li>Yes: {{ $question['question']['criteria']['true'] }}</li>
                            <li>No: {{ $question['question']['criteria']['false'] }}</li>
                        </ul>
                    @elseif ($type === 'choice')
                        <ul class="flex list-disc flex-col gap-1 pl-5 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            @foreach ($question['question']['options'] as $option => $description)
                                <li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $option }}</span> {{ $description }}</li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="flex list-disc flex-col gap-1 pl-5 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            @foreach ($question['question']['levels'] as $level)
                                <li>{{ $level }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="flex flex-col gap-2">
                    <p class="text-xs font-medium tracking-wider text-[#706f6c] uppercase dark:text-[#A1A09A]">Answer</p>
                    <div data-before class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Run to see the answer.
                    </div>
                    <div data-running hidden class="animate-pulse text-sm">
                        Running…
                    </div>
                    <div data-after hidden class="flex flex-col gap-3"></div>
                </div>

                <details>
                    <summary class="cursor-pointer text-sm font-medium">Request and response</summary>
                    <div class="flex flex-col gap-3 pt-3">
                        <div class="flex flex-col gap-2">
                            <h4 class="text-sm font-medium">Request</h4>
                            <pre data-request class="overflow-x-auto rounded-lg bg-[#FDFDFC] p-4 text-xs leading-5 dark:bg-[#0a0a0a]"></pre>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h4 class="text-sm font-medium">Response</h4>
                            <pre data-response class="overflow-x-auto rounded-lg bg-[#FDFDFC] p-4 text-xs leading-5 dark:bg-[#0a0a0a]">Response appears after you run this question.</pre>
                        </div>
                    </div>
                </details>
            </section>
        @endforeach

        <ul class="flex list-disc flex-col gap-2 pl-5 text-sm text-[#706f6c] dark:text-[#A1A09A]">
            <li>No API key: Run replays a recorded answer and lets it vary a little.</li>
            <li>
                Live: check out
                <a href="https://github.com/tibor-src/jev-site" class="text-[#1b1b18] underline dark:text-[#EDEDEC]">github.com/tibor-src/jev-site</a>
                and set <code class="text-[#1b1b18] dark:text-[#EDEDEC]">OPENROUTER_API_KEY</code> in <code class="text-[#1b1b18] dark:text-[#EDEDEC]">.env</code>.
            </li>
        </ul>
    </div>
    <script type="application/json" id="jev-demo">@json(['live' => $live, 'questions' => $questions])</script>
</x-layouts.site>
