<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Archive extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'archive',
    ];

    /**
     * Data to be passed to the view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'title' => $this->title(),
            'description' => $this->description(),
            'post_count' => $this->postCount(),
        ];
    }

    /**
     * Retrieve the archive title.
     */
    public function title(): string
    {
        if (is_category()) {
            return single_cat_title('', false);
        }

        if (is_tag()) {
            return single_tag_title('', false);
        }

        if (is_author()) {
            return get_the_author();
        }

        if (is_date()) {
            return get_the_archive_title();
        }

        return get_the_archive_title();
    }

    /**
     * Retrieve the archive description.
     */
    public function description(): string
    {
        if (is_category() || is_tag() || is_tax()) {
            return term_description();
        }

        if (is_author()) {
            return get_the_author_meta('description');
        }

        return '';
    }

    /**
     * Retrieve the total number of posts.
     */
    public function postCount(): int
    {
        global $wp_query;
        return $wp_query->found_posts ?? 0;
    }
}
