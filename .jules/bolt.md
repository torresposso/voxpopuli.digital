## 2024-05-31 - [Pre-computed Post Meta for Performance]
**Learning:** In WordPress/Blade templates, dynamically calculating reading time using `str_word_count(strip_tags($content))` causes significant CPU bottlenecks, especially with large content blocks inside loops.
**Action:** Always fetch the pre-computed `vp_reading_time` post meta directly using `get_post_meta()`, falling back to dynamic calculation only if the meta does not exist.

## 2026-06-02 - [WP_Query no_found_rows & Blade XSS Escaping]
**Learning:** Found two common codebase-specific performance and security antipatterns in Vox Populi:
1) Running `WP_Query` loops for sticky, suggested, or sitemap posts without pagination executes an expensive `SQL_CALC_FOUND_ROWS` in SQLite/MySQL, creating CPU overhead.
2) Blade raw output syntax `{!! !!}` was used for placeholders and input values, introducing reflected/stored XSS injection vectors.
**Action:** Always add `'no_found_rows' => true` to queries that don't need pagination. Never use `{!! !!}` raw tags for user input parameters or normal HTML tag attributes—always escape using native double curly braces `{{ }}`.

