<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Category extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'category',
    ];

    /**
     * Bind data to the category view.
     *
     * @return array
     */
    public function with()
    {
        $queried = get_queried_object();

        return [
            'categoryName' => $queried instanceof \WP_Term ? $queried->name : '',
            'categoryDescription' => $queried instanceof \WP_Term ? $queried->description : '',
            'categoryCount' => $queried instanceof \WP_Term ? $queried->count : 0,
        ];
    }
}
