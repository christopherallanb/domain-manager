@extends('layouts.admin')

@section('content')
<div class="rounded-xl border bg-card p-5">
    <h2 class="text-sm font-semibold mb-4">Relatórios de Custos</h2>
    <div class="space-y-4">
        @php
            $totalCost = $domains->sum('annual_cost');
            $costByRegistrar = $domains->groupBy('registrar')->map(fn($g) => $g->sum('annual_cost'));
        @endphp
        <div class="flex items-center gap-2 mb-3">
            <form method="GET" action="{{ route('domains.reports') }}" class="inline-flex items-center gap-2">
                <select name="year" class="rounded-md border px-2 py-1">
                    <option value="">Todos</option>
                    <option value="2025" {{ request('year')=='2025'? 'selected':'' }}>2025</option>
                    <option value="2026" {{ request('year')=='2026'? 'selected':'' }}>2026</option>
                </select>
                <button class="rounded-md border px-3 py-1">Filtrar</button>
            </form>
            <a href="{{ route('reports.export', ['year' => request('year')]) }}" class="rounded-md border px-3 py-1">Exportar CSV</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-lg border p-4">
                <h3 class="font-medium mb-2">Custo Total Anual</h3>
                <p class="text-2xl font-bold text-primary">R$ {{ number_format($totalCost, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-lg border p-4">
                <h3 class="font-medium mb-2">Total de Domínios</h3>
                <p class="text-2xl font-bold text-primary">{{ $domains->count() }}</p>
            </div>
        </div>
        
        <div class="rounded-lg border p-4">
            <h3 class="font-medium mb-4">Custo por Registrador</h3>
            <div class="space-y-2">
                @foreach($costByRegistrar as $registrar => $cost)
                    <div class="flex justify-between items-center">
                        <span>{{ $registrar }}</span>
                        <span class="font-medium">R$ {{ number_format($cost, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
