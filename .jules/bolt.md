## 2024-05-31 - [Pre-computed Post Meta for Performance]
**Learning:** In WordPress/Blade templates, dynamically calculating reading time using `str_word_count(strip_tags($content))` causes significant CPU bottlenecks, especially with large content blocks inside loops.
**Action:** Always fetch the pre-computed `vp_reading_time` post meta directly using `get_post_meta()`, falling back to dynamic calculation only if the meta does not exist.
