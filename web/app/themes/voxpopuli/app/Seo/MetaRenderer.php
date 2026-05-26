<?php

namespace App\Seo;

/**
 * Renders HTML meta tags from a SeoMeta instance.
 *
 * Outputs Open Graph, Twitter Card, meta description, canonical, and robots tags.
 */
class MetaRenderer
{
    private SeoMeta $seo;

    public function __construct(SeoMeta $seo)
    {
        $this->seo = $seo;
    }

    /**
     * Render all meta tags as an HTML string.
     */
    public function render(): string
    {
        $tags = [];

        // Open Graph tags
        if ($this->seo->getOgTitle() !== null) {
            $tags[] = $this->metaTag('property="og:title"', $this->seo->getOgTitle());
        }

        if ($this->seo->getOgDescription() !== null) {
            $tags[] = $this->metaTag('property="og:description"', $this->seo->getOgDescription());
        }

        if ($this->seo->getOgImageUrl() !== null) {
            $tags[] = $this->metaTag('property="og:image"', $this->seo->getOgImageUrl());
        }

        if ($this->seo->getPostUrl() !== null) {
            $tags[] = $this->metaTag('property="og:url"', $this->seo->getPostUrl());
        }

        $tags[] = $this->metaTag('property="og:type"', $this->seo->isHome() ? 'website' : 'article');
        $tags[] = $this->metaTag('property="og:site_name"', get_bloginfo('name'));
        $tags[] = $this->metaTag('property="og:locale"', get_locale());

        // Twitter Cards
        $tags[] = $this->metaTag('name="twitter:card"', 'summary_large_image');

        if ($this->seo->getOgTitle() !== null) {
            $tags[] = $this->metaTag('name="twitter:title"', $this->seo->getOgTitle());
        }

        if ($this->seo->getOgDescription() !== null) {
            $tags[] = $this->metaTag('name="twitter:description"', $this->seo->getOgDescription());
        }

        if ($this->seo->getOgImageUrl() !== null) {
            $tags[] = $this->metaTag('name="twitter:image"', $this->seo->getOgImageUrl());
        }

        // Meta description
        if ($this->seo->getMetaDescription() !== null) {
            $tags[] = $this->metaTag('name="description"', $this->seo->getMetaDescription());
        }

        // Canonical
        if ($this->seo->getCanonical() !== null) {
            $tags[] = sprintf('<link rel="canonical" href="%s">', $this->escape($this->seo->getCanonical()));
        }

        // Robots noindex
        if ($this->seo->getNoindex()) {
            $tags[] = $this->metaTag('name="robots"', 'noindex');
        }

        return implode("\n", $tags);
    }

    /**
     * Render a <meta> tag with the given attribute and content.
     */
    private function metaTag(string $attribute, string $content): string
    {
        return sprintf('<meta %s content="%s">', $attribute, $this->escape($content));
    }

    /**
     * Escape HTML attribute value.
     */
    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    }
}
