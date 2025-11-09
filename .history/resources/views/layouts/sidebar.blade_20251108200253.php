{{-- <aside class="w-64 bg-white h-screen shadow-md fixed">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold text-blue-600">📘 Mbook Admin</h2>
    </div>

    <nav class="mt-6">
        <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">🏠 Dashboard</a>
        <a href="{{route('admin.users.index')}}" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">👥 Хэрэглэгчид</a>
        <a href="{{route('admin.books.index')}}" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">📚 Номнууд</a>
        <a href="" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">⚙️ Тохиргоо</a>
        <a href="" class="block px-6 py-3 text-gray-700 hover:bg-blue-100 border-t mt-4">🚪 Гарах</a>
    </nav>
</aside> --}}
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 bg-white h-screen shadow-md fixed">
        <div class="p-6 border-b">
            <a href="{{ route('admin.dashboard') }}"><h2 class="text-2xl font-bold text-blue-600">📘 Mbook Admin</h2></a>
        </div>

        <nav class="mt-6">
            <a href="" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">🏠 Book chart</a>
            <a href="{{ route('admin.users.index') }}" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">👥 Хэрэглэгчид</a>
            <a href="{{ route('admin.books.index') }}" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">📚 Номнууд</a>
            <a href="{{ route('layouts.authors.index') }}" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">📚 Номнууд</a>
            <a href="" class="block px-6 py-3 text-gray-700 hover:bg-blue-100">⚙️ Тохиргоо</a>
           <!-- resources/views/layouts/sidebar.blade.php -->
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="block w-full text-left px-6 py-3 text-gray-700 hover:bg-blue-100 border-t mt-4">
        🚪 Гарах
    </button>
</form>
        </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 ml-64 p-6">
        @yield('content') <!-- ⚠️ Гол контент энд гарна -->
    </main>

</body>
</html>

