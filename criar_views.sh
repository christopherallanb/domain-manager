#!/bin/bash

# =============================================
# 1. Layout principal (admin.blade.php)
# =============================================
cat > resources/views/layouts/admin.blade.php << 'LAYOUT'
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Manager | Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background text-foreground">
    <div class="min-h-screen flex">
        <!-- Sidebar (injetada via include) -->
        @include('layouts.partials.sidebar')

        <!-- Main content -->
        <div class="flex-1 lg:ml-60 min-h-screen flex flex-col transition-all duration-300">
            <header class="sticky top-0 z-30 h-16 bg-card/80 backdrop-blur-md border-b border-border flex items-center justify-between px-4 sm:px-6">
                <div class="ml-10 lg:ml-0">
                    @if(isset($breadcrumb) && count($breadcrumb))
                        <p class="text-xs text-muted-foreground mb-0.5">{{ implode(' / ', $breadcrumb) }}</p>
                    @endif
                    <h1 class="text-lg font-semibold">{{ $title ?? 'Domain Manager' }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button class="relative rounded-lg p-2 hover:bg-muted transition-colors">
                        <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-danger"></span>
                    </button>
                    <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-primary-foreground">AD</div>
                </div>
            </header>
            <main class="flex-1 p-4 sm:p-6 animate-fade-in">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
LAYOUT

# =============================================
# 2. Sidebar parcial
# =============================================
mkdir -p resources/views/layouts/partials
cat > resources/views/layouts/partials/sidebar.blade.php << 'SIDEBAR'
<aside class="fixed top-0 left-0 z-40 h-screen w-60 bg-sidebar text-sidebar-foreground border-r border-sidebar-border transition-all duration-300">
    <div class="flex items-center gap-2 px-4 h-16 border-b border-sidebar-border">
        <svg class="h-7 w-7 text-sidebar-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-lg font-bold">Domain Manager</span>
    </div>
    <nav class="flex-1 py-4 px-2 space-y-1">
        @php $currentRoute = request()->route()->getName(); @endphp
        @foreach([
            ['route' => 'domains.dashboard', 'label' => 'Dashboard', 'icon' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
            ['route' => 'domains.index', 'label' => 'Domínios', 'icon' => '<path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['route' => 'domains.reports', 'label' => 'Relatórios', 'icon' => '<path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
            ['route' => 'domains.settings', 'label' => 'Configurações', 'icon' => '<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>'],
        ] as $item)
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === $item['route'] ? 'bg-sidebar-primary text-sidebar-primary-foreground' : 'text-sidebar-foreground hover:bg-sidebar-accent' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <div class="border-t border-sidebar-border px-3 py-3">
        <div class="flex items-center gap-2 px-1 pt-2">
            <div class="h-8 w-8 rounded-full bg-sidebar-primary flex items-center justify-center text-xs font-bold">AD</div>
            <div class="text-xs">
                <p class="font-medium">Admin</p>
                <p class="text-sidebar-muted">admin@domain.com</p>
            </div>
        </div>
    </div>
</aside>
SIDEBAR

# =============================================
# 3. Dashboard (página inicial)
# =============================================
cat > resources/views/admin/domains/dashboard.blade.php << 'DASHBOARD'
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
    </div>
</div>
@endsection
DASHBOARD

# =============================================
# 4. Listagem de domínios (index)
# =============================================
cat > resources/views/admin/domains/index.blade.php << 'INDEX'
@extends('layouts.admin')

@section('content')
<div class="rounded-xl border bg-card p-5">
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="search" placeholder="Buscar domínio ou registrador..." class="w-full rounded-md border border-input bg-background px-3 py-2 pl-9 text-sm">
        </div>
        <div class="flex gap-2">
            <a href="{{ route('domains.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:bg-primary/90"><svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Novo Domínio</a>
        </div>
    </div>
    <div class="overflow-x-auto rounded-lg border border-border">
        <table class="w-full text-sm">
            <thead class="bg-muted/50">
                <tr><th class="text-left px-4 py-3 font-medium">Domínio</th><th class="text-left px-4 py-3 font-medium hidden sm:table-cell">Registrador</th><th class="text-left px-4 py-3 font-medium">Expiração</th><th class="text-center px-4 py-3 font-medium">Dias</th><th class="text-right px-4 py-3 font-medium hidden md:table-cell">Custo/ano</th><th class="text-center px-4 py-3 font-medium">Ações</th></tr>
            </thead>
            <tbody id="domains-table-body">
                @foreach($domains as $domain)
                    @php $days = \Carbon\Carbon::parse($domain->expiration_date)->diffInDays(now()); @endphp
                    <tr class="border-b border-border hover:bg-muted/20">
                        <td class="px-4 py-3 font-medium">{{ $domain->name }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground">{{ $domain->registrar }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ \Carbon\Carbon::parse($domain->expiration_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center"><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold @if($days<0) bg-destructive text-destructive-foreground @elseif($days<=7) bg-orange text-orange-foreground @elseif($days<=30) bg-warning text-warning-foreground @else bg-success text-success-foreground @endif">@if($days<0) Vencido @else {{ $days }} dias @endif</span></td>
                        <td class="px-4 py-3 text-right hidden md:table-cell">R$ {{ number_format($domain->annual_cost, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('domains.edit', $domain) }}" class="p-1 rounded hover:bg-muted"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>
                                <form action="{{ route('domains.renew', $domain) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="new_date" value="{{ \Carbon\Carbon::parse($domain->expiration_date)->addYear()->format('Y-m-d') }}">
                                    <button type="submit" class="p-1 rounded hover:bg-muted"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
                                </form>
                                <form action="{{ route('domains.destroy', $domain) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este domínio?')">@csrf @method('DELETE')<button type="submit" class="p-1 rounded hover:bg-muted"><svg class="h-3.5 w-3.5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 text-sm text-muted-foreground">{{ $domains->count() }} domínio(s)</div>
</div>

<script>
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#domains-table-body tr');
        rows.forEach(row => {
            let name = row.cells[0].innerText.toLowerCase();
            let registrar = row.cells[1] ? row.cells[1].innerText.toLowerCase() : '';
            row.style.display = (name.includes(filter) || registrar.includes(filter)) ? '' : 'none';
        });
    });
</script>
@endsection
INDEX

# =============================================
# 5. Rota de dashboard (adicional)
# =============================================
echo "
Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DomainController::class, 'dashboard'])->name('domains.dashboard');
" >> routes/web.php

# =============================================
# 6. Adicionar método dashboard no controller
# =============================================
echo "
public function dashboard()
{
    \$domains = Domain::all();
    return view('admin.domains.dashboard', compact('domains'));
}
" >> app/Http/Controllers/Admin/DomainController.php

echo "✅ Arquivos Blade criados com sucesso!"