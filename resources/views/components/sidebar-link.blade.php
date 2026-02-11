@props(['active' => false])

@php
$classes = ($active ?? false)
    ? 'flex items-center gap-3 px-4 py-3 min-h-[48px] text-body font-medium text-brandBlue bg-brandBlue/10 rounded-mdx transition'
    : 'flex items-center gap-3 px-4 py-3 min-h-[48px] text-body text-primaryText hover:bg-brandGray/5 hover:text-brandBlue rounded-mdx transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
