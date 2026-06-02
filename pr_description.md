🎯 **What:**
Fixed a Reflected XSS vulnerability in `web/app/themes/voxpopuli/resources/views/search.blade.php`, and extended the fix to `drawer.blade.php`, `forms/search.blade.php`, and `partials/content-search-empty.blade.php`. These files were outputting user-provided search queries either unescaped (via Blade's `{!! !!}` tags) or double-escaping them unnecessarily.

⚠️ **Risk:**
If left unfixed, an attacker could craft a malicious URL with a Javascript payload in the `s` (search) parameter. If a user clicked this link, the Javascript would execute in their browser context, potentially leading to session hijacking, defacement, or redirection to malicious sites. The blast radius could impact any user who visits a crafted search link.

🛡️ **Solution:**
Changed the output of `get_search_query()` to use Blade's native safe output tags `{{ }}`. In order to prevent double-escaping (since `get_search_query()` returns an `esc_attr` escaped string by default), the parameter `false` is passed (`get_search_query(false)`), returning the raw query string which is then properly and safely escaped by `{{ }}`.
