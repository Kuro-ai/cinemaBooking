<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-900 py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-6 max-w-7xl">
            @php
                $menuItems = [
                    [
                        'route' => 'admin.cinemas',
                        'label' => 'Cinemas',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="black" class="bi bi-camera-reels" viewBox="0 0 16 16">
                                        <path d="M6 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0M1 3a2 2 0 1 0 4 0 2 2 0 0 0-4 0"/>
                                        <path d="M9 6h.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 7.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 16H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm6 8.73V7.27l-3.5 1.555v4.35zM1 8v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1"/>
                                        <path d="M9 6a3 3 0 1 0 0-6 3 3 0 0 0 0 6M7 3a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                                    </svg>'
                    ],
                    [
                        'route' => 'admin.theatres',
                        'label' => 'Theatres',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 10s3-3 3-8" />
                                        <path d="M22 10s-3-3-3-8" />
                                        <path d="M10 2c0 4.4-3.6 8-8 8" />
                                        <path d="M14 2c0 4.4 3.6 8 8 8" />
                                        <path d="M2 10s2 2 2 5" />
                                        <path d="M22 10s-2 2-2 5" />
                                        <path d="M8 15h8" />
                                        <path d="M2 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1" />
                                        <path d="M14 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1" />
                                    </svg>'
                    ],

                    ['route' => 'admin.movies', 'label' => 'Movies', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 -960 960 960"><path d="M160-240v-320zm0 80q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800l80 160h120l-80-160h80l80 160h120l-80-160h80l80 160h120l-80-160h120q33 0 56.5 23.5T880-720v160H160v320h320v80zm400 40v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22-4 22.5-13 20.5L683-120zm300-263-37-37zM620-180h38l121-122-18-19-19-18-122 121zm141-141-19-18 37 37z"/></svg>'], // Clapperboard Icon
                    ['route' => 'admin.users', 'label' => 'Users', 'icon' => '<svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                                <path d="M15 19.1276C15.8329 19.37 16.7138 19.5 17.625 19.5C19.1037 19.5 20.5025 19.1576 21.7464 18.5478C21.7488 18.4905 21.75 18.4329 21.75 18.375C21.75 16.0968 19.9031 14.25 17.625 14.25C16.2069 14.25 14.956 14.9655 14.2136 16.0552M15 19.1276V19.125C15 18.0121 14.7148 16.9658 14.2136 16.0552M15 19.1276C15 19.1632 14.9997 19.1988 14.9991 19.2343C13.1374 20.3552 10.9565 21 8.625 21C6.29353 21 4.11264 20.3552 2.25092 19.2343C2.25031 19.198 2.25 19.1615 2.25 19.125C2.25 15.6042 5.10418 12.75 8.625 12.75C11.0329 12.75 13.129 14.085 14.2136 16.0552M12 6.375C12 8.23896 10.489 9.75 8.625 9.75C6.76104 9.75 5.25 8.23896 5.25 6.375C5.25 4.51104 6.76104 3 8.625 3C10.489 3 12 4.51104 12 6.375ZM20.25 8.625C20.25 10.0747 19.0747 11.25 17.625 11.25C16.1753 11.25 15 10.0747 15 8.625C15 7.17525 16.1753 6 17.625 6C19.0747 6 20.25 7.17525 20.25 8.625Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                                </svg>
                                                                                                '], // User Icon
                    ['route' => 'admin.seats', 'label' => 'Seats', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 -960 960 960"><path d="M160-120v-240h640v240h-80v-160H240v160zm20-280q-25 0-42.5-17.5T120-460t17.5-42.5T180-520t42.5 17.5T240-460t-17.5 42.5T180-400m100 0v-360q0-33 23.5-56.5T360-840h240q33 0 56.5 23.5T680-760v360zm500 0q-25 0-42.5-17.5T720-460t17.5-42.5T780-520t42.5 17.5T840-460t-17.5 42.5T780-400m-420-80h240v-280H360zm0 0h240z"/></svg>'], // Seat Icon
                    ['route' => 'admin.manage-schedules', 'label' => 'Schedules', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 -960 960 960"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80zm0-80h560v-400H200zm0-480h560v-80H200zm0 0v-80zm280 240q-17 0-28.5-11.5T440-440t11.5-28.5T480-480t28.5 11.5T520-440t-11.5 28.5T480-400m-160 0q-17 0-28.5-11.5T280-440t11.5-28.5T320-480t28.5 11.5T360-440t-11.5 28.5T320-400m320 0q-17 0-28.5-11.5T600-440t11.5-28.5T640-480t28.5 11.5T680-440t-11.5 28.5T640-400M480-240q-17 0-28.5-11.5T440-280t11.5-28.5T480-320t28.5 11.5T520-280t-11.5 28.5T480-240m-160 0q-17 0-28.5-11.5T280-280t11.5-28.5T320-320t28.5 11.5T360-280t-11.5 28.5T320-240m320 0q-17 0-28.5-11.5T600-280t11.5-28.5T640-320t28.5 11.5T680-280t-11.5 28.5T640-240"/></svg>'], // Calendar Icon
                    ['route' => 'admin.manage-foods', 'label' => 'Foods', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 -960 960 960"><path d="M533-440q-32-45-84.5-62.5T340-520t-108.5 17.5T147-440zM40-360q0-109 91-174.5T340-600t209 65.5T640-360zm0 160v-80h600v80zM720-40v-80h56l56-560H450l-10-80h200v-160h80v160h200L854-98q-3 25-22 41.5T788-40zm0-80h56zM80-40q-17 0-28.5-11.5T40-80v-40h600v40q0 17-11.5 28.5T600-40zm260-400"/></svg>'], // Food Icon
                    ['route' => 'admin.bookings', 'label' => 'Bookings', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 -960 960 960"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h480q33 0 56.5 23.5T800-800v640q0 33-23.5 56.5T720-80zm0-80h480v-640h-80v280l-100-60-100 60v-280H240zm0 0v-640zm200-360 100-60 100 60-100-60z"/></svg>'], // Ticket Icon
                ];
            @endphp

            @foreach($menuItems as $item)
                <a href="{{ route($item['route']) }}" 
                    class="flex flex-col items-center justify-center p-6 w-48 h-48 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="mb-6">{!! $item['icon'] !!}</div> 
                    <span class="text-lg text-black font-semibold">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
