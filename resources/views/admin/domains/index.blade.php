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
            <form action="{{ route('domains.import') }}" method="POST" enctype="multipart/form-data" class="inline-flex items-center">
                @csrf
                <input type="file" name="file" accept=".csv" class="hidden" id="csvfile">
                <label for="csvfile" class="inline-flex cursor-pointer items-center justify-center rounded-md border px-3 py-2 text-sm">Importar CSV</label>
            </form>
            <a href="{{ route('domains.export') }}" class="inline-flex items-center justify-center rounded-md border px-3 py-2 text-sm">Exportar CSV</a>
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
    <div class="mt-4 text-sm text-muted-foreground">Mostrando {{ $domains->total() }} domínio(s) — página {{ $domains->currentPage() }} de {{ $domains->lastPage() }}</div>
    <div class="mt-4">{{ $domains->links() }}</div>
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
<script>
    const fileInput = document.getElementById('csvfile');
    if (fileInput) {
        fileInput.addEventListener('change', function(){
            this.form.submit();
        });
    }
</script>
@endsection
