<x-app-layout title="RumahFix Bali | Dashboard" canonical="url('/dashboard')">
    
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome Message --}}
            <div class="bg-secondary text-background overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">{{ __("Welcome Back!") }}</h3>
                    <p>{{ __("You're logged in! Here's an overview of your job requests.") }}</p>
                </div>
            </div>

            {{-- Job Requests Overview --}}
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4">{{ __('Your Job Requests') }}</h3>
                    
                    {{-- Job Requests Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-secondary bg-background border border-secondary rounded-lg">
                            <thead class="bg-secondary text-background">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Job Number') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Service Type') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Urgency') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Created') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-secondary">
                                @foreach ($jobRequests as $jobRequest)
                                    <x-job_requests.table-data-row :jobRequest="$jobRequest" />
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $jobRequests->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-secondary text-background overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">{{ __('Quick Actions') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        <a href="{{ route('job-requests.create') }}" class="block bg-primary text-background text-center py-4 rounded-lg shadow hover:bg-accent transition">
                            {{ __('Create New Job Request') }}
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block bg-primary text-background text-center py-4 rounded-lg shadow hover:bg-accent transition">
                            {{ __('Edit Profile') }}
                        </a>
                        <a href="{{ route('logout') }}" class="block bg-primary text-background text-center py-4 rounded-lg shadow hover:bg-accent transition"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Log Out') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>