<?php

namespace App\Seo;

/**
 * Data class holding per-post SEO metadata.
 *
 * Resolves from a data array (typically from _voxpopuli_* postmeta).
 * Falls back to post data when configured.
 */
class SeoMeta
{
    private ?string $metaDesc = null;

    private ?string $ogTitle = null;

    private ?string $ogDesc = null;

    private int|string|null $ogImageId = null;

    private ?string $ogImageUrl = null;

    private bool $noindex = false;

    private ?string $canonical = null;

    private string $postType = 'post';

    private ?string $postTitle = null;

    private ?string $postUrl = null;

    private ?string $datePublished = null;

    private ?string $dateModified = null;

    private ?string $authorName = null;

    private bool $isHome = false;

    /**
     * @param  array<string, mixed>  $data  SEO metadata
     * @param  bool  $fallbackToPost  Whether to fall back to post data for OG fields
     */
    public function __construct(array $data = [], bool $fallbackToPost = false)
    {
        $this->metaDesc = $data['meta_desc'] ?? null;
        $this->ogTitle = $data['og_title'] ?? null;
        $this->ogDesc = $data['og_desc'] ?? null;
        $this->ogImageId = $data['og_image_id'] ?? null;
        $this->ogImageUrl = $data['og_image_url'] ?? null;
        $this->noindex = (bool) ($data['noindex'] ?? false);
        $this->canonical = $data['canonical'] ?? null;
        $this->postType = $data['post_type'] ?? 'post';
        $this->postTitle = $data['post_title'] ?? null;
        $this->postUrl = $data['post_url'] ?? null;
        $this->datePublished = $data['date_published'] ?? null;
        $this->dateModified = $data['date_modified'] ?? null;
        $this->authorName = $data['author_name'] ?? null;
        $this->isHome = (bool) ($data['is_home'] ?? false);

        if ($fallbackToPost) {
            $this->applyFallbacks();
        }

        $this->validate();
    }

    /**
     * Apply fallbacks from post data for OG fields.
     */
    private function applyFallbacks(): void
    {
        if ($this->ogTitle === null && $this->postTitle !== null) {
            $this->ogTitle = $this->postTitle;
        }

        if ($this->ogDesc === null && $this->metaDesc !== null) {
            $this->ogDesc = $this->metaDesc;
        }
    }

    /**
     * Validate and normalize field values.
     */
    private function validate(): void
    {
        // Truncate meta description to 160 characters
        if ($this->metaDesc !== null && mb_strlen($this->metaDesc) > 160) {
            $this->metaDesc = mb_substr($this->metaDesc, 0, 160);
        }

        // Validate canonical URL — discard if invalid
        if ($this->canonical !== null && ! $this->isValidUrl($this->canonical)) {
            $this->canonical = null;
        }
    }

    /**
     * Basic URL validation.
     */
    private function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false
            && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'));
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDesc;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDesc;
    }

    public function getOgImageId(): int|string|null
    {
        return $this->ogImageId;
    }

    public function getOgImageUrl(): ?string
    {
        return $this->ogImageUrl;
    }

    public function getNoindex(): bool
    {
        return $this->noindex;
    }

    public function getCanonical(): ?string
    {
        return $this->canonical;
    }

    public function getPostType(): string
    {
        return $this->postType;
    }

    public function getPostTitle(): ?string
    {
        return $this->postTitle;
    }

    public function getPostUrl(): ?string
    {
        return $this->postUrl;
    }

    public function getDatePublished(): ?string
    {
        return $this->datePublished;
    }

    public function getDateModified(): ?string
    {
        return $this->dateModified;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function isHome(): bool
    {
        return $this->isHome;
    }
}
