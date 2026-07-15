<form role="search" method="get" class="search-form flex gap-2" action="{{ home_url('/') }}">
  <label class="flex-1">
    <span class="sr-only">{{ _x('Buscar:', 'label', 'voxpopuli') }}</span>
    <input
      type="search"
      class="input input-bordered w-full"
      placeholder="{!! esc_attr_x('Buscar &hellip;', 'placeholder', 'voxpopuli') !!}"
      value="{!! get_search_query() !!}"
      name="s"
    >
  </label>

  <button type="submit" class="btn btn-primary font-sans font-bold uppercase tracking-wider text-[0.75rem]">
    {{ _x('Buscar', 'submit button', 'voxpopuli') }}
  </button>
</form>
