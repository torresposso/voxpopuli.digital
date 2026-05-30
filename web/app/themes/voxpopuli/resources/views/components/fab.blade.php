@props([
  'url' => get_permalink(),
  'title' => get_the_title(),
])

@php
  $encodedUrl = urlencode($url);
  $encodedTitle = urlencode($title);
@endphp

@once
  @push('styles')
    <style>
      /* Premium Floating Action Button (FAB) */
      .fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 45;
        display: flex;
        flex-direction: column-reverse;
        align-items: center;
        gap: 0.75rem;
      }

      .fab button,
      .fab a {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
      }

      .fab.fab-active button,
      .fab.fab-active a {
        opacity: 1;
        pointer-events: auto;
      }

      .fab.fab-active *:nth-child(2) { transition-delay: 0ms; }
      .fab.fab-active *:nth-child(3) { transition-delay: 0ms; }
      .fab.fab-active *:nth-child(4) { transition-delay: 0ms; }
      .fab.fab-active *:nth-child(5) { transition-delay: 0ms; }

      /* FAB Radial Scroll Progress Animation (Pure CSS Scroll-driven) */
      @keyframes draw-progress {
        from {
          stroke-dashoffset: 210.49;
        }
        to {
          stroke-dashoffset: 0;
        }
      }

      /* Bind progress strictly to post content view timeline */
      .h-entry {
        timeline-scope: --post-view;
      }

      .e-content {
        view-timeline-name: --post-view;
        view-timeline-axis: block;
      }

      .fab-progress-circle {
        animation: draw-progress auto linear forwards;
        animation-timeline: --post-view;
        animation-range: entry 0% exit 100%; /* Tracks progress strictly while article is in scroll port */
      }

      /* Scroll-direction fade-out — only when menu is NOT open */
      .fab.fab-hidden:not(.fab-active) {
        opacity: 0 !important;
        transform: scale(0.9) translateY(15px) !important;
        pointer-events: none !important;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }

      /* Premium OKLCH Relative Color Glassmorphism for FAB Sub-buttons */
      .fab-sub-button {
        background-color: oklch(from var(--color-base-100) 98% 0.005 h / 0.85) !important;
        border: 1px solid oklch(from var(--color-primary) 70% 0.05 h / 0.25) !important;
        color: var(--color-primary) !important;
        backdrop-filter: blur(12px) !important;
        box-shadow: 0 8px 32px oklch(from var(--color-primary) 0.1 0.02 h / 0.06) !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }

      .fab-sub-button:hover {
        background-color: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
        color: var(--color-primary-content) !important;
        box-shadow: 0 12px 30px oklch(from var(--color-primary) 0.2 0.08 h / 0.25) !important;
      }

      /* Premium OKLCH Glow Shadow for Main Trigger Button */
      .fab .btn-secondary {
        background-color: var(--color-secondary) !important;
        border-color: var(--color-secondary) !important;
        color: var(--color-secondary-content) !important;
        box-shadow: 0 8px 24px oklch(from var(--color-secondary) 65% 0.16 h / 0.35) !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }

      .fab .btn-secondary:hover {
        box-shadow: 0 12px 32px oklch(from var(--color-secondary) 65% 0.16 h / 0.5) !important;
        transform: scale(1.06) rotate(12deg) !important;
      }
    </style>
  @endpush
@endonce

<div class="fab" data-share-container>
  <!-- Main trigger button with Radial Scroll Progress Border -->
  <div class="relative flex items-center justify-center z-50 size-[72px]">
    <!-- Radial Scroll Progress SVG -->
    <svg class="absolute size-full -rotate-90 pointer-events-none" viewBox="0 0 72 72">
      <!-- Background track -->
      <circle cx="36" cy="36" r="33.5" class="stroke-base-content/10 fill-none" stroke-width="2.5" />
      <!-- Drawing progress circle -->
      <circle cx="36" cy="36" r="33.5" class="stroke-primary fill-none fab-progress-circle" stroke-width="2.5" stroke-dasharray="210.49" stroke-dashoffset="210.49" stroke-linecap="round" />
    </svg>

    <div tabindex="0" role="button" class="btn btn-lg btn-circle btn-secondary shadow-xl hover:rotate-12 hover:scale-105 transition-all duration-300 flex items-center justify-center size-16" aria-label="{{ __('Compartir artículo', 'voxpopuli') }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
      </svg>
    </div>
  </div>

  <!-- Social Share buttons that show up when FAB is open -->
  
  {{-- Copy Link (Interactive Clipboard) --}}
  <button type="button"
          data-copy-url="{{ $url }}"
          class="btn btn-lg btn-circle fab-sub-button shadow-lg hover:scale-105 transition-all duration-300 tooltip tooltip-left"
          data-tip="{{ __('Copiar enlace', 'voxpopuli') }}"
          aria-label="{{ __('Copiar enlace del artículo', 'voxpopuli') }}">
    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
    </svg>
  </button>

  {{-- WhatsApp --}}
  <a href="https://api.whatsapp.com/send?text={{ $encodedTitle }}%20{{ $encodedUrl }}"
     target="_blank"
     rel="noopener noreferrer"
     class="btn btn-lg btn-circle fab-sub-button shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center tooltip tooltip-left"
     data-tip="{{ __('WhatsApp', 'voxpopuli') }}"
     aria-label="{{ __('Compartir en WhatsApp', 'voxpopuli') }}">
    <svg class="size-6 fill-current" viewBox="0 0 24 24">
      <path d="M12.01 2.012a10 10 0 0 0-8.66 14.98l-1.32 4.81 4.93-1.29a9.96 9.96 0 1 0 5.05-18.5zm5.55 13.98c-.25.7-1.46 1.37-2 1.41-.54.05-1.22.08-3.48-.85-2.9-1.2-4.77-4.16-4.92-4.36-.14-.2-1.21-1.61-1.21-3.07 0-1.46.76-2.18 1.03-2.46.2-.2.43-.26.57-.26.14 0 .28 0 .4.01.12.01.29-.05.45.34.17.4.58 1.41.63 1.52.05.11.08.24.01.38-.07.14-.11.23-.23.36-.11.13-.24.3-.35.4-.12.11-.25.24-.1.49.14.25.64 1.07 1.39 1.74.96.86 1.77 1.13 2.02 1.25.25.12.4.1.55-.07.15-.17.65-.76.82-1.02.17-.26.34-.22.58-.13.24.09 1.53.72 1.79.85.26.13.43.2.5.31.06.11.06.66-.19 1.36z"/>
    </svg>
  </a>

  {{-- Facebook --}}
  <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
     target="_blank"
     rel="noopener noreferrer"
     class="btn btn-lg btn-circle fab-sub-button shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center tooltip tooltip-left"
     data-tip="{{ __('Facebook', 'voxpopuli') }}"
     aria-label="{{ __('Compartir en Facebook', 'voxpopuli') }}">
    <svg class="size-6 fill-current" viewBox="0 0 24 24">
      <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
    </svg>
  </a>

  {{-- Twitter / X --}}
  <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}"
     target="_blank"
     rel="noopener noreferrer"
     class="btn btn-lg btn-circle fab-sub-button shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center tooltip tooltip-left"
     data-tip="{{ __('Twitter / X', 'voxpopuli') }}"
     aria-label="{{ __('Compartir en Twitter', 'voxpopuli') }}">
    <svg class="size-6 fill-current" viewBox="0 0 24 24">
      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
    </svg>
  </a>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const fab = document.querySelector('.fab');
    const trigger = fab ? fab.querySelector('[role="button"]') : null;
    const postContent = document.querySelector('.e-content');
    if (!fab || !trigger || !postContent) return;

    // Toggle share buttons on click
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      fab.classList.toggle('fab-active');
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!fab.contains(e.target)) {
        fab.classList.remove('fab-active');
        trigger.blur();
      }
    });

    // Copy link: read URL from data attribute (XSS-safe)
    const copyBtn = fab.querySelector('[data-copy-url]');
    if (copyBtn) {
      copyBtn.addEventListener('click', () => {
        const url = copyBtn.dataset.copyUrl;
        navigator.clipboard.writeText(url).then(() => {
          const originalHtml = copyBtn.innerHTML;
          copyBtn.classList.add('btn-success', 'text-success-content');
          copyBtn.innerHTML = `<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
          setTimeout(() => {
            copyBtn.classList.remove('btn-success', 'text-success-content');
            copyBtn.innerHTML = originalHtml;
          }, 2000);
        });
      });
    }

    // Scroll-aware show/hide — never touches fab-active
    let isPostFinished = false;
    const sentinel = postContent.lastElementChild;

    if (sentinel) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          isPostFinished = entry.isIntersecting;
          fab.classList.toggle('fab-hidden', isPostFinished);
        });
      }, {
        root: null,
        threshold: 0,
        rootMargin: '0px 0px -50px 0px'
      });

      observer.observe(sentinel);
    }

    let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    window.addEventListener('scroll', () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      const isScrollingUp = scrollTop < lastScrollTop;

      if (isPostFinished && !isScrollingUp) {
        fab.classList.add('fab-hidden');
      } else if (isScrollingUp) {
        fab.classList.remove('fab-hidden');
      }

      lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, { passive: true });
  });
</script>
