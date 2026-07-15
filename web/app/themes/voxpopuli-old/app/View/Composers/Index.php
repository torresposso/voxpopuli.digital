<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Index extends Composer
{
    protected static $views = [
        'index',
    ];

    public function with()
    {
        $sections = $this->getSections();
        $seen = [];
        $loadedSections = [];

        foreach ($sections as $section) {
            $posts = get_posts([
                'category_name' => $section['slug'],
                'posts_per_page' => 3,
                'post__not_in' => $seen,
                'no_found_rows' => true,
                'update_post_meta_cache' => true,
                'update_post_term_cache' => true,
            ]);
            $seen = array_merge($seen, wp_list_pluck($posts, 'ID'));
            $loadedSections[] = array_merge($section, ['posts' => $posts]);
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
