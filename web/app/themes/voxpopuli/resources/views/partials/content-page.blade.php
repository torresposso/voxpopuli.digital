<x-prose>
  @php(the_content())
</x-prose>

@if ($pagination())
  <nav class="border-t-2 border-base-300 pt-8" aria-label="Page">
    {!! $pagination !!}
  </nav>
@endif
