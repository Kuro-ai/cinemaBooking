<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>
    @if (session()->has('success'))
        <div class="dark:bg-green-100 border dark:border-green-400 dark:text-green-700 px-4 py-3 rounded relative mt-3 mb-3 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="dark:bg-red-100 border dark:border-red-400 dark:text-red-700 px-4 py-3 rounded relative mt-3 mb-3 text-center">
            {{ session('error') }}
        </div>
    @endif
    <div class="flex justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-900 py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-6 max-w-7xl">
            @php
                $menuItems = [
                    [
                        'route' => 'movies.index',
                        'label' => 'Movies',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="text-gray-800 dark:text-gray-200" viewBox="0 0 16 16"><path d="M6 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0M1 3a2 2 0 1 0 4 0 2 2 0 0 0-4 0"/><path d="M9 6h.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 7.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 16H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm6 8.73V7.27l-3.5 1.555v4.35zM1 8v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1"/><path d="M9 6a3 3 0 1 0 0-6 3 3 0 0 0 0 6M7 3a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/></svg>'
                    ],
                    // [
                    //     'route' => 'booking.index',
                    //     'label' => 'Bookings',
                    //     'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="text-gray-800 dark:text-gray-200" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 10s3-3 3-8"/><path d="M22 10s-3-3-3-8"/><path d="M10 2c0 4.4-3.6 8-8 8"/><path d="M14 2c0 4.4 3.6 8 8 8"/><path d="M2 10s2 2 2 5"/><path d="M22 10s-2 2-2 5"/><path d="M8 15h8"/><path d="M2 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1"/><path d="M14 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1"/></svg>'
                    // ],
                    [
                        'route' => 'foods.index',
                        'label' => 'Food & Drinks',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="text-gray-800 dark:text-gray-200" viewBox="0 -960 960 960"><path d="M160-240v-320zm0 80q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800l80 160h120l-80-160h80l80 160h120l-80-160h80l80 160h120l-80-160h120q33 0 56.5 23.5T880-720v160H160v320h320v80z"/></svg>'
                    ]
                ];
            @endphp


            @foreach($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex flex-col items-center justify-center p-6 w-48 h-48 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300 {{ request()->routeIs($item['route']) ? 'ring-4 ring-indigo-400' : '' }}"
                   aria-label="{{ $item['label'] }}">
                    <div class="mb-6">{!! $item['icon'] !!}</div>
                    <span class="text-lg text-black font-semibold dark:text-gray-200">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
