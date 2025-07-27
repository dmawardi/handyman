<section class="py-16 bg-background">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary sm:text-4xl">
                Service Areas
            </h2>
            <p class="mt-4 text-lg text-accent max-w-3xl mx-auto">
                We proudly serve communities across Bali with reliable maintenance and repair services. 
                Our skilled professionals are ready to help you in these key areas.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Map Image -->
            <div class="order-2 lg:order-1">
                <div class="relative">
                    <img 
                        src="{{ asset('images/home/service-map.png') }}" 
                        alt="Bali Service Areas Map showing Canggu, Seminyak, Kerobokan, Umalas, and Pererenan"
                        class="w-full h-auto rounded-lg shadow-lg"
                    >
                </div>
            </div>

            <!-- Service Areas Content -->
            <div class="order-1 lg:order-2">
                <div class="order-1 lg:order-2">
                <div class="bg-secondary p-6 rounded-lg shadow-sm border">
                    <ul class="space-y-4 text-background text-lg font-medium">
                        <li class="flex items-center">
                            Canggu
                        </li>
                        <li class="flex items-center">
                            Seminyak
                        </li>
                        <li class="flex items-center">
                            Kerobokan
                        </li>
                        <li class="flex items-center">
                            Umalas
                        </li>
                        <li class="flex items-center">
                            Pererenan
                        </li>
                    </ul>
                </div>

                    <div class="mt-6 bg-primary bg-opacity-10 p-6 rounded-lg">
                        <p class="text-primary font-medium">
                            Don't see your area listed? We're expanding our service coverage across Bali. 
                            <a href="{{ route('job-requests.create') }}" class="text-primary hover:text-secondary underline">
                                Contact us
                            </a> to check if we can help you!
                        </p>
                    </div>
            </div>
            </div>
        </div>
    </div>
</section>