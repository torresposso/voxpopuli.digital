<?php

namespace App\View\Composers;

use App\Repositories\PostRepository;
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

    private PostRepository $posts;

    /**
     * Create a new composer instance.
     */
    public function __construct(?PostRepository $posts = null)
    {
        $this->posts = $posts ?? new PostRepository();
    }

    /**
     * Bind data to the front-page view.
     *
     * @return array
     */
    public function with()
    {
        // Core sections: hero + LATEST
        $result = $this->posts->findFrontPage(8);
        $usedIds = $result['ids'];

        $hero = $result['hero'] ? $this->process($result['hero']) : null;
        $featured = $result['featured'] ? $this->process($result['featured']) : null;
        $rail = array_map([$this, 'process'], $result['rail']);

        // Investigation section
        $investigacion = array_map([$this, 'process'],
            $this->posts->findForSection('investigacion', 3, $usedIds));

        // Territories section
        $territorios = array_map([$this, 'process'],
            $this->posts->findForSection('territorios', 3, $usedIds));

        // Multimedia section (videos + podcast)
        $multimedia = array_map([$this, 'process'],
            $this->posts->findFromCategories(['videos', 'podcast'], 3, $usedIds));

        // Editor's pick
        $editorRaw = $this->posts->findEditorPick(['analisis', 'investigacion'], $usedIds);
        $editorPick = $editorRaw ? $this->process($editorRaw) : null;

        // Essential reads (lo más reciente, excluyendo todo lo ya usado)
        $esencialesRaw = $this->posts->findLatest(5, $usedIds);
        $esenciales = array_map([$this, 'process'], $esencialesRaw);

        return compact(
            'hero', 'featured', 'rail',
            'investigacion', 'territorios',
            'multimedia', 'editorPick',
            'esenciales'
        );
    }

    /**
     * Transform a WP_Post into a structured design token.
     */
    private function process($post)
    {
        $categories = get_the_category($post->ID);
        $visible = null;
        foreach ($categories as $cat) {
            if ($cat->slug !== 'destacadas') {
                $visible = $cat->name;
                break;
            }
        }

        $thumbnailId = get_post_thumbnail_id($post->ID);

        return (object) [
            'id'       => $post->ID,
            'title'    => get_the_title($post),
            'excerpt'  => get_the_excerpt($post),
            'url'      => get_permalink($post),
            'image'    => get_the_post_thumbnail_url($post->ID, 'large') ?: '',
            'alt'      => $thumbnailId
                ? (get_post_meta($thumbnailId, '_wp_attachment_image_alt', true) ?: get_the_title($post))
                : get_the_title($post),
            'date'     => get_the_date('', $post),
            'category' => $visible ?? __('Investigación', 'voxpopuli'),
            'author'   => get_the_author_meta('display_name', $post->post_author),
        ];
    }
}
