## 2024-06-06 - Batch Prime Caches for Composer Data

**Learning:** When retrieving arrays of IDs (e.g., from `get_posts` with `'fields' => 'ids'`) to cache relationships in View Composers (like Sage 10's `Index.php`), mapping those IDs to `WP_Post` objects later using `array_map('get_post', $ids)` creates severe N+1 query bottlenecks if the posts aren't already in the WordPress object cache. This happens because `get_post` triggers individual database lookups per ID.

**Action:** Before looping or mapping over an array of IDs to inflate objects, always aggregate the IDs and bulk-fetch them into the cache using `_prime_post_caches($all_post_ids, true, true)`. This turns O(n) database queries into O(1) queries.

## 2024-08-09 - Avoid DB writes in Presentation Layer Fallbacks

**Learning:** When calculating fallbacks for missing metadata (like reading time or word count) in View Composers or Blade templates, saving the calculated result back to the database using `update_post_meta` inside the render loop causes severe N+1 database write bottlenecks during page loads. These writes block the main thread and significantly degrade Time to First Byte (TTFB) on archive pages where multiple posts are rendered. The data calculation and caching should be handled asynchronously or hooked into the backend (e.g., `save_post` action), not during frontend presentation.

**Action:** Never perform database write operations (e.g., `update_post_meta`) inside the presentation layer. Compute fallbacks dynamically in memory during the request, but rely on backend hooks (`save_post`) or CLI scripts to persist the data to the database.
