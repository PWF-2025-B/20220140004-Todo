<x-app-layout>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
    </head>

    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Formulir Pencarian -->
                <form method="GET" action="{{ route('user.index') }}" class="mb-6 flex space-x-3">
                    <input type="text" name="search" placeholder="Cari pengguna..."
                        class="w-full px-6 py-3 text-gray-700 bg-gray-100 rounded-lg focus:outline-none"
                        value="{{ request('search') }}" />
                    <button type="submit"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Tabel -->
            <div class="relative overflow-x-auto">
                <table class="w-full text-base text-gray-700 dark:text-gray-300">
                    <thead class="text-sm uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-8 py-5 text-center">ID</th>
                            <th scope="col" class="px-8 py-5 text-left">NAMA</th>
                            <th scope="col" class="px-8 py-5 text-right">TODO</th>
                            <th scope="col" class="px-8 py-5 text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $data)
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-8 py-5 text-center text-blue-600 dark:text-blue-400">
                                    {{ $data->id }}
                                </td>
                                <td class="px-8 py-5 text-left">
                                    {{ $data->name }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-blue-600 dark:text-blue-300">
                                        {{ $data->todos->where('is_done', true)->count() }} ({{ $data->todos->count() }})
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="4" class="px-8 py-5 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-6 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        Menampilkan {{ $users->firstItem() ?? 0 }} hingga {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} hasil
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>