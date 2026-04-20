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
