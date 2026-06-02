⚡ Bolt: Optimize N+1 Query and Memory in Sitemap Generation

## 💡 What
Refactored the sitemap generation query in `SeoServiceProvider.php` to use a chunked batching strategy instead of fetching all full post objects at once.

## 🎯 Why
The original query fetched all posts with `'posts_per_page' => -1`, causing WordPress to hydrate every post object in memory simultaneously. This leads to massive RAM usage and triggers N+1 problems via `update_post_meta_cache` and `update_post_term_cache` for large datasets. Furthermore, not defining `no_found_rows` caused unnecessary SQL calculations.

## 📊 Impact
By fetching only IDs initially (`'fields' => 'ids'`) and avoiding meta/term cache updates, the initial load is extremely lightweight. Fetching posts in batches of 100 ensures predictable memory usage regardless of dataset size and allows WordPress to correctly optimize meta lookups sequentially.

## 🔬 Measurement
Although local benchmarking could not establish exact microtime differences due to absent sandbox setup, standard WordPress architecture guarantees a significant memory reduction and fewer query spikes for N > 500 records. Verified via PHP syntax linting.
