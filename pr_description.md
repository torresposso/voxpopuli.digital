🎯 What: Fixed a Cross-Site Scripting (XSS) vulnerability in `post-card.blade.php` and `featured-card.blade.php`.
⚠️ Risk: Unescaped rendering of post titles allowed malicious HTML/JavaScript to be injected and executed on the frontend when rendering post lists or grids. This is a severe Reflected/Stored XSS risk depending on how titles are updated.
🛡️ Solution: Swapped the raw Blade output syntax `{!! !!}` with the safe escaping syntax `{{ }}` for `get_the_title()` calls, ensuring the data is processed through `htmlspecialchars` before reaching the browser.
