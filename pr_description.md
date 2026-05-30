🎯 **What:**
Fixed improper escaping (XSS vulnerability) in the search form template. The `placeholder` and `value` attributes on the input element were using Blade's raw output tags (`{!! !!}`) instead of the safely escaped tags (`{{ }}`).

⚠️ **Risk:**
Cross-Site Scripting (XSS). An attacker could potentially craft a malicious search query that injects JavaScript into the `value` or `placeholder` attributes of the search input field. If executed, this script could steal user data, hijack sessions, or deface the website.

🛡️ **Solution:**
Replaced the raw output syntax (`{!! ... !!}`) with Blade's native escaping syntax (`{{ ... }}`) for both `esc_attr_x` and `get_search_query` functions within `web/app/themes/voxpopuli/resources/views/forms/search.blade.php`. This ensures the attribute values are properly HTML-encoded before rendering.
