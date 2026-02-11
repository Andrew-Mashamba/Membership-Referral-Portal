@props(['value' => null])

<label {{ $attributes->merge(['class' => 'block text-subtitle text-secondaryText uppercase tracking-wider mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
