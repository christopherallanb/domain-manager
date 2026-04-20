@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $total = $domains->count();
        $in30 = $domains->filter(fn($d) => \Carbon\Carbon::parse($d->expiration_date)->diffInDays(now()) <= 30 && \Carbon\Carbon::parse($d->expiration_date)->isFuture())->count();
        $in7 = $domains->filter(fn($d) => \Carbon\Carbon::parse($d->expiration_date)->diffInDays(now()) <= 7 && \Carbon\Carbon::parse($d->expiration_date)->isFuture())->count();
        $expired = $domains->filter(fn($d) => \Carbon\Carbon::parse($d->expiration_date)->isPast())->count();
    @endphp
    <div class="rounded-xl border bg-card p-5 flex items-center gap-4">
        <div class="rounded-xl p-3 bg-primary/10"><svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        <div><p class="text-2xl font-bold">{{ $total }}</p><p class="text-xs text-muted-foreground">Total de domínios</p></div>
    </div>
    <div class="rounded-xl border bg-card p-5 flex items-center gap-4">
        <div class="rounded-xl p-3 bg-warning/10"><svg class="h-6 w-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
        <div><p class="text-2xl font-bold">{{ $in30 }}</p><p class="text-xs text-muted-foreground">Vencendo em 30 dias</p></div>
    </div>
    <div class="rounded-xl border bg-card p-5 flex items-center gap-4">
        <div class="rounded-xl p-3 bg-orange/10"><svg class="h-6 w-6 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        <div><p class="text-2xl font-bold">{{ $in7 }}</p><p class="text-xs text-muted-foreground">Vencendo em 7 dias</p></div>
    </div>
    <div class="rounded-xl border bg-card p-5 flex items-center gap-4">
        <div class="rounded-xl p-3 bg-danger/10"><svg class="h-6 w-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        <div><p class="text-2xl font-bold">{{ $expired }}</p><p class="text-xs text-muted-foreground">Vencidos</p></div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 rounded-xl border bg-card p-5">
        <h2 class="text-sm font-semibold mb-4">Próximos vencimentos</h2>
        <div class="space-y-3">
            @foreach($domains->sortBy('expiration_date')->take(5) as $domain)
                @php $days = \Carbon\Carbon::parse($domain->expiration_date)->diffInDays(now()); @endphp
                <div class="flex items-center justify-between gap-2 p-2 rounded-lg hover:bg-muted/50">
                    <div><p class="text-sm font-medium">{{ $domain->name }}</p><p class="text-xs text-muted-foreground">{{ $domain->registrar }}</p></div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                            @if($days < 0) bg-destructive text-destructive-foreground
                            @elseif($days <= 7) bg-orange text-orange-foreground
                            @elseif($days <= 30) bg-warning text-warning-foreground
                            @else bg-success text-success-foreground @endif">
                            @if($days < 0) Vencido @else {{ $days }} dias @endif
                        </span>
                        <form action="{{ route('domains.renew', $domain) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="new_date" value="{{ \Carbon\Carbon::parse($domain->expiration_date)->addYear()->format('Y-m-d') }}">
                            <button type="submit" class="p-1 rounded hover:bg-muted"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="rounded-xl border bg-card p-5">
        <h2 class="text-sm font-semibold mb-4">Custos por registrador</h2>
        <div class="space-y-2">
            @php $costByRegistrar = $domains->groupBy('registrar')->map(fn($g) => $g->sum('annual_cost')); @endphp
            @foreach($costByRegistrar as $registrar => $cost)
                <div class="flex justify-between text-sm"><span>{{ $registrar }}</span><span class="font-medium">R$ {{ number_format($cost, 2, ',', '.') }}</span></div>
            @endforeach
        </div>
        <div class="mt-4">
            <canvas id="registrarChart" height="120"></canvas>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function(){
        const data = @json($costByRegistrar);
        const labels = Object.keys(data);
        const values = Object.values(data);
        const ctx = document.getElementById('registrarChart');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Custo anual (R$)', data: values, backgroundColor: '#60A5FA' }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    })();
</script>
@endpush
@endsection
