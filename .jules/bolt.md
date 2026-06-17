## 2024-06-06 - Batch Prime Caches for Composer Data

**Learning:** When retrieving arrays of IDs (e.g., from `get_posts` with `'fields' => 'ids'`) to cache relationships in View Composers (like Sage 10's `Index.php`), mapping those IDs to `WP_Post` objects later using `array_map('get_post', $ids)` creates severe N+1 query bottlenecks if the posts aren't already in the WordPress object cache. This happens because `get_post` triggers individual database lookups per ID.

**Action:** Before looping or mapping over an array of IDs to inflate objects, always aggregate the IDs and bulk-fetch them into the cache using `_prime_post_caches($all_post_ids, true, true)`. This turns O(n) database queries into O(1) queries.

## 2024-08-09 - Avoid DB writes in Presentation Layer Fallbacks

**Learning:** When calculating fallbacks for missing metadata (like reading time or word count) in View Composers or Blade templates, saving the calculated result back to the database using `update_post_meta` inside the render loop causes severe N+1 database write bottlenecks during page loads. These writes block the main thread and significantly degrade Time to First Byte (TTFB) on archive pages where multiple posts are rendered. The data calculation and caching should be handled asynchronously or hooked into the backend (e.g., `save_post` action), not during frontend presentation.

**Action:** Never perform database write operations (e.g., `update_post_meta`) inside the presentation layer. Compute fallbacks dynamically in memory during the request, but rely on backend hooks (`save_post`) or CLI scripts to persist the data to the database.

## 2024-08-11 - Batch Prime Caches for Sitemap Generation

**Learning:** When generating sitemaps or processing large numbers of posts sequentially in batches (where fields 'ids' were fetched initially), instantiating a secondary `WP_Query` inside the batch loop causes high memory and CPU overhead. Setting up the WP_Query class is heavy even when passing `post__in`.

**Action:** When you already have an array of post IDs chunked into batches, do not use a secondary `WP_Query` to loop through them. Instead, use `_prime_post_caches($batch, false, true)` to batch-load the post and meta caches in a single query, and iterate directly over the IDs using `foreach`. This eliminates WP_Query instantiation overhead and avoids N+1 queries.

## 2024-08-14 - Prefer get_posts() over new WP_Query() for unpaginated lists

**Learning:** Instantiating `new WP_Query()` for simple, unpaginated lists of posts (like "recent posts" fallbacks in Blade templates) introduces unnecessary overhead from setting up the full query object, main loop variables, and potentially executing `SQL_CALC_FOUND_ROWS` if not explicitly disabled.
**Action:** Always prefer `get_posts()` over `new WP_Query()` when fetching simple arrays of posts for presentation, as it inherently defaults to `'no_found_rows' => true` and `'suppress_filters' => true`, significantly reducing CPU and memory overhead.

## 2024-06-16 - Sitemap Generation Optimization
**Learning:** Calling `get_post_type($postId)` multiple times in a loop over post IDs can cause CPU and database overhead.
**Action:** Always assign the result of `get_post_type` or similar functions to a variable once per iteration and reuse that variable to prevent N+1 queries or redundant function calls.

## 2024-06-16 - [WP-CLI SEO Migration N+1 Bottlenecks]
**Learning:** `get_the_title()` and `get_the_excerpt()` are highly inefficient in bulk scripts because they trigger the entire WordPress filter cascade. Relying on `get_bloginfo('name')` inside a large loop also redundantly triggers options table checks, further exacerbating performance drops.
**Action:** Always fetch properties via `$post = get_post($postId);` leveraging primed object cache during migrations, avoiding filter cascades. Hoist static values like `get_bloginfo('name')` outside loops.

## 2024-06-17 - array_merge Bottlenecks in Composer Loops
**Learning:** Using `array_merge()` inside a `foreach` loop to aggregate elements from nested arrays (e.g., when collecting post IDs from multiple section arrays in a View Composer) causes an O(N^2) memory reallocation bottleneck in PHP. The array is duplicated and reallocated on every iteration, leading to exponential memory usage and slower execution times for larger datasets.
**Action:** Always use a nested `foreach` loop to append elements individually (`$array[] = $val;`) instead of `array_merge()` when aggregating data in a loop.
