## 2024-06-06 - Batch Prime Caches for Composer Data

**Learning:** When retrieving arrays of IDs (e.g., from `get_posts` with `'fields' => 'ids'`) to cache relationships in View Composers (like Sage 10's `Index.php`), mapping those IDs to `WP_Post` objects later using `array_map('get_post', $ids)` creates severe N+1 query bottlenecks if the posts aren't already in the WordPress object cache. This happens because `get_post` triggers individual database lookups per ID.

**Action:** Before looping or mapping over an array of IDs to inflate objects, always aggregate the IDs and bulk-fetch them into the cache using `_prime_post_caches($all_post_ids, true, true)`. This turns O(n) database queries into O(1) queries.
## 2026-06-07 - Prevent Database Writes in Frontend Render Loop
**Learning:** Executing database writes (like `update_post_meta`) during frontend rendering processes (e.g., Blade components or View Composers) introduces a severe N+1 performance bottleneck. This slows down Time to First Byte (TTFB), triggers unnecessary database transactions per item rendered (e.g. lists of posts), and fails to improve backend cache since frontend data might not be pre-loaded before sorting logic evaluates it.
**Action:** When computing fallback metadata (like reading time) in the presentation layer, dynamically calculate and pass the value to the view, but absolutely avoid saving it to the database with `update_post_meta`. Rely on dedicated backend hooks (like `save_post`) for persistent database updates.
