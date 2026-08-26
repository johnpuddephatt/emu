@props([
    'type' => null,
    'message' => null,
])

{{-- The default Tailwind palette is reset in _tailwind-config.css, so the
     stock numbered colours (green-400, indigo-50 …) don't exist. These map
     onto the brand tints instead. --}}
@php($class = match ($type) {
  'success' => 'bg-green-soft',
  'caution' => 'bg-yellow-soft',
  'warning' => 'bg-red-soft',
  default => 'bg-blue-soft',
})

<div {{ $attributes->merge(['class' => "rounded-lg px-4 py-3 {$class}"]) }}>{!! $message ?? $slot !!}</div>
