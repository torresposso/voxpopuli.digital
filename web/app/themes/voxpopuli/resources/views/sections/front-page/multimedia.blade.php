@php
    $posts = collect($posts);
    $videoPost = $posts->firstWhere('category', 'Videos');
    $podcastPost = $posts->firstWhere('category', 'Podcast');
    $otherPosts = $posts->reject(fn($p) => in_array($p->category, ['Videos', 'Podcast']));

    // Extract YouTube video ID from various URL formats
    $youtubeId = null;
    if ($videoPost && !empty($videoPost->url)) {
        preg_match(
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/',
            $videoPost->url,
            $matches
        );
        $youtubeId = $matches[1] ?? null;
    }
@endphp

<section>
    {{-- SECTION HEADER — "Multimedia" with bottom border --}}
    <h2 class="font-display font-bold text-[2rem] sm:text-[2.5rem] text-base-content border-b-2 border-base-300 pb-4 mb-8">
        {{ __('Multimedia', 'voxpopuli') }}
    </h2>

    <div class="grid lg:grid-cols-2 gap-8">
        {{-- LEFT COLUMN — En Video (YouTube embed 16:9) --}}
        <div>
            <h3 class="font-sans font-extrabold text-[0.75rem] uppercase tracking-[0.2em] text-neutral mb-4">
                {{ __('En Video', 'voxpopuli') }}
            </h3>

            @if ($videoPost && $youtubeId)
                <div class="card card-border bg-base-200 rounded-box overflow-hidden">
                    {{-- Responsive 16:9 YouTube embed --}}
                    <div class="aspect-video relative">
                        <iframe
                            src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}"
                            title="{{ $videoPost->title }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            class="absolute inset-0 w-full h-full"
                        ></iframe>
                    </div>
                    <div class="card-body p-[1.5rem] gap-[0.5rem]">
                        <h4 class="font-display font-bold text-[1.25rem] tracking-tighter text-base-content leading-tight">
                            <a href="{{ $videoPost->url }}"
                               class="hover:text-accent transition-colors duration-[200ms]">
                                {{ $videoPost->title }}
                            </a>
                        </h4>
                        @if (!empty($videoPost->excerpt))
                            <p class="font-serif text-[0.9375rem] leading-relaxed text-base-content/80 line-clamp-2">
                                {{ $videoPost->excerpt }}
                            </p>
                        @endif
                    </div>
                </div>
            @else
                <div class="card card-border bg-base-200 rounded-box">
                    <div class="card-body p-[1.5rem] text-center">
                        <p class="font-serif text-base-content/60 italic">
                            {{ __('Próximamente contenido en video', 'voxpopuli') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN — Podcast (title + excerpt + link) --}}
        <div>
            <h3 class="font-sans font-extrabold text-[0.75rem] uppercase tracking-[0.2em] text-neutral mb-4">
                {{ __('Podcast', 'voxpopuli') }}
            </h3>

            @if ($podcastPost)
                <div class="card card-border bg-base-200 rounded-box">
                    <div class="card-body p-[1.5rem] gap-[1rem]">
                        <h4 class="card-title font-display font-bold text-[1.25rem] tracking-tighter">
                            {{ $podcastPost->title }}
                        </h4>
                        @if (!empty($podcastPost->excerpt))
                            <p class="font-serif text-[0.9375rem] leading-relaxed text-base-content/80 line-clamp-3">
                                {{ $podcastPost->excerpt }}
                            </p>
                        @endif
                        <div class="card-actions mt-2">
                            <a href="{{ $podcastPost->url }}"
                               class="btn btn-primary font-sans font-bold text-[0.75rem] uppercase tracking-wider">
                                {{ __('Escuchar', 'voxpopuli') }} →
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-border bg-base-200 rounded-box">
                    <div class="card-body p-[1.5rem] text-center">
                        <p class="font-serif text-base-content/60 italic">
                            {{ __('Próximamente contenido en podcast', 'voxpopuli') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SECONDARY LINKS — Other multimedia posts --}}
    @if ($otherPosts->isNotEmpty())
        <div class="mt-8 pt-6 border-t-2 border-base-300">
            <h3 class="font-sans font-extrabold text-[0.75rem] uppercase tracking-[0.2em] text-neutral mb-4">
                {{ __('Más contenido multimedia', 'voxpopuli') }}
            </h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($otherPosts as $post)
                    <a href="{{ $post->url }}"
                       class="card card-border bg-base-200 rounded-box hover:border-accent transition-colors duration-[200ms] group">
                        <div class="card-body p-[1rem] gap-[0.25rem]">
                            @if (!empty($post->category))
                                <span class="font-sans font-bold text-[0.625rem] uppercase tracking-wider text-accent">
                                    {{ $post->category }}
                                </span>
                            @endif
                            <h4 class="font-display font-bold text-[1rem] tracking-tighter text-base-content group-hover:text-accent transition-colors duration-[200ms] leading-tight">
                                {{ $post->title }}
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</section>
