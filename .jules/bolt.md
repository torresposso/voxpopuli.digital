## 2026-06-02 - [Word Count & Reading Time Save Post Hook]
**Learning:** Calculating word count and reading time dynamically in `the_content` or blade template loops is expensive. Implementing a "lazy cache" approach by computing these values during `save_post` and storing them in `postmeta` (and providing a fallback update on read) drastically cuts down CPU usage in loops.
**Action:** When adding `save_post` hooks in WordPress (e.g., in `setup.php`), always include guard clauses for `DOING_AUTOSAVE` and `wp_is_post_revision()` to prevent redundant calculations and incorrect metadata updates during auto-saves or revision creations.
## 2026-06-02 - [Redundant no_found_rows in get_posts]
**Learning:** While using `'no_found_rows' => true` is an important optimization for direct `WP_Query` usages, the WordPress core function `get_posts()` unconditionally sets `'no_found_rows' = true` internally before running the query. Explicitly passing it to `get_posts` is redundant and provides no performance benefit.
**Action:** Only add `'no_found_rows' => true` to explicit `new WP_Query()` instantiations, never to `get_posts()`.

## 2026-06-02 - [Avoid count() on WP_Query->posts]
**Learning:** Using `count($query->posts)` requires an array traversal in PHP. The `WP_Query` object already computes and stores this exact count as an integer in its `$query->post_count` property during the query execution loop.
**Action:** Always prefer retrieving the integer property `$query->post_count` over calling the `count()` function on the array to avoid redundant CPU cycles, especially on larger bulk queries.
