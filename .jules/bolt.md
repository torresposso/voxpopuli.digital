## 2024-05-24 - Pre-computed Reading Time
**Learning:** The `voxpopuli-core` plugin pre-computes and stores `vp_reading_time` as post metadata on save. Dynamically parsing large post content on every card render with `str_word_count(strip_tags())` causes unnecessary CPU overhead, especially on pages with many posts.
**Action:** Always prefer `get_post_meta($postId, 'vp_reading_time', true)` over calculating word counts on the fly, keeping the dynamic calculation only as a fallback.
