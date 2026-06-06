## 2024-06-06 - Batch Prime Caches for Composer Data

**Learning:** When retrieving arrays of IDs (e.g., from `get_posts` with `'fields' => 'ids'`) to cache relationships in View Composers (like Sage 10's `Index.php`), mapping those IDs to `WP_Post` objects later using `array_map('get_post', $ids)` creates severe N+1 query bottlenecks if the posts aren't already in the WordPress object cache. This happens because `get_post` triggers individual database lookups per ID.

**Action:** Before looping or mapping over an array of IDs to inflate objects, always aggregate the IDs and bulk-fetch them into the cache using `_prime_post_caches($all_post_ids, true, true)`. This turns O(n) database queries into O(1) queries.
