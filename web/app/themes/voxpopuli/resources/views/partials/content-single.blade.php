@php($pag = $pagination())
@php($cat = $primaryCategory())
@php($img = $featuredImage())

<div class="reading-progress-bar fixed top-0 left-0 h-1.5 bg-secondary z-50 shadow-[0_0_10px_rgba(239,133,25,0.5)] origin-left"
    style="width: 0%; transform: scaleX(0);"></div>

<article @php(post_class('h-entry w-full relative'))>
    @if ($img)
        {{-- Full-bleed Hero Header with Dark Overlay & Texture --}}
        <header
            class="group relative w-full min-h-[50vh] md:min-h-[65vh] flex items-end overflow-hidden mb-10 md:mb-14 bg-base-200 border-b border-base-300 pt-28 pb-8 md:pb-12">
            {{-- Primary Top Bar (Identidad Vox Populi) --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-primary z-20"></div>

            <div class="absolute inset-0 w-full h-full">
                {!! wp_get_attachment_image($img['id'], 'large', false, [
                    'alt' => $img['alt'],
                    'class' => 'w-full h-full object-cover sepia transition-all duration-700 ease-out group-hover:grayscale-0 group-hover:scale-105',
                    'loading' => 'eager',
                    'fetchpriority' => 'high',
                ]) !!}
                <div
                    class="absolute inset-0 bg-primary/75   mix-blend-multiply transition-colors duration-700 group-hover:bg-black/50">
                </div>
                <div
                    class="absolute inset-0 bg-linear-to-t from-primary/95 via-primary-20 to-black/50 pointer-events-none z-10">
                </div>
                <div class="absolute inset-0 noise-overlay opacity-50 mix-blend-overlay pointer-events-none"></div>
            </div>

            <div class="relative z-10 w-full max-w-3xl mx-auto px-4 md:px-0 text-white">
                @if ($cat)
                    <a href="{{ $cat['link'] }}"
                        class="inline-flex items-center font-sans font-extrabold text-[10px] md:text-xs tracking-[0.2em] uppercase bg-secondary text-secondary-content text-shadow-2xs px-3.5 py-1.5 rounded-sm mb-4 hover:bg-secondary/90 transition-all duration-300 shadow-sm">
                        {{ $cat['name'] }}
                    </a>
                @endif

                <h1
                    class="p-name font-display text-4xl md:text-5xl lg:text-6xl text-shadow-lg font-extrabold text-white leading-[1.05] tracking-tighter mb-6 animate-fade-in-up drop-shadow-lg flex items-start gap-3">
                    <span
                        class="text-secondary text-shadow-lg select-none font-sans font-black tracking-widest mt-1 opacity-90">.:</span>
                    <span>{{ $title }}</span>
                </h1>

                <x-entry-meta :reading-time="$readingTime()" class="text-white/90 border-white/20 mb-6" />
                <x-social-share class="text-white/90" />
            </div>
        </header>
    @else
        {{-- Clean Standard Text-only Header (Inside Containment) --}}
        <header
            class="max-w-3xl mx-auto px-6 md:px-10 pt-12 md:pt-16 pb-8 md:pb-10 mb-8 md:mb-12 bg-base-100 border border-base-300/50 rounded-sm shadow-sm relative overflow-hidden">
            {{-- Primary Top Bar --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-primary"></div>

            @if ($cat)
                <a href="{{ $cat['link'] }}"
                    class="inline-flex items-center font-sans font-extrabold text-[10px] md:text-xs tracking-[0.2em] uppercase bg-secondary text-secondary-content px-3.5 py-1.5 rounded-sm mb-4 hover:bg-secondary/90 transition-all duration-300 shadow-sm">
                    {{ $cat['name'] }}
                </a>
            @endif

            <h1
                class="p-name font-display text-3xl md:text-5xl lg:text-6xl font-extrabold text-base-content leading-[1.05] tracking-tighter mb-6 animate-fade-in-up flex items-start gap-3">
                <span class="text-secondary select-none font-sans font-black tracking-widest mt-1">.:</span>
                <span>{{ $title }}</span>
            </h1>

            <x-entry-meta :reading-time="$readingTime()" class="mb-6" />
            <x-social-share class="text-muted" />
        </header>
    @endif

    {{-- Constrained Content Column --}}
    <div class="max-w-3xl mx-auto px-4 md:px-0 pb-12">
        @if ($img && $img['caption'])
            <div
                class="font-sans text-xs text-muted/80 mb-10 -mt-6 px-6 py-2.5 border-l-2 border-secondary/50 italic text-left bg-base-200/40 rounded-r-md">
                {!! wp_kses_post($img['caption']) !!}
            </div>
        @endif

        <div
            class="e-content font-serif text-lg md:text-2xl leading-relaxed prose prose-voxpopuli max-w-none text-base-content/90 prose-headings:font-display prose-headings:font-black prose-headings:text-base-content prose-a:text-primary prose-a:font-bold hover:prose-a:text-secondary prose-a:transition-colors prose-strong:text-base-content">
            @php(the_content())
        </div>

        @if ($pag)
            <footer class="mt-8 pt-6 border-t border-base-300">
                <nav class="page-nav" aria-label="{{ __('Páginas', 'voxpopuli') }}">
                    {!! $pag !!}
                </nav>
            </footer>
        @endif
    </div>

    {{-- Expansive Related Posts Column --}}
    <div class="max-w-6xl mx-auto px-4 md:px-0 pb-16">
        <x-related-posts :suggested="$suggestedPosts()" :featured="$latestFeaturedPost()" />
    </div>

    <x-fab />
</article>
