@props(['services' => [
    [
        'title' => 'Plumbing',
        'description' => 'From fixing leaks to new installations, our expert plumbers have you covered.',
        'image' => '/images/services/plumbing.jpeg',
    ],
    [
        'title' => 'Electrical',
        'description' => 'Safe and certified electrical repairs, installations, and inspections.',
        'image' => '/images/services/electrical.jpeg',
    ],
    [
        'title' => 'Painting',
        'description' => 'Interior and exterior painting services to give your space a fresh new look.',
        'image' => '/images/services/painting.jpeg',
    ],
    [
        'title' => 'Pool Maintenance',
        'description' => 'Regular cleaning and maintenance to keep your pool sparkling clean.',
        'image' => '/images/services/pool.jpeg',
    ],
    [
        'title' => 'Cleaning',
        'description' => 'Professional cleaning services for homes and offices.',
        'image' => '/images/services/cleaning.jpeg',
    ],
    [
        'title' => 'Gardening',
        'description' => 'Expert gardening and landscaping services to beautify your outdoor space.',
        'image' => '/images/services/gardening.jpeg',
    ],
    [
        'title' => 'General Repairs',
        'description' => 'Handyman services for all types of repairs around your home or business.',
        'image' => '/images/services/general-repairs.jpeg',
    ],
    [
        'title' => 'Air Conditioning',
        'description' => 'Installation, maintenance, and repair of air conditioning systems.',
        'image' => '/images/services/air-conditioning.jpeg',
    ],
]])

<section class="py-16 bg-background">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach ($services as $service)
                <div 
                    class="relative bg-cover bg-center rounded-xl shadow hover:shadow-lg transition mb-6 h-64 flex items-center justify-center text-center" 
                    style="background-image: url('{{ $service['image'] }}');">
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-xl"></div>
                    
                    <!-- Content -->
                    <div class="relative z-10 text-background px-4">
                        <h3 class="text-2xl font-semibold mb-2">{{ $service['title'] }}</h3>
                        <p class="text-sm">{{ $service['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>