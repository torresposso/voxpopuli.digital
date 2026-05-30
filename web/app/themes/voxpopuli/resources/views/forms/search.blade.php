<form role="search" method="get" class="search-form flex gap-2 items-center" action="{{ home_url('/') }}">
  <label class="sr-only">
    {{ _x('Search for:', 'label', 'voxpopuli') }}
  </label>

  <input
    type="search"
    class="input input-ghost w-full border-base-300"
    placeholder="{{ esc_attr_x('Search &hellip;', 'placeholder', 'voxpopuli') }}"
    value="{{ get_search_query() }}"
    name="s"
  >

  <button class="btn btn-primary">{{ _x('Search', 'submit button', 'voxpopuli') }}</button>
</form>
