@props(['services' => [
    [
        'title' => 'Plumbing',
        'description' => 'From fixing leaks to new installations, our expert plumbers have you covered.',
        'image' => '/images/services/plumbing.png',
    ],
    [
        'title' => 'Electrical',
        'description' => 'Safe and certified electrical repairs, installations, and inspections.',
        'image' => '/images/services/electrical.png',
    ],
    [
        'title' => 'Painting',
        'description' => 'Interior and exterior painting services to give your space a fresh new look.',
        'image' => '/images/services/painting.png',
    ],
    [
        'title' => 'Carpentry',
        'description' => 'Custom furniture, cabinetry, and repairs by skilled carpenters.',
        'image' => '/images/services/carpentry.png',
    ],
    [
        'title' => 'Cleaning',
        'description' => 'Professional cleaning services for homes and offices.',
        'image' => '/images/services/cleaning.png',
    ],
    [
        'title' => 'Gardening',
        'description' => 'Expert gardening and landscaping services to beautify your outdoor space.',
        'image' => '/images/services/gardening.png',
    ],
    [
        'title' => 'Pest Control',
        'description' => 'Effective pest control solutions to keep your home pest-free.',
        'image' => '/images/services/pest-control.png',
    ],
    [
        'title' => 'General Repairs',
        'description' => 'Handyman services for all types of repairs around your home or business.',
        'image' => '/images/services/general-repairs.png',
    ],
    [
        'title' => 'Air Conditioning',
        'description' => 'Installation, maintenance, and repair of air conditioning systems.',
        'image' => '/images/services/air-conditioning.png',
    ],
]])

<section class="py-16 bg-background">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @for ($index = 0; $index < count($services); $index++)
                @if ($index % 3 == 0 && $index != 0)
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @endif
                <div class="bg-secondary p-6 rounded-xl shadow hover:shadow-lg transition mb-6"> <!-- Added margin-bottom -->
                    <div class="mb-4">
                        <img src="{{ $services[$index]['image'] }}" alt="{{ $services[$index]['title'] }}" class="h-16 mx-auto">
                    </div>
                    <h3 class="text-xl font-semibold text-center text-primary mb-2">{{ $services[$index]['title'] }}</h3>
                    <p class="text-background text-center">{{ $services[$index]['description'] }}</p>
                </div>
            @endfor
        </div>
    </div>
</section>