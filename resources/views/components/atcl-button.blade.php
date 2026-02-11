@props(['title', 'subtitle' => null, 'primary' => false])

<button
    {{ $attributes->merge([
        'class' => '
            w-full min-h-[72px] max-h-20
            ' . ($primary ? 'bg-brandBlue text-white' : 'bg-white text-primaryText') . '
            rounded-lgx shadow-soft
            flex items-center gap-4 px-4
            transition hover:shadow-card
            focus:outline-none focus:ring-2 focus:ring-brandBlue focus:ring-offset-2
        '
    ]) }}
>
    @if(isset($icon))
        <div class="w-12 h-12 rounded-mdx {{ $primary ? 'bg-white/20' : 'bg-brandBlue' }} flex items-center justify-center shrink-0">
            {{ $icon }}
        </div>
    @endif
    <div class="flex-1 min-w-0 text-left">
        <p class="text-title font-semibold truncate {{ $primary ? 'text-white' : 'text-primaryText' }}">
            {{ $title }}
        </p>
        @if($subtitle)
            <p class="text-subtitle truncate text-secondaryText">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</button>
