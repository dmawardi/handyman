@props(['title' => 'Ready to Fix Your Home Problems?', 'description' => 'Our professional handyman team is just a click away. Get your free quote today and solve those nagging home repair issues.', 'buttonText' => 'Request Service Now'])
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                {{ $title ?? 'Ready to Fix Your Home Problems?' }}
            </h2>
            <p class="mt-4 text-lg">
                {{ $description ?? 'Our professional handyman team is just a click away. Get your free quote today and solve those nagging home repair issues.' }}
            </p>
            <div class="mt-8">
                <a href="{{ route('job-requests.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-gray-100 hover:text-blue-800 transition ease-in-out duration-150 shadow-md">
                    {{ $buttonText ?? 'Request Service Now' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>