<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Cache;
use Roots\Acorn\View\Composer;

class Index extends Composer
{
    protected static $views = [
        'index',
    ];

    /**
     * Safely and surgically bust the composer cache without side effects.
     */
    public function bustCache(): void
    {
        Cache::forget('voxpopuli_homepage_sections_ids');
    }

    public function with()
    {
        $sections = $this->getSections();

        $loadedSections = Cache::remember('voxpopuli_homepage_sections_ids', 3600, function () use ($sections) {
            $seen = [];
            $res = [];

            // Optimization: Fetch all recent posts for these categories in one query to avoid N+1 queries.
            $slugs = array_column($sections, 'slug');
            $all_posts = get_posts([
                'category_name' => implode(',', $slugs),
                'posts_per_page' => 100, // Buffer to ensure we have enough per category
                'fields' => 'ids',
                'no_found_rows' => true,
            ]);

            // Pre-fetch terms efficiently for mapping
            $post_terms = wp_get_object_terms($all_posts, 'category', ['fields' => 'all_with_object_id']);

            $posts_by_slug = [];
            if (! is_wp_error($post_terms)) {
                foreach ($post_terms as $term) {
                    if (in_array($term->slug, $slugs, true)) {
                        $posts_by_slug[$term->slug][] = $term->object_id;
                    }
                }
            }

            foreach ($sections as $section) {
                $post_ids = [];
                $slug = $section['slug'];

                // Iterate through the original sorted $all_posts to maintain chronological order
                foreach ($all_posts as $id) {
                    // Check if this post belongs to the current category
                    if (! empty($posts_by_slug[$slug]) && in_array($id, $posts_by_slug[$slug], true)) {
                        if (! in_array($id, $seen, true)) {
                            $post_ids[] = $id;
                            $seen[] = $id;
                            if (count($post_ids) >= 3) {
                                break;
                            }
                        }
                    }
                }

                // Fallback for missing posts if the bulk query was dominated by other categories
                if (count($post_ids) < 3) {
                    $fallback_posts = get_posts([
                        'category_name' => $slug,
                        'posts_per_page' => 3 - count($post_ids),
                        'post__not_in' => $seen,
                        'fields' => 'ids',
                        'no_found_rows' => true,
                    ]);

                    foreach ($fallback_posts as $f_id) {
                        $post_ids[] = $f_id;
                        $seen[] = $f_id;
                    }
                }

                $res[] = array_merge($section, ['post_ids' => $post_ids]);
            }

            return $res;
        });

        // Map post_ids to WP_Post objects
        foreach ($loadedSections as &$section) {
            $section['posts'] = array_filter(array_map('get_post', $section['post_ids']));
        }

        return [
            'sections' => $loadedSections,
        ];
    }

    public function getSections()
    {
        return [
            ['slug' => 'analisis',     'name' => 'Análisis',      'desc' => 'Lectura profunda de la coyuntura política, económica y social.',       'icon' => '📊'],
            ['slug' => 'investigacion', 'name' => 'Investigación',  'desc' => 'Reportajes de investigación con fuentes documentales y testimonios.',  'icon' => '🔍'],
            ['slug' => 'opinion',      'name' => 'Opinión',       'desc' => 'Columnas firmadas con postura explícita y análisis crítico.',          'icon' => '✍️'],
            ['slug' => 'deportes',     'name' => 'Deportes',      'desc' => 'Sociología, política y cultura del fenómeno deportivo caribeño.',     'icon' => '🏃'],
            ['slug' => 'ahora',        'name' => 'Ahora',         'desc' => 'Noticias de última hora y flashes de actualización rápida.',          'icon' => '⚡'],
        ];
    }
}
