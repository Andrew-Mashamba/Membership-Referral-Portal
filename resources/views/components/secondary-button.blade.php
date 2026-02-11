<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-3 min-h-[48px] bg-primaryBg border border-brandGray/20 rounded-2xl font-semibold text-body text-primaryText shadow-sm hover:bg-brandGray/5 hover:border-brandGray/30 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
