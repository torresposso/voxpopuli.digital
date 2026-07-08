/**
 * Video click-to-play: replaces thumbnail + play button with a YouTube iframe
 * on first click. No network request before the click (lazy iframe).
 *
 * @module video-feature
 */

export function init() {
  const containers = document.querySelectorAll('[data-video-container]');
  if (!containers.length) return;

  containers.forEach(function (container) {
    const playBtn = container.querySelector('[data-video-play]');
    if (!playBtn) return;

    function onClick() {
      const youtubeUrl = playBtn.getAttribute('data-youtube-url');
      if (!youtubeUrl) return;

      // Extract video ID from various YouTube URL formats
      var videoId = extractYouTubeId(youtubeUrl);
      if (!videoId) return;

      var iframe = document.createElement('iframe');
      iframe.src = 'https://www.youtube-nocookie.com/embed/' + videoId + '?autoplay=1';
      iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
      iframe.allowFullscreen = true;
      iframe.className = 'absolute inset-0 w-full h-full';
      iframe.title = 'YouTube video player';
      iframe.loading = 'eager';

      // Replace the container contents
      container.innerHTML = '';
      container.appendChild(iframe);

      // Clean up the event listener
      playBtn.removeEventListener('click', onClick);
    }

    playBtn.addEventListener('click', onClick);
  });
}

/**
 * Extract YouTube video ID from various URL formats.
 *
 * @param {string} url
 * @returns {string|null}
 */
function extractYouTubeId(url) {
  var patterns = [
    /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
    /^([a-zA-Z0-9_-]{11})$/,
  ];

  for (var i = 0; i < patterns.length; i++) {
    var match = url.match(patterns[i]);
    if (match) return match[1];
  }

  return null;
}

export function destroy() {
  // Nothing to clean up — event listeners and DOM are self-contained
}
