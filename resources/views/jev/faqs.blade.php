@php($page = \App\Jev\Pages::page('faqs'))
<x-layouts.site
    :title="$page['title']"
    :description="$page['description']"
    :canonical="$page['url']"
    :og-type="$page['og']"
    schema-key="faqs"
>
    <article class="flex max-w-xl flex-col gap-8">
        <header class="flex flex-col gap-2">
            <h2 class="text-xl font-medium tracking-tight">FAQs</h2>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">
                Short answers for the questions this site raises. The model is TypeSafe’s. The pages are an independent reading of their docs, the <time datetime="2026-09-15">15 September 2026</time> launch post, and the <time datetime="2026-10-14">14 October 2026</time> Jev 1.14 post.
            </p>
        </header>

        @foreach (\App\Jev\Pages::faqs() as $faq)
            <section id="{{ $faq['id'] }}" class="flex flex-col gap-2">
                <h3 class="text-lg font-medium">{{ $faq['question'] }}</h3>
                @foreach ($faq['paragraphs'] as $paragraph)
                    <p class="text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">{!! $paragraph !!}</p>
                @endforeach
                @if (isset($faq['points']))
                    <ul class="flex list-disc flex-col gap-2 pl-5 text-sm leading-6 text-[#706f6c] dark:text-[#A1A09A]">
                        @foreach ($faq['points'] as $point)
                            <li><span class="text-[#1b1b18] dark:text-[#EDEDEC]">{{ $point['term'] }}</span> {{ $point['text'] }}</li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endforeach
    </article>
</x-layouts.site>
