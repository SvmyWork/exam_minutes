<!-- Access parent Alpine.js scope directly -->
<div 
    x-cloak
    x-show="showNotification"
    x-show="showNotification"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="fixed top-5 right-5 w-80 bg-white text-gray-900 p-4 rounded-lg shadow-lg flex items-start gap-3 border border-gray-200"
    style="display: none;"
>
    <!-- Icon -->
    <div class="mt-1" x-show="icon === 'success'">
        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <div class="mt-1" x-show="icon === 'error'">
        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </div>

    <div class="mt-1" x-show="icon === 'warning'">
        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5 19h14l-7-14-7 14z"></path>
        </svg>
    </div>

    <!-- Message -->
    <div class="flex-1">
        <p class="font-semibold" x-text="title"></p>
        <span x-text="note"></span>
    </div>

    <!-- Close button -->
    <button @click="showNotification = false" class="text-gray-400 hover:text-gray-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
