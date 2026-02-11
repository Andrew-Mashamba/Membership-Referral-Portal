@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-xl border-brandGray/20 focus:border-brandBlue focus:ring-2 focus:ring-brandBlue/30 shadow-sm min-h-[48px] px-4 py-2.5 text-body text-primaryText bg-primaryBg block w-full transition outline-none']) !!}>
