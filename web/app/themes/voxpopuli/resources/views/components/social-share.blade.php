@props([
  'url' => get_permalink(),
  'title' => get_the_title(),
])

@php
  $encodedUrl = urlencode($url);
  $encodedTitle = urlencode($title);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }} data-share-container>
  <span class="font-sans text-[10px] tracking-wider uppercase font-extrabold opacity-80 mr-2 select-none">
    {{ __('Compartir', 'voxpopuli') }}
  </span>

  {{-- Twitter / X --}}
  <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}"
     target="_blank"
     rel="noopener noreferrer"
     class="btn btn-circle btn-sm btn-ghost hover:bg-current/10 text-current hover:text-secondary transition-all duration-300 border border-current/20"
     aria-label="{{ __('Compartir en Twitter', 'voxpopuli') }}">
    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
    </svg>
  </a>

  {{-- Facebook --}}
  <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
     target="_blank"
     rel="noopener noreferrer"
     class="btn btn-circle btn-sm btn-ghost hover:bg-current/10 text-current hover:text-secondary transition-all duration-300 border border-current/20"
     aria-label="{{ __('Compartir en Facebook', 'voxpopuli') }}">
    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
      <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
    </svg>
  </a>

  {{-- WhatsApp --}}
  <a href="https://api.whatsapp.com/send?text={{ $encodedTitle }}%20{{ $encodedUrl }}"
     target="_blank"
     rel="noopener noreferrer"
     class="btn btn-circle btn-sm btn-ghost hover:bg-current/10 text-current hover:text-secondary transition-all duration-300 border border-current/20"
     aria-label="{{ __('Compartir en WhatsApp', 'voxpopuli') }}">
    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
      <path d="M12.01 2.012a10 10 0 0 0-8.66 14.98l-1.32 4.81 4.93-1.29a9.96 9.96 0 1 0 5.05-18.5zm5.55 13.98c-.25.7-1.46 1.37-2 1.41-.54.05-1.22.08-3.48-.85-2.9-1.2-4.77-4.16-4.92-4.36-.14-.2-1.21-1.61-1.21-3.07 0-1.46.76-2.18 1.03-2.46.2-.2.43-.26.57-.26.14 0 .28 0 .4.01.12.01.29-.05.45.34.17.4.58 1.41.63 1.52.05.11.08.24.01.38-.07.14-.11.23-.23.36-.11.13-.24.3-.35.4-.12.11-.25.24-.1.49.14.25.64 1.07 1.39 1.74.96.86 1.77 1.13 2.02 1.25.25.12.4.1.55-.07.15-.17.65-.76.82-1.02.17-.26.34-.22.58-.13.24.09 1.53.72 1.79.85.26.13.43.2.5.31.06.11.06.66-.19 1.36z"/>
    </svg>
  </a>

  {{-- Copy Link (Interactive Clipboard) --}}
  <button onclick="navigator.clipboard.writeText('{{ $url }}').then(() => { 
            const btn = this;
            const originalHtml = btn.innerHTML;
            btn.classList.add('btn-success', 'text-success-content');
            btn.innerHTML = `<svg class='h-4 w-4' fill='none' stroke='currentColor' stroke-width='2.5' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7'></path></svg>`;
            setTimeout(() => { 
              btn.classList.remove('btn-success', 'text-success-content');
              btn.innerHTML = originalHtml;
            }, 2000);
          })"
          class="btn btn-circle btn-sm btn-ghost hover:bg-current/10 text-current hover:text-secondary transition-all duration-300 border border-current/20 tooltip tooltip-bottom"
          data-tip="{{ __('Copiar enlace', 'voxpopuli') }}"
          aria-label="{{ __('Copiar enlace del artículo', 'voxpopuli') }}">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
    </svg>
  </button>
</div>
