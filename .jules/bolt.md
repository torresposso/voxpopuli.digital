## 2024-06-16 - Sitemap Generation Optimization
**Learning:** Calling `get_post_type($postId)` multiple times in a loop over post IDs can cause CPU and database overhead.
**Action:** Always assign the result of `get_post_type` or similar functions to a variable once per iteration and reuse that variable to prevent N+1 queries or redundant function calls.
