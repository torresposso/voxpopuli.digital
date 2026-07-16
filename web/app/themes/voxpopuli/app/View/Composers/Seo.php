<?php

namespace App\View\Composers;

use App\Seo\JsonLd;
use App\Seo\MetaRenderer;
use App\Seo\SeoMeta;
use Roots\Acorn\View\Composer;

class Seo extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'layouts.app',
    ];

    /**
     * Provide SEO data to the view.
     *
     * @return array<string, mixed>
     */
    public function with(): array
    {
        $seoMeta = $this->resolveSeoMeta();

        return [
            'seoMetaTags' => $this->renderMetaTags($seoMeta),
            'seoJsonLd' => $this->renderJsonLd($seoMeta),
        ];
    }

    /**
     * Resolve SeoMeta for the current request.
     */
    private function resolveSeoMeta(): SeoMeta
    {
        $data = [];

        if (is_singular()) {
            $postId = get_queried_object_id();
            $data = $this->getSeoDataForPost($postId);
        } elseif (is_front_page() || is_home()) {
            $desc = get_bloginfo('description');
            if (empty($desc) || $desc === 'Just another WordPress site') {
                $desc = 'Periodismo independiente de investigación, análisis y opinión desde el Caribe colombiano. Rigor técnico y mirada progresista.';
            }
            $data = [
                'post_title' => get_bloginfo('name'),
                'post_url' => home_url('/'),
                'meta_desc' => $desc,
                'is_home' => true,
                'canonical' => home_url('/'),
            ];
        } elseif (is_archive()) {
            $data = [
                'post_title' => get_the_archive_title(),
                'post_url' => get_archive_link(get_query_var('post_type') ?: 'post', get_query_var('year'), get_query_var('monthnum'), get_query_var('day')),
                'meta_desc' => get_the_archive_description() ?: get_bloginfo('description'),
                'canonical' => home_url($_SERVER['REQUEST_URI'] ?? ''),
            ];
        } elseif (is_search()) {
            $query = get_search_query(false);
            $data = [
                'post_title' => sprintf(__('Resultados de búsqueda para: %s', 'voxpopuli'), $query),
                'post_url' => home_url('/?s=' . urlencode($query)),
                'meta_desc' => sprintf(__('Resultados de búsqueda en Vox Populi para: %s', 'voxpopuli'), $query),
                'canonical' => home_url('/?s=' . urlencode($query)),
            ];
        } elseif (is_404()) {
            $data = [
                'post_title' => __('Página no encontrada - 404', 'voxpopuli'),
                'post_url' => home_url($_SERVER['REQUEST_URI'] ?? ''),
                'meta_desc' => __('La página que buscas no existe o ha sido movida.', 'voxpopuli'),
                'noindex' => true,
            ];
        }

        return new SeoMeta($data, true);
    }

    /**
     * Get SEO data for a specific post.
     *
     * @return array<string, mixed>
     */
    private function getSeoDataForPost(int $postId): array
    {
        $post = get_post($postId);
        if (!$post) {
            return [];
        }

        $ogImageId = get_post_meta($postId, '_voxpopuli_og_image', true);

        if (empty($ogImageId)) {
            // ⚡ Bolt: Pass $post object instead of $postId to avoid redundant get_post() loopkups
            $ogImageId = get_post_thumbnail_id($post);
            $ogImageUrl = $ogImageId ? wp_get_attachment_url($ogImageId) : null;
        } else {
            $ogImageUrl = wp_get_attachment_url((int) $ogImageId);
        }

        return [
            'meta_desc' => get_post_meta($postId, '_voxpopuli_meta_desc', true) ?: null,
            'og_title' => get_post_meta($postId, '_voxpopuli_og_title', true) ?: null,
            'og_desc' => get_post_meta($postId, '_voxpopuli_og_desc', true) ?: null,
            'og_image_id' => $ogImageId ?: null,
            'og_image_url' => $ogImageUrl ?: null,
            'noindex' => get_post_meta($postId, '_voxpopuli_noindex', true) === '1',
            'canonical' => get_post_meta($postId, '_voxpopuli_canonical', true) ?: null,
            // ⚡ Bolt: Replaced function overhead and multiple get_post() DB calls by directly passing the $post object or raw properties
            'post_title' => get_the_title($post),
            'post_url' => get_permalink($post),
            'post_type' => $post->post_type,
            'date_published' => get_the_date('c', $post),
            'date_modified' => get_the_modified_date('c', $post),
            'author_name' => get_the_author_meta('display_name', $post->post_author),
        ];
    }

    /**
     * Render HTML meta tags.
     */
    private function renderMetaTags(SeoMeta $seoMeta): string
    {
        $renderer = new MetaRenderer($seoMeta);

        return $renderer->render();
    }

    /**
     * Render JSON-LD scripts.
     */
    private function renderJsonLd(SeoMeta $seoMeta): string
    {
        $jsonld = app(JsonLd::class);
        $graph = [];

        $siteUrl = home_url();
        $siteName = get_bloginfo('name');

        // Organization
        $orgConfig = apply_filters('voxpopuli/seo/organization', [
            'name' => $siteName,
            'logo' => get_theme_mod('custom_logo')
                ? wp_get_attachment_url(get_theme_mod('custom_logo'))
                : null,
            'url' => $siteUrl,
            'sameAs' => [],
        ]);

        $org = $jsonld->organization($orgConfig);
        unset($org['@context']);
        $graph[] = $org;

        // WebSite
        $siteConfig = apply_filters('voxpopuli/seo/social', [
            'name' => $siteName,
            'url' => $siteUrl,
            'search_url' => $siteUrl . '/?s={search_term_string}',
        ]);

        $site = $jsonld->website($siteConfig);
        unset($site['@context']);
        $graph[] = $site;

        // Article (only on single posts)
        if (is_singular('post') && ! $seoMeta->isHome()) {
            $articleData = [
                'headline' => $seoMeta->getPostTitle() ?? '',
                'description' => $seoMeta->getMetaDescription() ?? $seoMeta->getOgDescription() ?? '',
                'datePublished' => $seoMeta->getDatePublished() ?? '',
                'dateModified' => $seoMeta->getDateModified() ?? '',
                'author' => $seoMeta->getAuthorName() ?? '',
                'image' => $seoMeta->getOgImageUrl() ?? '',
                'url' => $seoMeta->getPostUrl() ?? '',
            ];

            $article = $jsonld->article($articleData);
            unset($article['@context']);
            $graph[] = $article;
        }

        // BreadcrumbList
        $breadcrumbs = $this->buildBreadcrumbs($seoMeta);
        $breadcrumbList = $jsonld->breadcrumbList($breadcrumbs);
        unset($breadcrumbList['@context']);
        $graph[] = $breadcrumbList;

        $combined = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];

        return JsonLd::toScript($combined);
    }

    /**
     * Build breadcrumb items.
     *
     * @return array<int, array<string, string>>
     */
    private function buildBreadcrumbs(SeoMeta $seoMeta): array
    {
        $items = [
            ['name' => 'Inicio', 'url' => home_url()],
        ];

        if (! $seoMeta->isHome() && is_singular()) {
            $categories = get_the_category(get_queried_object_id());

            if (! empty($categories)) {
                $category = $categories[0];
                $items[] = [
                    'name' => $category->name,
                    'url' => get_category_link($category->term_id),
                ];
            }

            $items[] = [
                'name' => $seoMeta->getPostTitle() ?? 'Artículo',
                'url' => (string) ($seoMeta->getPostUrl() ?? home_url()),
            ];
        }

        return $items;
    }
}
