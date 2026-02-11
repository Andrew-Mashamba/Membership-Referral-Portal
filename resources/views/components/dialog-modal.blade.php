@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-title font-semibold text-primaryText">
            {{ $title }}
        </div>

        <div class="mt-4 text-body text-secondaryText">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end gap-3 px-6 py-4 bg-brandGray/5 text-end border-t border-brandGray/20">
        {{ $footer }}
    </div>
</x-modal>
