## 2025-02-12 - Database & Memory Allocation Optimizations
**Learning:** Found two common codebase-specific performance antipatterns in Vox Populi:
1) Fetching `WP_Query` loops without pagination triggered an expensive `SQL_CALC_FOUND_ROWS` computation in the database.
2) `str_word_count(strip_tags())` was being dynamically run to calculate reading time, even though `VoxPopuli\Core\ContentOptimizer` pre-computes it and saves it to `vp_reading_time` metadata.
**Action:** When querying posts without needing pagination, always add `'no_found_rows' => true`. For calculations related to content metrics like reading time or word count, always check for the pre-computed `vp_reading_time` and `vp_word_count` post metadata before dynamically parsing string content.
