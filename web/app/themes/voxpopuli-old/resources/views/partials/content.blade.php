@props([
  'post' => null,
])

@if ($post)
  <x-post-card :post="$post" />
@endif
