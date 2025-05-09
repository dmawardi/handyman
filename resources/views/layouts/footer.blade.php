<!-- filepath: /Users/d/Web Development/projects/handyman/resources/views/layouts/footer.blade.php -->
<footer class="bg-primary text-background py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Business Details -->
            <div>
                <h3 class="text-lg font-semibold mb-4">About Us</h3>
                <p class="text-sm">
                    RumahFix is your trusted partner for all handyman services. From plumbing to electrical work, we ensure quality and reliability in every job.
                </p>
                <p class="mt-4 text-sm">
                    <strong>Service Areas:</strong> Canggu, Seminyak, Umalas, Kerobokan, Pereranan<br>
                    @php
                        $phone = env('BUSINESS_PHONE');
                        // Place spaces in the phone number for better readability
                        $formattedPhone = implode(' ', str_split($phone, 3));
                    @endphp
                    <strong>Phone:</strong> +{{ $formattedPhone }}<br>
                    <strong>Email:</strong> support@rumahfix.com
                </p>
            </div>

            <!-- Sitemap -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Sitemap</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                    <li><a href="{{ route('services') }}" class="hover:underline">Services</a></li>
                    <li><a href="{{ route('job-requests.create') }}" class="hover:underline">Book a Service</a></li>
                </ul>
            </div>

            <!-- WhatsApp Link -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Get in Touch</h3>
                <p class="text-sm mb-4">
                    Need assistance? Chat with us on WhatsApp for quick support.
                </p>
                <a href="https://wa.me/{{ env('BUSINESS_PHONE') }}?text={{ urlencode('Hello RumahFix! I need assistance with your services.') }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md shadow-lg transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16.72 13.06c-.29-.15-1.7-.84-1.96-.93-.26-.1-.45-.15-.64.15s-.74.93-.91 1.12c-.17.2-.34.22-.63.07-.29-.15-1.22-.45-2.32-1.43-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.59.13-.13.29-.34.43-.51.14-.17.17-.29.26-.48.09-.2.04-.37-.02-.52-.07-.15-.64-1.54-.88-2.12-.23-.56-.47-.48-.64-.48h-.55c-.2 0-.52.07-.79.37-.26.29-1.04 1.02-1.04 2.5 0 1.48 1.07 2.91 1.22 3.11.15.2 2.09 3.18 5.06 4.46.71.31 1.27.49 1.7.63.71.23 1.36.2 1.87.12.57-.09 1.7-.7 1.94-1.38.24-.67.24-1.25.17-1.38-.07-.13-.26-.2-.55-.34z"/>
                    </svg>
                    Chat on WhatsApp
                </a>
            </div>
        </div>

        <div class="mt-8 border-t border-gray-700 pt-4 text-center text-sm">
            <p>&copy; {{ date('Y') }} RumahFix. All rights reserved.</p>
        </div>
    </div>
</footer>