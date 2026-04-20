@extends('layouts.admin')

@section('content')
<div class="rounded-xl border bg-card p-5">
    <h2 class="text-sm font-semibold mb-4">Editar Domínio</h2>
    
    <form method="POST" action="{{ route('domains.update', $domain) }}">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nome do domínio *</label>
                <input type="text" name="name" value="{{ old('name', $domain->name) }}" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Registrador *</label>
                <select name="registrar" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="GoDaddy" @selected($domain->registrar == 'GoDaddy')>GoDaddy</option>
                    <option value="Registro.br" @selected($domain->registrar == 'Registro.br')>Registro.br</option>
                    <option value="Namecheap" @selected($domain->registrar == 'Namecheap')>Namecheap</option>
                    <option value="Cloudflare" @selected($domain->registrar == 'Cloudflare')>Cloudflare</option>
                    <option value="AWS Route53" @selected($domain->registrar == 'AWS Route53')>AWS Route53</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Data de expiração *</label>
                <input type="date" name="expiration_date" value="{{ old('expiration_date', $domain->expiration_date) }}" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Custo anual (R$)</label>
                <input type="number" name="annual_cost" step="0.01" value="{{ old('annual_cost', $domain->annual_cost) }}" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-1">Observações</label>
                <textarea name="notes" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">{{ old('notes', $domain->notes) }}</textarea>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="checkbox" name="auto_renew" id="auto_renew" value="1" @checked($domain->auto_renew)>
                <label for="auto_renew">Renovação automática</label>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                    Atualizar
                </button>
                <a href="{{ route('domains.index') }}" class="rounded-md border border-input px-4 py-2 text-sm font-medium hover:bg-accent">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
