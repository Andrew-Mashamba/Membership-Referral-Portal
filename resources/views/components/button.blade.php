<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-3 min-h-[48px] bg-brandBlue border border-transparent rounded-2xl font-semibold text-body text-white shadow-soft hover:shadow-card hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
