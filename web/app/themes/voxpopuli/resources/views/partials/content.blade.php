@php
  $categories = array_filter(get_the_category(), function ($cat) {
    return $cat->slug !== 'destacadas';
  });
  $category = ! empty($categories) ? reset($categories)->name : null;
@endphp

<article @php(post_class())>
  <x-card
    :title="get_the_title()"
    :category="$category"
    :author="get_the_author_meta('display_name')"
    :date="get_the_date()"
    :url="get_permalink()"
  />

  @include('partials.entry-meta')
</article>
