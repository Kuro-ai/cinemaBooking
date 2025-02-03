<div class="max-w-6xl mx-auto p-6">
    <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Available Foods</h3>

    @foreach(['Food', 'Drink'] as $category)
        @if(isset($foods[$category]))
            <div class="mb-8">
                <h4 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-200">{{ $category }}</h4>
                <div class="relative">
                    <div class="swiper-container overflow-hidden">
                        <div class="swiper-wrapper ">
                            @foreach($foods[$category] as $food)
                                <div class="swiper-slide bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md dark:shadow-lg">
                                    @if ($food->image_path)
                                        <img src="{{ asset('storage/' . $food->image_path) }}" alt="{{ $food->name }}" class="w-full h-full object-cover rounded-md">
                                    @endif
                                    <p class="mt-2 text-center font-medium text-gray-900 dark:text-gray-100">{{ $food->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Navigation Buttons -->
                    <div class="swiper-button-prev absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 p-2 rounded-full cursor-pointer"></div>
                    <div class="swiper-button-next absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 dark:bg-gray-100 text-white dark:text-gray-900 p-2 rounded-full cursor-pointer"></div>
                
                </div>
            </div>
        @endif
    @endforeach
</div>

