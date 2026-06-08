## 2024-06-06 - Batch Prime Caches for Composer Data

**Learning:** When retrieving arrays of IDs (e.g., from `get_posts` with `'fields' => 'ids'`) to cache relationships in View Composers (like Sage 10's `Index.php`), mapping those IDs to `WP_Post` objects later using `array_map('get_post', $ids)` creates severe N+1 query bottlenecks if the posts aren't already in the WordPress object cache. This happens because `get_post` triggers individual database lookups per ID.

**Action:** Before looping or mapping over an array of IDs to inflate objects, always aggregate the IDs and bulk-fetch them into the cache using `_prime_post_caches($all_post_ids, true, true)`. This turns O(n) database queries into O(1) queries.

## 2024-06-08 - Presentation Layer N+1 Write Bottlenecks
**Learning:** Writing data to the database (e.g., `update_post_meta`) inside presentation layer components (like Blade templates or View Composers) causes severe N+1 query bottlenecks. If a page renders a list of 20 posts and none have the `vp_reading_time` cached, it triggers 40 unnecessary database `UPDATE` queries during a frontend read operation, crippling TTFB.
**Action:** Never perform database write operations inside the presentation layer. Compute values dynamically in memory for fallback scenarios, and ensure permanent data caching happens strictly in the backend (e.g., via `save_post` hooks) to maintain a pure, read-only presentation layer.
