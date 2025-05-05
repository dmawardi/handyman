@props([
    'testimonials' => [
        [
            'quote' => 'Quick and professional! I needed my AC fixed and they got it done the same day.',
            'name' => 'Sarah, Seminyak',
        ],
        [
            'quote' => 'Highly recommend. The handyman arrived on time and installed my water heater perfectly.',
            'name' => 'James, Canggu',
        ],
    ],
])

{{-- Testimonials Section --}}
])
<section class="bg-background py-16">
  <div class="container mx-auto px-6">
      <h2 class="text-2xl font-bold text-center text-primary mb-10">Happy Customers</h2>
      <div class="grid md:grid-cols-2 gap-8">
        @for ($index = 0; $index < count($testimonials); $index++)
          @if ($index % 2 == 0 && $index != 0)
            </div>
            <div class="grid md:grid-cols-2 gap-8">
          @endif
          <div class="bg-secondary p-6 rounded-lg shadow hover:shadow-lg transition">
              <p class="mb-4 text-background">"{{ $testimonials[$index]['quote'] }}"</p>
              <p class="font-semibold text-primary">– {{ $testimonials[$index]['name'] }}</p>
          </div>
        @endfor
      </div>
  </div>
</section>