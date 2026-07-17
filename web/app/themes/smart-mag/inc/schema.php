<?php
/**
 * Auto schema data generator for common places in the theme.
 * 
 * Handles generation of structured data (schema.org) for articles, reviews,
 * and related content to improve SEO and search engine rich snippets.
 */
class Bunyad_Theme_Schema 
{
	public function __construct()
	{
		add_action('wp_footer', [$this, 'article']);
		add_action('wp_footer', [$this, 'review']);
	}
	
	/**
	 * Article schema - only on single page
	 */
	public function article() 
	{
		if (!is_single() || !Bunyad::options()->single_schema_article) {
			return;
		}
		
		// Buggy plugins might have been playing around
		wp_reset_query();
		rewind_posts();
		
		if (!have_posts()) {
			return;
		}
		
		the_post();	
		
		// Get featured image, quit if missing
		$featured = $this->get_featured_image();
		if (!$featured) {
			return;
		}
		
		// Article schema 
		$schema    = [
			'@context'      => 'http://schema.org',
			'@type'         => 'Article',
			'headline'      => get_the_title(),
			'url'           => get_the_permalink(),
			'image'         => $featured,
			'datePublished' => get_the_date(DATE_W3C),
			'dateModified'  => get_the_modified_date(DATE_W3C),
			'author'        => [],
			'publisher'     => $this->get_publisher(),
			'mainEntityOfPage' => [
				'@type' => 'WebPage',
				'@id'   => get_the_permalink(),
			],
		];

		// Handle multiple authors if Co-Authors Plus is active.
		if (function_exists('get_coauthors')) {
			$authors = get_coauthors();
			foreach ($authors as $author) {
				$schema['author'][] = [
					'@type' => 'Person',
					'name'  => $author->display_name,
					'url'   => get_author_posts_url($author->ID, $author->user_nicename ?? ''),
				];
			}
		} else {
			// Fallback to single author
			$schema['author'] = [
				'@type' => 'Person',
				'name'  => get_the_author(),
				'url'   => $this->get_author_url(),
			];
		}

		$schema = apply_filters('bunyad_schema_article', $schema);
		echo '<script type="application/ld+json">' . json_encode($schema) . "</script>\n";
	}
	
	/**
	 * Review schema - only on single pages
	 */
	public function review()
	{
		if (!is_single() || !Bunyad::options()->review_schema || !Bunyad::posts()->meta('reviews') || !Bunyad::reviews()) {
			return;
		}
		
		// Buggy plugins might have been playing around
		wp_reset_query();
		rewind_posts();
		
		if (!have_posts()) {
			return;
		}
		
		the_post();
		
		/**
		 * Define basic info for the schema.
		 */
		$schema_type = Bunyad::posts()->meta('review_schema') ? Bunyad::posts()->meta('review_schema') : 'Product';
		$item_author = Bunyad::posts()->meta('review_item_author') ? Bunyad::posts()->meta('review_item_author') : get_the_author();
		$item_name   = Bunyad::posts()->meta('review_item_name') ? Bunyad::posts()->meta('review_item_name') : get_the_title();
		$item_author_type = Bunyad::posts()->meta('review_item_author_type');
		$item_author_url  = $this->get_author_url();

		// Use verdict text or fallback to excerpt from post.
		$description = (
			Bunyad::posts()->meta('review_verdict_text') 
				? Bunyad::posts()->meta('review_verdict_text') 
				: strip_tags(Bunyad::posts()->excerpt(null, 180, ['add_more' => false]))
		);

		// Author data to be added for certain types.
		$author_data = [
			'@type' => $item_author_type ? ucfirst($item_author_type) : 'Person',
			'name'  => $item_author,
		];

		if ($author_data['@type'] === 'Person') {
			$author_data['url'] = $item_author_url;
		}

		// Types that should add author.
		$have_author = [
			'CreativeWorkSeason', 
			'CreativeWorkSeries', 
			'Game', 
			'MediaObject', 
			'MusicPlaylist', 
			'MusicRecording'
		];

		// Denotes disabled.
		if ($schema_type === 'none') {
			return;
		}

		// Final schema.
		$schema      = [
			'@context' => 'https://schema.org',
			'@type'    => 'Review',
			
			'itemReviewed' => [
				'@type'  => $schema_type,
				'name'   => $item_name,
				'image'  => $this->get_featured_image(),
			],
			'author'   => [
				'@type' => 'Person',
				'name'  => get_the_author(),
				'url'   => $item_author_url,
			],
			'name'         => get_the_title(),
			'publisher'    => $this->get_publisher(),
			'reviewRating' => [
				'@type'       => 'Rating',
				'ratingValue' => Bunyad::posts()->meta('review_overall'),
				'worstRating'  => '0',
				'bestRating'   => Bunyad::options()->review_scale,
			],
			// Limited as some types require it with max chars of 200.
			'description'   => mb_substr($description, 0, 200),
			'datePublished' => get_the_date(DATE_W3C),
		];

		// Add official link - mainly for type Movie but is supported by all.
		if ($link = Bunyad::posts()->meta('review_item_link')) {
			$schema['itemReviewed']['sameAs'] = esc_url($link);
		}

		$aggregate = $this->get_aggregate_rating();
		if ($aggregate) {
			$schema['itemReviewed']['aggregateRating'] = $aggregate;
		}

		// Add id reference to fix testing tool issue.
		$schema_id     = esc_url(get_permalink()) . '#review';
		$schema['@id'] = $schema_id;
		$schema['itemReviewed']['review'] = ['@id' => $schema_id];

		// Add author for certain types.
		if (in_array($schema_type, $have_author)) {
			$schema['itemReviewed']['author'] = $author_data;
		}

		/**
		 * Add Pros and Cons.
		 */
		$pros_cons = Bunyad::reviews()->get_pros_cons();
		if ($pros_cons) {
			$schema['positiveNotes'] = $this->get_items_list($pros_cons['pros']);
			$schema['negativeNotes'] = $this->get_items_list($pros_cons['cons']);		
		}

		/**
		 * Additional per schema type changes.
		 * 
		 * NOTE: This should be the last code block as some cases need to nest $schema in review.
		 */
		switch ($schema_type) {

			// Course uses provider.
			case 'Course':
				$schema['itemReviewed']['provider'] = $author_data;

				// Description is required to be nested.
				$schema['itemReviewed']['description'] = $schema['description'];
				break;

			// Movie requires publisher.
			case 'Movie':
				$schema['itemReviewed']['publisher'] = $author_data;
				break;

			// Product suggests description and brand.
			case 'Product':

				unset($schema['itemReviewed']);

				$schema = [
					'@context'    => 'https://schema.org',
					'@type'       => 'Product',
					'name'        => $item_name,
					'description' => $description,
					'review'      => $schema,
					'image'       => $this->get_featured_image(),
					'offers'      => $this->get_offers(),
				];

				if (Bunyad::posts()->meta('review_item_author')) {
					// Add brand.
					$author_data['@type'] = 'Brand';
					$schema['brand'] = $author_data;
				}

				break;

			// Software App needs offers. Optional OS and Category.
			case 'SoftwareApplication':
				$schema['itemReviewed'] += array_filter([
					'operatingSystem'     => Bunyad::posts()->meta('review_item_os'),
					'applicationCategory' => Bunyad::posts()->meta('review_item_app_cat'),
					'offers' => $this->get_offers()
				]);
				
				break;
		}

		$schema = apply_filters('bunyad_schema_review', $schema);
		echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . "</script>\n";
	}

	/**
	 * Get author URL for the current post.
	 *
	 * @return string
	 */
	public function get_author_url()
	{
		$authordata = $GLOBALS['authordata'];
		if (is_object($authordata)) {
			return get_author_posts_url($authordata->ID, $authordata->user_nicename);
		}

		return '';
	}

	/**
	 * Get an item list - used for pros and cons.
	 *
	 * @param array $items
	 * @return array
	 */
	public function get_items_list($items) 
	{
		$list  = [];
		$count = 1;
		foreach ((array) $items as $item) {
			$list[] = [
				'@type'    => 'ListItem',
				'position' => ($count++),
				'name'     => $item
			];
		}

		return [
			'@type' => 'ItemList',
			'itemListElement' => $list,
		];
	}

	/**
	 * Get aggregate rating - users rating or editor if none yet.
	 */
	public function get_aggregate_rating()
	{
		$schema = [
			'@type'       => 'AggregateRating',
			'worstRating' => '0',
			'bestRating'  => Bunyad::options()->review_scale,
		];

		$rating = Bunyad::posts()->meta('user_rating');
		if (empty($rating) || !isset($rating['overall'])) {

			// Only aggregate if any votes exist.
			return false;

			// $schema += [
			// 	'ratingValue' => Bunyad::posts()->meta('review_overall'),
			// 	'ratingCount' => 1,
			// ];
		}
		else {
			$schema += [
				'ratingValue' => round($rating['overall'], 1),
				'ratingCount' => intval($rating['count']),
			];
		}

		return $schema;
	}
	
	/**
	 * Get featured image of current article
	 */
	public function get_featured_image()
	{
		$id = get_post_thumbnail_id();
		
		if (!$id) {
			return false;
		}
		
		// Fetch the featured image meta
		$image = wp_get_attachment_image_src($id, 'main-featured');
		list($url, $width, $height) = $image;
		
		// Prepare the schema
		$data = [
			'@type'  => 'ImageObject',
			'url'    => $url,
			'width'  => $width,
			'height' => $height
		];
		
		return $data;
	}
	
	/**
	 * Get publisher info
	 */
	public function get_publisher()
	{	
		$data = [
			'@type'  => 'Organization',
			'name'   => get_bloginfo('name'),
			'sameAs' => get_home_url()
		];
		
		// Have image logo?
		if (Bunyad::options()->image_logo) {
			$data['logo'] = [
				'@type' => 'ImageObject',
				'url'   => Bunyad::options()->image_logo,
			];
		}
		
		return $data;
	}

	/**
	 * Get the offer for SoftwareApplication or Product.
	 */
	public function get_offers()
	{
		$price = Bunyad::posts()->meta('review_item_price');
		if ($price) {
			return [
				'@type'         => 'Offer',
				'priceCurrency' => Bunyad::posts()->meta('review_item_currency') ?: Bunyad::options()->review_schema_currency,
				'price'         => $price
			];
		}

		return [];
	}
}

// init and make available in Bunyad::get('schema')
Bunyad::register('schema', [
	'class' => 'Bunyad_Theme_Schema',
	'init'  => true
]);