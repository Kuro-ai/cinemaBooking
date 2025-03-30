<div>
    <!-- Movie List Section -->
    <div class="max-w-6xl mx-auto p-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Movies</h3>
        <form class="max-w-lg mx-auto mb-6" role="search">
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="🔍 Search by movie title..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:outline-none focus:ring focus:ring-blue-500"
            />
        </form>
        @foreach([['Now Showing', $nowShowing], ['Upcoming Movies', $upcomingMovies]] as [$category, $movies])
            @if(!empty($movies) && count($movies) > 0 && !$selectedMovie)
                <div class="mb-8">
                    <h4 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">{{ $category }}</h4>
                    <div class="relative ">
                        <div class="swiper-container overflow-hidden">
                            <div class="swiper-wrapper ">
                                @foreach($movies as $movie)
                                <div class="swiper-slide bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md dark:shadow-lg text-center relative cursor-pointer !w-auto flex-shrink-0"
                                wire:click="selectMovie({{ $movie->id }})">
                               <!-- Movie Poster -->
                               <div class="relative w-full max-w-[200px] aspect-[2/3] rounded-md overflow-hidden mx-auto">
                                   @if ($movie->image_path)
                                       <img src="{{ asset('storage/' . $movie->image_path) }}" 
                                            alt="{{ $movie->title }}" 
                                            class="w-full h-full object-cover object-center">
                                   @else
                                       <div class="w-full h-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                                           No Image Available
                                       </div>
                                   @endif
                                   <!-- Language Tag (Bottom Left) -->
                                   <span class="absolute bottom-2 left-2 bg-gray-900 text-white text-xs px-2 py-1 rounded-md dark:bg-gray-100 dark:text-gray-900">
                                       {{ $movie->language }}
                                   </span>
                               </div>
                           
                               <!-- Movie Info -->
                               <div class="w-full max-w-[200px] mx-auto">
                                   <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                       {{ \Carbon\Carbon::parse($movie->created_at)->year }} | 
                                       @if (!empty($movie->duration))
                                           @php
                                               $durationParts = explode(':', $movie->duration);
                                               $hours = isset($durationParts[0]) ? $durationParts[0] . 'h' : '0h';
                                               $minutes = isset($durationParts[1]) ? $durationParts[1] . 'm' : '0m';
                                           @endphp
                                           {{ $hours }} {{ $minutes }},
                                       @else
                                           N/A,
                                       @endif
                                       {{ $movie->genre }}
                                   </p>
                                   <p class="mt-1 text-center font-medium text-gray-900 dark:text-gray-100">{{ $movie->title }}</p>
                               </div>
                           </div>
                           
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-button-prev absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 p-2 rounded-full cursor-pointer"></div>
                        <div class="swiper-button-next absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 p-2 rounded-full cursor-pointer"></div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <!-- Movie Details Section (Visible When Movie Is Selected) -->
    @if($selectedMovie)
    <div class="mt-8 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <!-- Movie Details Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Movie Image -->
            <div class="relative w-full h-80 rounded-md overflow-hidden">
                @if ($selectedMovie->image_path)
                    <img src="{{ asset('storage/' . $selectedMovie->image_path) }}" alt="{{ $selectedMovie->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                        No Image Available
                    </div>
                @endif
            </div>
            <!-- Movie Details -->
            <div>
                <h4 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $selectedMovie->title }}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Release Date: {{ \Carbon\Carbon::parse($selectedMovie->created_at)->format('M d, Y') }} |
                    Duration: {{ $selectedMovie->duration }} |
                    Genre: {{ $selectedMovie->genre }} |
                    Language: {{ $selectedMovie->language }}
                </p>              
                <p class="mt-4 font-semibold text-gray-900 dark:text-gray-100">Director: {{ $selectedMovie->director }}</p>
                <p class="mt-4 text-gray-700 dark:text-gray-300">
                    {{ $selectedMovie->description ?? 'This movie does not have a description yet. Please stay tuned for more information!' }}
                </p>
                <div>
                    <button 
                        wire:click="fetchTrailer" 
                        class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                    >
                        Watch Trailer
                    </button>
                </div>
            </div>
        </div>

        <!-- Theatres Showing This Movie -->
        <div class="mt-6">
            <h5 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Theatres Showing This Movie:</h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                @foreach($selectedMovie->theatres->unique('id') as $theatre)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg shadow-black overflow-hidden">
                        <div class="p-4 flex items-center">
                            <!-- Theatre Image -->
                            <div class="w-24 h-24 rounded-full overflow-hidden">
                                @if ($theatre->image_path)
                                    <img src="{{ asset('storage/' . $theatre->image_path) }}" alt="{{ $theatre->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                                        No Image Available
                                    </div>
                                @endif
                            </div>
            
                            <!-- Theatre Details -->
                            <div class="ml-4">
                                <p class="text-gray-800 dark:text-gray-100 text-sm">{{ $theatre->cinema->city }}</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100 text-lg">{{ $theatre->name }} 
                                    <span class="text-gray-600 dark:text-gray-400">({{ $theatre->cinema->location }})</span>
                                </p>
                                <div class="mt-4">
                                    <button wire:click="startBooking({{ $selectedMovie->id }})" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                                        Show Schedule
                                    </button>                                    
                                </div>
                            </div>

                            
                        </div>
                    </div>
                @endforeach
            </div>            
        </div>
    </div>
    @endif
</div>