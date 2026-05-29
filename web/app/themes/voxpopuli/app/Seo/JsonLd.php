<?php

namespace App\Seo;

/**
 * Builder for JSON-LD structured data schemas.
 *
 * Generates Organization, WebSite, Article, and BreadcrumbList
 * schemas as associative arrays ready for JSON serialization.
 */
class JsonLd
{
    /**
     * Build an Organization schema.
     *
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    public function organization(array $config): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $config['name'] ?? '',
            'url' => $config['url'] ?? '',
        ];

        if (! empty($config['logo'])) {
            $schema['logo'] = $config['logo'];
        }

        if (! empty($config['sameAs'])) {
            $schema['sameAs'] = $config['sameAs'];
        }

        return $schema;
    }

    /**
     * Build a WebSite schema with optional SearchAction.
     *
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    public function website(array $config): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $config['name'] ?? '',
            'url' => $config['url'] ?? '',
        ];

        if (! empty($config['search_url'])) {
            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $config['search_url'],
                ],
                'query-input' => 'required name=search_term_string',
            ];
        }

        return $schema;
    }

    /**
     * Build an Article schema.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function article(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['headline'] ?? '',
        ];

        if (! empty($data['description'])) {
            $schema['description'] = $data['description'];
        }

        if (! empty($data['datePublished'])) {
            $schema['datePublished'] = $data['datePublished'];
        }

        if (! empty($data['dateModified'])) {
            $schema['dateModified'] = $data['dateModified'];
        }

        if (! empty($data['author'])) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $data['author'],
            ];
        }

        if (! empty($data['image'])) {
            $schema['image'] = $data['image'];
        }

        if (! empty($data['url'])) {
            $schema['mainEntityOfPage'] = [
                '@type' => 'WebPage',
                '@id' => $data['url'],
            ];
        }

        return $schema;
    }

    /**
     * Build a BreadcrumbList schema.
     *
     * @param  array<int, array<string, string>>  $items  Array of ['name' => ..., 'url' => ...]
     * @return array<string, mixed>
     */
    public function breadcrumbList(array $items): array
    {
        $listElements = [];
        $position = 1;

        foreach ($items as $item) {
            $listElements[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $item['name'] ?? '',
                'item' => $item['url'] ?? '',
            ];
            $position++;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listElements,
        ];
    }

    /**
     * Render a schema array as a JSON-LD script tag.
     *
     * @param  array<string, mixed>  $schema
     */
    public static function toScript(array $schema): string
    {
        $json = json_encode(
            $schema,
            JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT
            | JSON_THROW_ON_ERROR,
        );

        return sprintf(
            '<script type="application/ld+json">%s</script>',
            $json,
        );
    }
}
