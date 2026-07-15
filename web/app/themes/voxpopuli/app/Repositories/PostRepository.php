<?php

namespace App\Repositories;

/**
 * Repository for WordPress post queries.
 *
 * Centralizes query logic that was previously duplicated across
 * view composers and components. Provides a seam for testability.
 *
 * Page-level caching is handled by WP Super Cache.
 */
class PostRepository
{
    /**
     * Find featured posts from the 'destacadas' category, with fallback
     * to the latest posts if not enough featured posts exist.
     *
     * @return \WP_Post[]
     */
    public function findFeatured(int $count = 4): array
    {
        $featured = get_posts([
            'post_type' => 'post',
            'posts_per_page' => $count,
            'orderby' => 'date',
            'order' => 'DESC',
            'category_name' => 'destacadas',
            'no_found_rows' => true,
        ]);

        // Fallback: fill remaining slots with latest posts
        if (count($featured) < $count) {
            $excludeIds = wp_list_pluck($featured, 'ID');
            $fallbacks = get_posts([
                'post_type' => 'post',
                'posts_per_page' => $count - count($featured),
                'orderby' => 'date',
                'order' => 'DESC',
                'post__not_in' => $excludeIds,
                'no_found_rows' => true,
            ]);
            $featured = array_merge($featured, $fallbacks);
        }

        return $featured;
    }

    /**
     * Find the latest posts, excluding specific IDs.
     *
     * @param  int[]  $exclude
     * @return \WP_Post[]
     */
    public function findLatest(int $count = 5, array $exclude = []): array
    {
        return get_posts([
            'post_type' => 'post',
            'posts_per_page' => $count,
            'orderby' => 'date',
            'order' => 'DESC',
            'post__not_in' => $exclude,
            'no_found_rows' => true,
        ]);
    }

    /**
     * Find posts for a specific section/category slug.
     *
     * @param  int[]  $exclude
     * @return \WP_Post[]
     */
    public function findForSection(string $slug, int $count = 3, array $exclude = []): array
    {
        return get_posts([
            'category_name' => $slug,
            'posts_per_page' => $count,
            'post__not_in' => $exclude,
            'no_found_rows' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ]);
    }

    /**
     * Find recent posts for the front page layout.
     *
     * @return array{hero: \WP_Post|null, featured: \WP_Post|null, rail: \WP_Post[], ids: int[]}
     */
    public function findFrontPage(int $total = 8): array
    {
        $posts = get_posts([
            'post_type' => 'post',
            'posts_per_page' => $total,
            'post_status' => 'publish',
            'no_found_rows' => true,
        ]);

        return [
            'hero' => $posts[0] ?? null,
            'featured' => $posts[1] ?? null,
            'rail' => array_slice($posts, 2),
            'ids' => wp_list_pluck($posts, 'ID'),
        ];
    }

    /**
     * Find posts from multiple category slugs (OR).
     *
     * @param  string[] $slugs
     * @param  int[]    $exclude
     * @return \WP_Post[]
     */
    public function findFromCategories(array $slugs, int $count = 3, array $exclude = []): array
    {
        return get_posts([
            'post_type' => 'post',
            'posts_per_page' => $count,
            'category_name' => implode(', ', $slugs),
            'post__not_in' => $exclude,
            'no_found_rows' => true,
        ]);
    }

    /**
     * Find a single editor's pick (sticky post, or latest from a priority category).
     *
     * @param  string[] $prioritySlugs
     * @param  int[]    $exclude
     * @return \WP_Post|null
     */
    public function findEditorPick(array $prioritySlugs = ['analisis', 'investigacion'], array $exclude = []): ?\WP_Post
    {
        $sticky = get_option('sticky_posts');
        if (! empty($sticky)) {
            $pick = get_posts([
                'post__in' => array_diff($sticky, $exclude),
                'posts_per_page' => 1,
                'no_found_rows' => true,
            ]);
            if (! empty($pick)) {
                return $pick[0];
            }
        }

        // Fallback: latest from priority slugs
        $pick = get_posts([
            'category_name' => implode(', ', $prioritySlugs),
            'posts_per_page' => 1,
            'post__not_in' => $exclude,
            'no_found_rows' => true,
        ]);

        return $pick[0] ?? null;
    }
}
