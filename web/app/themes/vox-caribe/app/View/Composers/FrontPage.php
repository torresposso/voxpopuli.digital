<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class FrontPage extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'front-page',
    ];

    /**
     * Data to be passed to view.
     *
     * @return array
     */
    public function with()
    {
        $excludedIds = [];

        return [
            'hero' => $this->getHero($excludedIds),
            'storyGrid' => $this->getStoryGrid($excludedIds),
            'archivePosts' => $this->getArchivePosts($excludedIds),
            'latestPosts' => $this->getLatestPosts($excludedIds),
            'popularPosts' => $this->getPopularPosts(),
            'podcasts' => [], // Placeholder for future podcast content
        ];
    }

    /**
     * Get the sticky/featured post for the hero section.
     *
     * @param array &$excludedIds Accumulator of post IDs to exclude from subsequent queries.
     * @return \WP_Post|null
     */
    protected function getHero(array &$excludedIds): ?\WP_Post
    {
        $sticky = get_option('sticky_posts');

        if (empty($sticky)) {
            // Fallback: most recent published post
            $query = new \WP_Query([
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'ignore_sticky_posts' => true,
            ]);

            if ($query->have_posts()) {
                $post = $query->posts[0];
                $excludedIds[] = $post->ID;
                return $post;
            }

            return null;
        }

        $heroId = $sticky[0];
        $excludedIds[] = $heroId;

        return get_post($heroId);
    }

    /**
     * Get the story grid posts (4 cols × 2 rows = 8 posts).
     * Excludes the hero post and ensures all posts have thumbnails.
     *
     * @param array &$excludedIds Accumulator of post IDs to exclude.
     * @return array<int, \WP_Post>
     */
    protected function getStoryGrid(array &$excludedIds): array
    {
        $posts = new \WP_Query([
            'posts_per_page' => 8,
            'post_status' => 'publish',
            'post__not_in' => $excludedIds,
            'ignore_sticky_posts' => true,
            'meta_query' => [
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS',
                ],
            ],
        ]);

        $gridPosts = $posts->posts;

        foreach ($gridPosts as $post) {
            $excludedIds[] = $post->ID;
        }

        return $gridPosts;
    }

    /**
     * Get archive posts: 3 posts from the same month in previous years.
     * Excludes already shown posts.
     *
     * @param array &$excludedIds Accumulator of post IDs to exclude.
     * @return array<int, \WP_Post>
     */
    protected function getArchivePosts(array &$excludedIds): array
    {
        $currentMonth = (int) current_time('n');
        $currentYear = (int) current_time('Y');

        $posts = new \WP_Query([
            'posts_per_page' => 3,
            'post_status' => 'publish',
            'post__not_in' => $excludedIds,
            'ignore_sticky_posts' => true,
            'date_query' => [
                [
                    'month' => $currentMonth,
                    'year' => $currentYear - 1,
                    'compare' => '<=',
                ],
                [
                    'month' => $currentMonth,
                ],
            ],
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        $archivePosts = $posts->posts;

        foreach ($archivePosts as $post) {
            $excludedIds[] = $post->ID;
        }

        // If we got fewer than 3, relax constraint: go further back
        if (count($archivePosts) < 3) {
            $remaining = 3 - count($archivePosts);
            $morePosts = new \WP_Query([
                'posts_per_page' => $remaining,
                'post_status' => 'publish',
                'post__not_in' => $excludedIds,
                'ignore_sticky_posts' => true,
                'date_query' => [
                    [
                        'month' => $currentMonth,
                    ],
                ],
                'orderby' => 'date',
                'order' => 'DESC',
            ]);

            foreach ($morePosts->posts as $post) {
                $archivePosts[] = $post;
                $excludedIds[] = $post->ID;
            }
        }

        return $archivePosts;
    }

    /**
     * Get the latest posts for the timeline section.
     *
     * @param array &$excludedIds Accumulator of post IDs to exclude.
     * @return array<int, \WP_Post>
     */
    protected function getLatestPosts(array &$excludedIds): array
    {
        $posts = new \WP_Query([
            'posts_per_page' => 4,
            'post_status' => 'publish',
            'post__not_in' => $excludedIds,
            'ignore_sticky_posts' => true,
        ]);

        return $posts->posts;
    }

    /**
     * Get popular posts via WP Popular Posts plugin.
     * Falls back to most commented posts if plugin is not active.
     *
     * @return array
     */
    protected function getPopularPosts(): array
    {
        // Try WP Popular Posts plugin first
        if (function_exists('wpp_get_mostpopular')) {
            $args = [
                'limit' => 5,
                'range' => 'last30days',
                'order_by' => 'views',
                'post_type' => 'post',
                'freshness' => true,
            ];

            // wpp_get_mostpopular echoes by default; capture with output buffering
            ob_start();
            wpp_get_mostpopular($args);
            $output = ob_get_clean();

            // Parse the HTML output into structured data
            // WPP returns HTML; we extract post IDs to build a proper array
            preg_match_all('/class="wpp-post-title"[^>]*href="([^"]*)"[^>]*>(.*?)<\/a>/s', $output, $matches);

            if (! empty($matches[1])) {
                $posts = [];
                foreach ($matches[1] as $url) {
                    $postId = url_to_postid($url);
                    if ($postId) {
                        $posts[] = get_post($postId);
                    }
                }
                return $posts;
            }
        }

        // Fallback: most commented posts in last 30 days
        $posts = new \WP_Query([
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'orderby' => 'comment_count',
            'order' => 'DESC',
            'date_query' => [
                [
                    'after' => '30 days ago',
                ],
            ],
        ]);

        return $posts->posts;
    }
}
