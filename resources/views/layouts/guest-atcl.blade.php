<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ATCL SACCOS Membership Referral Portal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(6px, -8px) scale(1.02); }
            66% { transform: translate(-4px, 4px) scale(0.98); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-float { animation: float 8s ease-in-out infinite; }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
    </style>
</head>
<body class="bg-[#0f2d4a] text-primaryText antialiased min-h-screen overflow-x-hidden">
    {{-- Full-screen dynamic background --}}
    <div class="fixed inset-0 z-0">
        {{-- Base gradient (ATCL blue spectrum) --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#0f2d4a] via-[#153a5c] to-[#20538A]"></div>
        {{-- Soft radial spots for depth --}}
        <div class="absolute inset-0 opacity-40" style="background: radial-gradient(ellipse 80% 50% at 20% 40%, rgba(32,83,138,0.5) 0%, transparent 50%), radial-gradient(ellipse 60% 80% at 80% 60%, rgba(32,83,138,0.35) 0%, transparent 50%);"></div>
        {{-- Canvas bubbles --}}
        <canvas id="auth-bubbles" class="absolute inset-0 w-full h-full" aria-hidden="true"></canvas>
        {{-- Top vignette --}}
        <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to bottom, rgba(15,45,74,0.3) 0%, transparent 40%);"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col">
        {{-- Main content --}}
        <main class="flex-1 min-h-0 overflow-y-auto px-4 sm:px-6 py-6 sm:py-8 flex flex-col items-center justify-center">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="flex-shrink-0 py-4 px-4 text-center">
            <p class="text-subtitle text-white/60">Membership Referral Portal</p>
        </footer>
    </div>

    <script>
(function() {
    var canvas = document.getElementById('auth-bubbles');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var bubbles = [];
    var animationId;
    var BRAND_BLUE = '32, 83, 138';
    var COLORS = [
        'rgba(' + BRAND_BLUE + ', 0.25)',
        'rgba(' + BRAND_BLUE + ', 0.18)',
        'rgba(255, 255, 255, 0.08)',
        'rgba(255, 255, 255, 0.05)'
    ];

    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        if (bubbles.length === 0) initBubbles();
    }

    function initBubbles() {
        var count = Math.min(40, Math.floor((canvas.width * canvas.height) / 18000));
        bubbles = [];
        for (var i = 0; i < count; i++) {
            bubbles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                r: 4 + Math.random() * 28,
                vx: (Math.random() - 0.5) * 0.4,
                vy: (Math.random() - 0.5) * 0.4 - 0.15,
                color: COLORS[Math.floor(Math.random() * COLORS.length)]
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        bubbles.forEach(function(b) {
            b.x += b.vx;
            b.y += b.vy;
            if (b.x < -b.r) b.x = canvas.width + b.r;
            if (b.x > canvas.width + b.r) b.x = -b.r;
            if (b.y < -b.r) b.y = canvas.height + b.r;
            if (b.y > canvas.height + b.r) b.y = -b.r;
            ctx.beginPath();
            ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
            ctx.fillStyle = b.color;
            ctx.fill();
        });
        animationId = requestAnimationFrame(draw);
    }

    resize();
    window.addEventListener('resize', function() {
        resize();
    });
    draw();
})();
    </script>
    @livewireScripts
</body>
</html>
