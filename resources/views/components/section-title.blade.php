<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-title font-semibold text-primaryText">{{ $title }}</h3>

        <p class="mt-1 text-body text-secondaryText">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
