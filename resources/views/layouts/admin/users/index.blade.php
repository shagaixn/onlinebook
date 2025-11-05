@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">👥 Бүртгэлтэй хэрэглэгчид</h1>
            
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white shadow rounded-xl overflow-hidden">
        <thead class="bg-blue-50 border-b">
            <tr>
                <th class="text-left py-3 px-4">#</th>
                <th class="text-left py-3 px-4">ID</th>
                <th class="text-left py-3 px-4">Нэр</th>
                <th class="text-left py-3 px-4">Имэйл</th>
                <th class="text-left py-3 px-4">Role</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-b hover:bg-blue-50">
                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                <td class="py-3 px-4">{{ $user->id }}</td>
                <td class="py-3 px-4">{{ $user->name }}</td>
                <td class="py-3 px-4">{{ $user->email }}</td>
                <td class="py-3 px-4">{{ $user->role }}</td>
                <td class="py-3 px-4">
                    
                        @csrf
                        @method('DELETE')
                        
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection