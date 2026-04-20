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
            <header
                class="sticky top-0 z-30 h-16 bg-card/80 backdrop-blur-md border-b border-border flex items-center justify-between px-4 sm:px-6">
                <div class="ml-10 lg:ml-0">
                    @if (isset($breadcrumb) && count($breadcrumb))
                        <p class="text-xs text-muted-foreground mb-0.5">{{ implode(' / ', $breadcrumb) }}</p>
                    @endif
                    <h1 class="text-lg font-semibold">{{ $title ?? 'Domain Manager' }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button class="relative rounded-lg p-2 hover:bg-muted transition-colors">
                        <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-danger"></span>
                    </button>
                    <div
                        class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-primary-foreground">
                        AD</div>
                </div>
            </header>
            <main class="flex-1 p-4 sm:p-6 animate-fade-in">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <div id="toast"
        class="hidden fixed bottom-6 right-6 z-50 max-w-xs rounded-lg bg-card/90 border border-border px-4 py-2 shadow-lg text-sm">
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const msg = {!! json_encode(session('success') ?? (session('status') ?? null)) !!};
            if (msg) {
                const t = document.getElementById('toast');
                t.textContent = msg;
                t.classList.remove('hidden');
                t.style.opacity = 0;
                // fade in
                let opa = 0;
                const fin = setInterval(() => {
                    opa += 0.1;
                    t.style.opacity = opa;
                    if (opa >= 1) clearInterval(fin);
                }, 30);
                setTimeout(() => {
                    // fade out
                    let op = 1;
                    const fout = setInterval(() => {
                        op -= 0.1;
                        t.style.opacity = op;
                        if (op <= 0) {
                            clearInterval(fout);
                            t.classList.add('hidden');
                        }
                    }, 30);
                }, 3500);
            }
        });
    </script>
    <!-- Generic confirmation modal -->
    <div id="confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md rounded bg-card p-4 border border-border">
            <h3 id="confirm-modal-title" class="text-lg font-semibold">Confirme a ação</h3>
            <p id="confirm-modal-message" class="text-sm text-muted-foreground mt-2">Tem certeza?</p>
            <div class="mt-4 flex justify-end gap-2">
                <button id="confirm-modal-cancel" class="px-3 py-2 rounded border">Cancelar</button>
                <button id="confirm-modal-ok" class="px-3 py-2 rounded bg-primary text-primary-foreground">OK</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let pendingForm = null;
            const modal = document.getElementById('confirm-modal');
            const msgEl = document.getElementById('confirm-modal-message');
            const btnOk = document.getElementById('confirm-modal-ok');
            const btnCancel = document.getElementById('confirm-modal-cancel');

            function showConfirm(message, form) {
                pendingForm = form || null;
                msgEl.textContent = message || 'Tem certeza?';
                modal.classList.remove('hidden');
            }

            function hideConfirm() {
                pendingForm = null;
                modal.classList.add('hidden');
            }

            document.body.addEventListener('click', function(e) {
                const el = e.target.closest('[data-confirm]');
                if (!el) return;
                // If it's a link, show modal and on OK follow link
                const message = el.getAttribute('data-confirm');
                const nearestForm = el.closest('form');
                if (nearestForm) {
                    e.preventDefault();
                    showConfirm(message, nearestForm);
                } else if (el.tagName === 'A') {
                    e.preventDefault();
                    showConfirm(message, el);
                }
            });

            // intercept direct form submits that have class needs-confirm
            document.body.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.classList && form.classList.contains('needs-confirm')) {
                    e.preventDefault();
                    const message = form.getAttribute('data-confirm') || 'Tem certeza?';
                    showConfirm(message, form);
                }
            }, true);

            btnCancel.addEventListener('click', function() {
                hideConfirm();
            });

            btnOk.addEventListener('click', function() {
                if (!pendingForm) { hideConfirm(); return; }
                // if pendingForm is an element (link), follow href
                if (pendingForm.tagName && pendingForm.tagName.toLowerCase() === 'a') {
                    const href = pendingForm.getAttribute('href');
                    hideConfirm();
                    if (href) window.location.href = href;
                    return;
                }
                // otherwise submit the form
                pendingForm.submit();
                hideConfirm();
            });
        });
    </script>
</body>

</html>
