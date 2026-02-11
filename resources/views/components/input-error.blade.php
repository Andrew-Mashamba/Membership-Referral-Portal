@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-subtitle text-secondaryText mt-1.5']) }}>{{ $message }}</p>
@enderror
