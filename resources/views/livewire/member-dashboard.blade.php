<div class="max-w-5xl mx-auto px-4 sm:px-0 py-6 sm:py-8">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    .delay-75 { animation-delay: 75ms; }
    .delay-150 { animation-delay: 150ms; }
    .delay-225 { animation-delay: 225ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-375 { animation-delay: 375ms; }
  </style>

  {{-- Stats grid --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5 mb-8">
    @foreach([
      ['label' => 'Total referrals', 'value' => $total, 'href' => null, 'accent' => false, 'delay' => '75'],
      ['label' => 'Pending', 'value' => $pending, 'href' => route('referrals.index'), 'accent' => true, 'delay' => '150'],
      ['label' => 'Approved', 'value' => $approved, 'href' => null, 'accent' => false, 'delay' => '225'],
      ['label' => 'Rejected', 'value' => $rejected, 'href' => null, 'accent' => false, 'delay' => '300'],
    ] as $stat)
      @php $delayClass = 'delay-' . $stat['delay']; @endphp
      <div class="opacity-0 animate-fade-in-up {{ $delayClass }} group">
        @if($stat['href'])
          <a href="{{ $stat['href'] }}" class="block h-full p-5 sm:p-6 bg-white rounded-2xl shadow-soft border border-white/80 transition-all duration-300 ease-out hover:shadow-card hover:-translate-y-1 hover:border-brandBlue/10 focus:outline-none focus:ring-2 focus:ring-brandBlue/30">
        @else
          <div class="h-full p-5 sm:p-6 bg-white rounded-2xl shadow-soft border border-white/80 transition-all duration-300 ease-out group-hover:shadow-card group-hover:-translate-y-1 group-hover:border-brandGray/20">
        @endif
          <p class="text-subtitle text-secondaryText uppercase tracking-wider mb-1">{{ $stat['label'] }}</p>
          <p class="text-2xl sm:text-3xl font-bold {{ $stat['accent'] ? 'text-brandBlue' : 'text-primaryText' }}">
            {{ $stat['value'] }}
          </p>
        @if($stat['href'])
          </a>
        @else
          </div>
        @endif
      </div>
    @endforeach
  </div>

  {{-- Charts row: time series + status (side by side, equal height) --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 items-stretch">
    {{-- Referrals over time (line chart) --}}
    <div class="opacity-0 animate-fade-in-up delay-375 flex">
      <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden transition-all duration-300 hover:shadow-card flex flex-col w-full">
        <div class="p-5 sm:p-6 flex flex-col flex-1 min-h-0">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <h2 class="text-title font-semibold text-primaryText">Referrals over time</h2>
            <select wire:model.live="period" class="w-full sm:w-auto rounded-xl border border-brandGray/20 px-4 py-2.5 text-body text-primaryText bg-primaryBg focus:ring-2 focus:ring-brandBlue/30 focus:border-brandBlue transition outline-none min-h-[44px]">
              <option value="daily">Daily (last 30 days)</option>
              <option value="monthly">Monthly (last 6 months)</option>
              <option value="annual">Annual (last 5 years)</option>
            </select>
          </div>
          <div class="h-64 sm:h-72 flex-1 min-h-[240px]" wire:ignore>
            <canvas id="chart-member-referrals-time" role="img" aria-label="Referrals over time chart"></canvas>
          </div>
          @if(empty($chartTimeLabels))
            <p class="text-body text-secondaryText py-2">No referral activity in the selected period.</p>
          @endif
        </div>
      </div>
    </div>

    {{-- Status breakdown (doughnut) --}}
    <div class="opacity-0 animate-fade-in-up delay-375 flex">
      <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden transition-all duration-300 hover:shadow-card flex flex-col w-full h-full">
        <div class="p-5 sm:p-6 flex flex-col flex-1 min-h-0">
          <h2 class="text-title font-semibold text-primaryText mb-4">Status breakdown</h2>
          <div class="h-64 sm:h-72 flex-1 min-h-[240px] flex items-center justify-center" wire:ignore>
            <canvas id="chart-member-status" role="img" aria-label="Referral status breakdown"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Recent referrals (list + CTA) --}}
  <div class="opacity-0 animate-fade-in-up delay-375">
    <div class="bg-white rounded-2xl shadow-soft border border-white/80 overflow-hidden transition-all duration-300 hover:shadow-card">
      <div class="p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
          <h2 class="text-title font-semibold text-primaryText">Recent referrals</h2>
          <a href="{{ route('referrals.create') }}" class="min-h-[48px] px-4 flex items-center justify-center bg-brandBlue text-white rounded-xl shadow-soft font-semibold text-title focus:ring-2 focus:ring-brandBlue/30 focus:ring-offset-2 transition outline-none shrink-0">
            Submit referral
          </a>
        </div>
        <ul class="space-y-2 border-t border-gray-100 pt-4">
          @forelse ($recent as $r)
            <li class="flex justify-between items-center text-body text-primaryText py-2 border-b border-brandGray/10 last:border-0">
              <a href="{{ route('referrals.show', $r) }}" class="text-brandBlue font-medium hover:underline">{{ $r->referral_id }}</a>
              <span class="text-subtitle text-secondaryText">{{ $r->referred_name }} · {{ ucfirst($r->status) }}</span>
            </li>
          @empty
            <li class="text-secondaryText text-body py-4">No referrals yet. <a href="{{ route('referrals.create') }}" class="text-brandBlue font-medium hover:underline">Submit one</a>.</li>
          @endforelse
        </ul>
        @if($recent->isNotEmpty())
          <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('referrals.index') }}" class="text-body text-brandBlue font-medium hover:underline">View all referrals</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@script
<script>
  const BRAND_BLUE = 'rgb(32, 83, 138)';
  const BRAND_BLUE_LIGHT = 'rgba(32, 83, 138, 0.15)';
  const BRAND_BLUE_MID = 'rgba(32, 83, 138, 0.5)';
  const BRAND_GRAY = 'rgb(121, 121, 121)';

  function initCharts() {
    const timeLabels = @js($chartTimeLabels);
    const timeValues = @js($chartTimeValues);
    const statusLabels = @js($chartStatusLabels);
    const statusValues = @js($chartStatusValues);

    // Referrals over time (line)
    const timeCtx = document.getElementById('chart-member-referrals-time');
    if (timeCtx && typeof Chart !== 'undefined') {
      if (window.memberTimeChart) window.memberTimeChart.destroy();
      window.memberTimeChart = new Chart(timeCtx, {
        type: 'line',
        data: {
          labels: timeLabels,
          datasets: [{
            label: 'Referrals',
            data: timeValues,
            borderColor: BRAND_BLUE,
            backgroundColor: BRAND_BLUE_LIGHT,
            fill: true,
            tension: 0.3,
            pointBackgroundColor: BRAND_BLUE,
            pointBorderColor: '#fff',
            pointBorderWidth: 1,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 1 },
              grid: { color: 'rgba(0,0,0,0.06)' }
            },
            x: {
              grid: { display: false }
            }
          }
        }
      });
    }

    // Status breakdown (doughnut)
    const statusCtx = document.getElementById('chart-member-status');
    if (statusCtx && typeof Chart !== 'undefined') {
      if (window.memberStatusChart) window.memberStatusChart.destroy();
      window.memberStatusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
          labels: statusLabels,
          datasets: [{
            data: statusValues,
            backgroundColor: [BRAND_BLUE_MID, BRAND_BLUE, BRAND_GRAY],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '60%',
          plugins: {
            legend: { position: 'bottom' }
          }
        }
      });
    }
  }

  function runWhenChartReady(fn, attempts = 20) {
    if (typeof Chart !== 'undefined') {
      fn();
      return;
    }
    let n = 0;
    const t = setInterval(() => {
      n++;
      if (typeof Chart !== 'undefined') {
        clearInterval(t);
        fn();
      } else if (n >= attempts) clearInterval(t);
    }, 50);
  }

  runWhenChartReady(initCharts);

  $wire.$watch('period', () => {
    $wire.getChartTimeData().then((data) => {
      if (window.memberTimeChart && data && data.labels) {
        window.memberTimeChart.data.labels = data.labels;
        window.memberTimeChart.data.datasets[0].data = data.values;
        window.memberTimeChart.update();
      }
    });
  });
</script>
@endscript
