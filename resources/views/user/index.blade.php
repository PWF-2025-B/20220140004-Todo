<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="px-6 pt-6 pb-5 mb-6 md:w-1/2 2xl:w-1/3">
        @if (request('search'))
            <h2 class="pb-3 mb-4 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Search result for: {{ request('search') }}
            </h2>
        @endif
        <form class="flex items-center gap-2 mb-4">
            <div>
                <x-text-input
                    id="search"
                    name="search"
                    type="text"
                    class="w-50"
                    placeholder="Search by name or email"
                    value="{{ request('search') }}"
                    autofocus
                />
            </div>
            <div class="px-6">
                <x-primary-button type="submit">
                    {{ __('Search') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <div class="px-6 mb-8 text-xl text-gray-900 dark:text-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div></div>
            <div>
                @if (session('success'))
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 5000)"
                       class="pb-3 mb-2 text-sm text-green-600 dark:text-green-400">
                        {{ session('success') }}
                    </p>
                @endif
                @if (session('danger'))
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 5000)"
                       class="pb-3 mb-2 text-sm text-red-600 dark:text-red-400">
                        {{ session('danger') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3">Id</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Todo</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                        <td class="px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                            {{ $user->id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p>
                                {{ $user->todos->count() }}
                                <span>
                                    <span class="text-green-600 dark:text-green-400">
                                        ({{ $user->todos->where('is_done', true)->count() }}
                                    </span> /
                                    <span class="text-blue-600 dark:text-blue-400">
                                        {{ $user->todos->where('is_done', false)->count() }})
                                    </span>
                                </span>
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                @if ($user->is_admin)
                                <form action="{{ route('user.removeadmin', $user) }}" method="Post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-blue-600 dark:text-blue-400 whitespace-nowrap">
                                        Remove Admin
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('user.makeadmin', $user) }}" method="Post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-red-600 dark:text-red-400 whitespace-nowrap">
                                        Make Admin
                                    </button>
                                </form>
                                @endif
                                <!-- Delete Button -->
                                <form action="{{ route('user.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 whitespace-nowrap">
                                        Delete
                                    </button>
                                </form>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No data available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-5">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
