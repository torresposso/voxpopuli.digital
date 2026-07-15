@props([
  'type' => null,
  'message' => null,
])

@php($class = match ($type) {
  'success' => 'alert-success',
  'warning' => 'alert-warning',
  'error' => 'alert-error',
  default => 'alert-info',
})

<div {{ $attributes->merge(["class" => "alert {$class} rounded-box"]) }}>
  {!! $message ?? $slot !!}
</div>
