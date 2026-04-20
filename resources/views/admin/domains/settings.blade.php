@extends('layouts.admin')

@section('content')
    <div class="rounded-xl border bg-card p-5">
        <h2 class="text-sm font-semibold mb-4">Configurações</h2>

        <div class="space-y-6">
            <div class="rounded-lg border p-4">
                <h3 class="font-medium mb-2">Configurações de Notificação</h3>
                <p class="text-sm text-muted-foreground mb-3">Configure os e-mails e prazos para alertas de vencimento.</p>

                <form action="{{ route('domains.settings.save') }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">E-mails para alerta</label>
                            <input type="text" name="alert_emails"
                                value="{{ old('alert_emails', $settings['alert_emails'] ?? '') }}"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                placeholder="admin@domain.com, financeiro@domain.com">
                            <p class="text-xs text-muted-foreground mt-1">Separe os e-mails por vírgula</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Dias de antecedência para alertas</label>
                            <input type="text" name="alert_days"
                                value="{{ old('alert_days', $settings['alert_days'] ?? '30,15,7,1') }}"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <p class="text-xs text-muted-foreground mt-1">Separe os valores por vírgula</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="email_notif" id="email_notif"
                                {{ isset($settings['email_notif']) && $settings['email_notif'] ? 'checked' : '' }}>
                            <label for="email_notif">Notificações por e-mail</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="telegram_notif" id="telegram_notif"
                                {{ isset($settings['telegram_notif']) && $settings['telegram_notif'] ? 'checked' : '' }}>
                            <label for="telegram_notif">Notificações por Telegram</label>
                        </div>

                        <button type="submit"
                            class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">
                            Salvar configurações
                        </button>
                    </div>
                </form>
                {{-- flash handled by layout toast --}}
                alert('{{ session('success') }}');
                });
                </script>
                @endif
            </div>

            <div class="rounded-lg border p-4">
                <h3 class="font-medium mb-2">Informações do Sistema</h3>
                <div class="space-y-2 text-sm">
                    <p><strong>Versão:</strong> 1.0.0</p>
                    <p><strong>Total de domínios:</strong> {{ \App\Models\Domain::count() }}</p>
                    <p><strong>Última atualização:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
