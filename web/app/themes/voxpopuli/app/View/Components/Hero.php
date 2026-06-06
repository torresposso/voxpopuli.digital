<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Roots\Acorn\View\Component;

class Hero extends Component
{
    /**
     * Featured posts fetched for the hero layout (exactly 3).
     *
     * @var array
     */
    public $featured_posts;

    /**
     * Latest posts fetched for the sidebar (exactly 5, excluding featured).
     *
     * @var array
     */
    public $latest_posts;

    /**
     * Create the component instance and fetch all required posts.
     */
    public function __construct()
    {
        $data = $this->getHeroData();
        $this->featured_posts = $data['featured'];
        $this->latest_posts = $data['latest'];
    }

    /**
     * Get the unified cache key for the hero component.
     */
    public static function getCacheKey(): string
    {
        if (defined('WP_ENV') && WP_ENV === 'development') {
            $hostHash = substr(md5($_SERVER['HTTP_HOST'] ?? 'default'), 0, 20);

            return "vp_hero_data_v2_{$hostHash}";
        }

        return 'voxpopuli_hero_data_cache_v2';
    }

    /**
     * Fetch and cache the featured and latest posts.
     *
     * @return array
     */
    protected function getHeroData()
    {
        $cacheKey = self::getCacheKey();

        // Try to retrieve cached hero data only in production to ensure instant feedback in development
        if (! defined('WP_ENV') || (WP_ENV !== 'development' && WP_ENV !== 'local')) {
            $cached = get_transient($cacheKey);
            if ($cached !== false && is_array($cached) && isset($cached['featured'], $cached['latest'])) {
                return $cached;
            }
        }

        // 1. Fetch exactly 4 posts from the 'destacadas' category
        $featured = get_posts([
            'post_type' => 'post',
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
            'category_name' => 'destacadas',
            'no_found_rows' => true,
        ]);

        // Fallback: If less than 4 posts, fill the rest with the latest general posts
        if (count($featured) < 4) {
            $excludeIds = array_map(fn ($p) => $p->ID, $featured);
            $fallbacks = get_posts([
                'post_type' => 'post',
                'posts_per_page' => 4 - count($featured),
                'orderby' => 'date',
                'order' => 'DESC',
                'post__not_in' => $excludeIds,
                'no_found_rows' => true,
            ]);
            $featured = array_merge($featured, $fallbacks);
        }

        // 2. Fetch exactly 5 latest posts, excluding featured IDs
        $featuredIds = array_map(fn ($p) => $p->ID, $featured);
        $latest = get_posts([
            'post_type' => 'post',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
            'post__not_in' => $featuredIds,
            'no_found_rows' => true,
        ]);

        // Helper function to map raw WP_Post objects into structured design tokens
        $process = function ($post) {
            // Filter categories to exclude 'destacadas' utility slug from visible badges
            $allCategories = wp_get_post_categories($post->ID, ['fields' => 'all']);
            $visibleCategory = 'Artículo';
            foreach ($allCategories as $cat) {
                if ($cat->slug !== 'destacadas') {
                    $visibleCategory = $cat->name;
                    break;
                }
            }

            // Clean, semantic excerpt extraction
            $excerpt = ! empty($post->post_excerpt)
                ? $post->post_excerpt
                : wp_trim_words(wp_strip_all_tags($post->post_content), 25);

            // Dynamic author resolution
            $authorId = get_post_field('post_author', $post->ID);
            $authorName = get_the_author_meta('display_name', $authorId) ?: 'Vox Redacción';

            return (object) [
                'id' => $post->ID,
                'title' => get_the_title($post),
                'excerpt' => $excerpt,
                'url' => get_permalink($post),
                'image' => get_the_post_thumbnail_url($post->ID, 'full') ?: '',
                'alt' => ($thumbnailId = get_post_thumbnail_id($post))
                    ? (get_post_meta($thumbnailId, '_wp_attachment_image_alt', true) ?: get_the_title($post))
                    : get_the_title($post),
                'date' => get_the_date('', $post),
                'category' => $visibleCategory,
                'author' => $authorName,
            ];
        };

        $processedFeatured = array_map($process, $featured);
        $processedLatest = array_map($process, $latest);

        $data = [
            'featured' => $processedFeatured,
            'latest' => $processedLatest,
        ];

        // Cache the processed data for 1 hour
        set_transient($cacheKey, $data, HOUR_IN_SECONDS);

        return $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return $this->view('components.hero');
    }
}
