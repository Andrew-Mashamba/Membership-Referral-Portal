@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-6 md:mt-0 md:col-span-2">
        <form wire:submit="{{ $submit }}">
            <div class="px-5 py-5 sm:p-6 bg-white shadow-soft border border-white/80 {{ isset($actions) ? 'rounded-t-2xl border-b border-brandGray/10' : 'rounded-2xl' }}">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-5 py-4 bg-brandGray/5 text-end sm:px-6 rounded-b-2xl border border-brandGray/10 border-t-0">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
