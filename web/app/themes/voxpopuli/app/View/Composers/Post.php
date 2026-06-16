<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Post extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.page-header',
        'partials.content',
        'partials.content-*',
    ];

    /**
     * Retrieve the post title.
     */
    public function title(): string
    {
        if ($this->view->name() !== 'partials.page-header') {
            return get_the_title();
        }

        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }

            return __('Latest Posts', 'voxpopuli');
        }

        if (is_archive()) {
            return get_the_archive_title();
        }

        if (is_search()) {
            return sprintf(
                /* translators: %s is replaced with the search query */
                __('Search Results for %s', 'voxpopuli'),
                get_search_query(false),
            );
        }

        if (is_404()) {
            return __('Not Found', 'voxpopuli');
        }

        return get_the_title();
    }

    /**
     * Retrieve the pagination links.
     */
    public function pagination(): string
    {
        return wp_link_pages([
            'echo' => 0,
            'before' => '<p>' . __('Pages:', 'voxpopuli'),
            'after' => '</p>',
        ]);
    }

    /**
     * Retrieve the featured image details.
     */
    public function featuredImage(): ?array
    {
        $id = get_post_thumbnail_id();
        if (!$id) {
            return null;
        }

        $src = wp_get_attachment_image_src($id, 'large');
        return [
            'url' => $src[0] ?? '',
            'width' => $src[1] ?? '',
            'height' => $src[2] ?? '',
            'alt' => get_post_meta($id, '_wp_attachment_image_alt', true) ?: get_the_title(),
            'caption' => wp_get_attachment_caption($id) ?: '',
        ];
    }

    /**
     * Retrieve the estimated reading time.
     */
    public function readingTime(): string
    {
        $post_id = get_the_ID();
        $minutes = get_post_meta($post_id, 'vp_reading_time', true);

        if (!$minutes) {
            $content = get_post_field('post_content', $post_id);
            $wordCount = str_word_count(strip_tags($content));
            $wordsPerMinute = 200; // Average reading speed
            $minutes = max(1, ceil($wordCount / $wordsPerMinute));
        }

        return sprintf(
            _n('%d min de lectura', '%d min de lectura', (int) $minutes, 'voxpopuli'),
            (int) $minutes,
        );
    }

    /**
     * Retrieve the primary category.
     */
    public function primaryCategory(): ?array
    {
        $categories = get_the_category();
        if (empty($categories)) {
            return null;
        }

        // Try Yoast Primary Category
        $primaryId = null;
        if (class_exists('WPSEO_Primary_Term')) {
            $wpseoPrimaryTerm = new \WPSEO_Primary_Term('category', get_the_ID());
            $primaryId = $wpseoPrimaryTerm->get_primary_term();
        }

        $category = $primaryId ? get_term($primaryId) : $categories[0];
        if (is_wp_error($category) || !$category) {
            $category = $categories[0];
        }

        return [
            'name' => $category->name,
            'link' => get_category_link($category->term_id),
            'slug' => $category->slug,
        ];
    }

    /**
     * Retrieve 2 suggested posts in the same category, excluding the current one.
     */
    public function suggestedPosts(): array
    {
        $categories = wp_get_post_categories(get_the_ID());
        if (empty($categories)) {
            return [];
        }

        $query = new \WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 2,
            'category__in' => $categories,
            'post__not_in' => [get_the_ID()],
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
        ]);

        $posts = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $imgId = get_post_thumbnail_id();
                $imgUrl = $imgId ? wp_get_attachment_image_url($imgId, 'medium') : '';
                $posts[] = [
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'date' => get_the_date(),
                    'image' => $imgUrl,
                    'category' => get_the_category()[0]->name ?? '',
                ];
            }
            wp_reset_postdata();
        }

        return $posts;
    }

    /**
     * Retrieve the latest featured (sticky) post, or latest post overall as fallback, excluding current post.
     */
    public function latestFeaturedPost(): ?array
    {
        $sticky = get_option('sticky_posts');
        $args = [
            'post_type' => 'post',
            'posts_per_page' => 1,
            'post__not_in' => [get_the_ID()],
            'no_found_rows' => true,
        ];

        if (!empty($sticky)) {
            $args['post__in'] = $sticky;
            $args['ignore_sticky_posts'] = 1;
        } else {
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
        }

        $query = new \WP_Query($args);
        $featured = null;

        if ($query->have_posts()) {
            $query->the_post();
            $imgId = get_post_thumbnail_id();
            $imgUrl = $imgId ? wp_get_attachment_image_url($imgId, 'large') : '';
            $featured = [
                'title' => get_the_title(),
                'link' => get_permalink(),
                'date' => get_the_date(),
                'excerpt' => get_the_excerpt(),
                'image' => $imgUrl,
                'category' => get_the_category()[0]->name ?? '',
            ];
            wp_reset_postdata();
        }

        return $featured;
    }
}
