<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Besindo App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-54 bg-gray-900 text-white flex flex-col shadow-xl">
            <div class="h-16 flex items-center justify-center border-b border-gray-700">
                <h1 class="text-2xl font-bold text-white">BESINDO</h1>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
    
    <a href="{{ route('dashboard') }}" 
       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <i class="fas fa-home w-6"></i>
        <span class="text-xs font-medium">Dashboard</span>
    </a>

    <a href="{{ route('master.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('master.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <i class="fas fa-database w-6"></i>
        <span class="text-xs font-medium">Data Master</span>
    </a>

    <a href="{{ route('sales.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('sales.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <i class="fas fa-cash-register w-6"></i>
        <span class="text-xs font-medium">Penjualan</span>
    </a>

    <a href="{{ route('purchases.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('purchases.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <i class="fas fa-shopping-bag w-6"></i>
        <span class="text-xs font-medium">Pembelian</span>
    </a>

    <a href="{{ route('finance.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('finance.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <i class="fas fa-wallet w-6"></i>
        <span class="text-xs font-medium">Keuangan</span>
    </a>

    <a href="{{ route('reports.index') }}" 
       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <i class="fas fa-file-lines w-6"></i>
        <span class="text-xs font-medium">Laporan</span>
    </a>

</nav>

            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center text-sm font-bold">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">Role: {{ ucfirst(Auth::user()->role->name) }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded flex items-center justify-center gap-2 transition">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
            {{ $slot }}
        </main>

    </div>

</body>
</html>