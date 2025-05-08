@props([
    'phoneNumber' => '628113702797', // Default phone number
    'message' => "Hey RumahFix! I’d like to book a service (details below).\nName:\nEmail:\nAddress:\nUrgency(Emergency/48hrs/1week):\nJob Description:" // Default message
])

<a href="https://wa.me/{{ $phoneNumber }}?text={{ urlencode($message) }}" 
   target="_blank" 
   rel="noopener noreferrer"
   class="fixed bottom-5 right-5 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full shadow-lg z-50 transition-all duration-300 flex items-center space-x-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M16.72 13.06c-.29-.15-1.7-.84-1.96-.93-.26-.1-.45-.15-.64.15s-.74.93-.91 1.12c-.17.2-.34.22-.63.07-.29-.15-1.22-.45-2.32-1.43-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.59.13-.13.29-.34.43-.51.14-.17.17-.29.26-.48.09-.2.04-.37-.02-.52-.07-.15-.64-1.54-.88-2.12-.23-.56-.47-.48-.64-.48h-.55c-.2 0-.52.07-.79.37-.26.29-1.04 1.02-1.04 2.5 0 1.48 1.07 2.91 1.22 3.11.15.2 2.09 3.18 5.06 4.46.71.31 1.27.49 1.7.63.71.23 1.36.2 1.87.12.57-.09 1.7-.7 1.94-1.38.24-.67.24-1.25.17-1.38-.07-.13-.26-.2-.55-.34z"/>
    </svg>
    <span>Chat with us</span>
</a>
