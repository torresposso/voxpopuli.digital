## 2024-06-25 - Avoid Dynamic Text Parsing in Templates
**Learning:** Using `str_word_count(strip_tags($content))` in Blade templates and View Composers causes severe CPU bottleneck on large content pages, performing slow operations during rendering. The `vp_reading_time` pre-computed post meta is designed exactly for this but was being ignored.
**Action:** Always fetch `vp_reading_time` post meta directly and only fallback to calculation if the meta does not exist.
