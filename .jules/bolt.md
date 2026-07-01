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

## 2024-11-20 - [Redundant function calls causing performance bottleneck]
**Learning:** `get_the_title()` can trigger multiple filter cascades when called repeatedly for the same post in components or composers, impacting performance. Additionally, performing variable assignment inside a ternary operation like `($thumbnailId = get_post_thumbnail_id($post))` makes code harder to read.
**Action:** Extract expensive calls like `get_the_title($post)` and `get_post_thumbnail_id($post)` into local variables before their usage, especially when they are needed multiple times or used within complex ternary operations, to prevent redundant filter executions and DB lookups.

## 2024-11-21 - Bypass Filter Cascades in Bulk Operations

**Learning:** When processing many WordPress posts in loops (like sitemap generation), using standard formatting functions such as `get_the_modified_date()` introduces significant CPU and database overhead because it applies the full WordPress filter cascade on every iteration. Calling functions like `get_permalink($postId)` or `get_post_type($postId)` also redundantly re-fetches the post from the cache internally.
**Action:** When iterating over a batch of IDs that have had their caches primed, always fetch the raw `$post = get_post($postId)` object. Then, pass that `$post` object to functions like `get_permalink($post)`, and access properties like `$post->post_modified` directly instead of using formatting functions, completely bypassing expensive filter cascades.

## 2024-11-21 - Prevent Cache Stampedes During Autosaves

**Learning:** View Composer and Component caching strategies can be silently undermined by WordPress background autosaves and revision creations if `save_post` invalidation hooks are left un-guarded. This causes continuous cache destruction on production while editors draft content, negating the performance benefits of caching.
**Action:** Always add guard clauses for `DOING_AUTOSAVE` and `wp_is_post_revision()` inside `save_post` hooks when used for cache invalidation or metadata updates to prevent redundant processing and cache stampedes.
