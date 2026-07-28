@props([
    'id' => null,
])

@php
    $id = $id ?: get_the_author_meta('ID');
    $name = get_the_author_meta('display_name', $id);
@endphp

<span {{ $attributes->class('inline-flex items-center gap-2.5 font-bold') }}>
    {{--
    Placeholder avatar until the per-author illustrations are added —
    swap the initial for the author's illustration here.
  --}}

    <span aria-hidden="true"
        class="flex size-12 shrink-0 items-center overflow-hidden justify-center rounded-blob bg-yellow-soft text-sm font-bold text-black">
        {{-- {{ mb_strtoupper(mb_substr($name, 0, 1)) }} --}}
        {!! get_avatar($id) !!}

    </span>

    <span class="p-author h-card">{{ $name }}</span>
</span>
