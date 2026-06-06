<?php

namespace App\Seo;

/**
 * Virtual XML sitemap generator.
 *
 * Generates sitemap XML from an array of URL entries.
 * No file is written — the XML is generated on-the-fly.
 */
class Sitemap
{
    /**
     * URL entries for the sitemap.
     *
     * @var array<int, array<string, string>>
     */
    private array $entries;

    /**
     * @param  array<int, array<string, string>>  $entries  Sitemap URL entries
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Generate the sitemap XML.
     */
    public function toXml(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= "\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->entries as $entry) {
            $loc = $this->escape($entry['loc'] ?? '');
            $lastmod = $entry['lastmod'] ?? '';
            $priority = $entry['priority'] ?? $this->defaultPriority($entry['type'] ?? 'post');
            $changefreq = $entry['changefreq'] ?? $this->defaultChangefreq($entry['type'] ?? 'post');

            $xml .= "\n  <url>";
            $xml .= "\n    <loc>{$loc}</loc>";
            $xml .= "\n    <lastmod>{$lastmod}</lastmod>";
            $xml .= "\n    <priority>{$priority}</priority>";
            $xml .= "\n    <changefreq>{$changefreq}</changefreq>";
            $xml .= "\n  </url>";
        }

        $xml .= "\n</urlset>";

        return $xml;
    }

    /**
     * Get the Last-Modified timestamp from the most recent entry.
     */
    public function getLastModified(): ?string
    {
        $dates = array_filter(array_column($this->entries, 'lastmod'));

        if (empty($dates)) {
            return null;
        }

        return max($dates);
    }

    /**
     * Get the Content-Type header value.
     */
    public function getContentType(): string
    {
        return 'application/xml';
    }

    /**
     * Get the Cache-Control header value.
     */
    public function getCacheControl(): string
    {
        return 'public, max-age=3600';
    }

    /**
     * Default priority for post type.
     */
    private function defaultPriority(string $type): string
    {
        return $type === 'page' ? '0.8' : '0.7';
    }

    /**
     * Default change frequency for post type.
     */
    private function defaultChangefreq(string $type): string
    {
        return $type === 'page' ? 'monthly' : 'weekly';
    }

    /**
     * Escape XML special characters.
     */
    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    /**
     * Return the XML as a string.
     */
    public function __toString(): string
    {
        return $this->toXml();
    }
}
