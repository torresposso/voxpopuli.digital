<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Index extends Composer
{
    protected static $views = [
        'index',
    ];

    /**
     * Safely and surgically bust the composer cache without side effects.
     *
     * @return void
     */
    public function bustCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('voxpopuli_homepage_sections_ids');
    }

    public function with()
    {
        $sections = $this->getSections();

        $loadedSections = \Illuminate\Support\Facades\Cache::remember('voxpopuli_homepage_sections_ids', 3600, function () use ($sections) {
            $seen = [];
            $res = [];
            foreach ($sections as $section) {
                $post_ids = get_posts([
                    'category_name' => $section['slug'],
                    'posts_per_page' => 3,
                    'post__not_in' => $seen,
                    'fields' => 'ids',
                    'no_found_rows' => true,
                ]);
                $seen = array_merge($seen, $post_ids);
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
            ['slug' => 'investigacion','name' => 'Investigación',  'desc' => 'Reportajes de investigación con fuentes documentales y testimonios.',  'icon' => '🔍'],
            ['slug' => 'opinion',      'name' => 'Opinión',       'desc' => 'Columnas firmadas con postura explícita y análisis crítico.',          'icon' => '✍️'],
            ['slug' => 'deportes',     'name' => 'Deportes',      'desc' => 'Sociología, política y cultura del fenómeno deportivo caribeño.',     'icon' => '🏃'],
            ['slug' => 'ahora',        'name' => 'Ahora',         'desc' => 'Noticias de última hora y flashes de actualización rápida.',          'icon' => '⚡'],
        ];
    }
}
