<!-- filepath: /Users/d/Web Development/projects/handyman/resources/views/job_requests/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Job Request Details') }}
            </h2>
            <a href="{{ route('job-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to All Requests') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- Job Request Summary -->
                    <div class="border-b pb-6 mb-6">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $jobRequest->job_number }}</h3>
                                <p class="mt-1 text-sm text-gray-500">Submitted on {{ $jobRequest->created_at->format('F j, Y, g:i a') }}</p>
                            </div>
                            <div class="mt-4 mb-2 sm:mt-0">
                                @php
                                    $statusClass = match($jobRequest->status) {
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'In Progress' => 'bg-blue-100 text-blue-800',
                                        'Completed' => 'bg-green-100 text-green-800',
                                        'Cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusClass }}">
                                    {{ $jobRequest->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        <!-- Contact Information -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h4>
                            <dl class="grid grid-cols-1 gap-y-3 text-sm">
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Name:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->contact_name }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Email:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->contact_email }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Phone:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->contact_phone }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Address Information -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Service Location</h4>
                            <dl class="grid grid-cols-1 gap-y-3 text-sm">
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Address:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->street_address ?: 'Not provided' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Suburb:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->suburb ?: 'Not provided' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Area:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->area ?: 'Not provided' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">City:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->city ?: 'Not provided' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">State:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->state ?: 'Not provided' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">ZIP Code:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->zip_code ?: 'Not provided' }}</dd>
                                </div>
                                @if($jobRequest->location)
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Location Notes:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->location }}</dd>
                                </div>
                                @endif
                                @if($jobRequest->latitude && $jobRequest->longitude)
                                <div class="grid grid-cols-3 mt-2">
                                    <dt class="font-medium text-gray-500">Map View:</dt>
                                    <dd class="col-span-2">
                                        <a href="https://maps.google.com/?q={{ $jobRequest->latitude }},{{ $jobRequest->longitude }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            View on Google Maps
                                        </a>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Job Details -->
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Job Details</h4>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Service Type:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->job_type }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Urgency:</dt>
                                    <dd class="col-span-2">
                                        @php
                                            $urgencyClass = match($jobRequest->urgency_level) {
                                                'Low - Within 2 weeks' => 'text-blue-600',
                                                'Medium - Within 1 week' => 'text-yellow-600',
                                                'High - Within 48 hours' => 'text-orange-600',
                                                'Emergency - Same day' => 'text-red-600',
                                                default => 'text-gray-600'
                                            };
                                        @endphp
                                        <span class="{{ $urgencyClass }} font-medium">{{ $jobRequest->urgency_level }}</span>
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Budget:</dt>
                                    <dd class="col-span-2">
                                        @if($jobRequest->job_budget)
                                            ${{ number_format($jobRequest->job_budget, 2) }}
                                        @else
                                            Not specified
                                        @endif
                                    </dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="font-medium text-gray-500 mb-2">Description:</dt>
                                    <dd class="mt-1 whitespace-pre-line bg-gray-50 p-3 rounded">{{ $jobRequest->job_description }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Image Attachments -->
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Image Attachments</h4>
                            @if($jobRequest->images && count($jobRequest->images) > 0)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($jobRequest->images as $image)
                                        <div class="relative group">
                                            <img src="{{ $image->getSrc() }}" alt="Attachment" class="w-full h-32 object-cover rounded-md shadow">
                                            @if(auth()->user()->user_type === 'admin')
                                                <button type="button" class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded shadow group-hover:opacity-100 opacity-0 transition-opacity duration-200" onclick="removeImage('{{ $image->id }}')">
                                                    Remove
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No images attached.</p>
                            @endif
                    </div>

                    <!-- Actions -->
                    @if($jobRequest->status === 'Pending')
                    <div class="mt-10 pt-6 border-t flex justify-end">
                        <form method="POST" action="{{ route('job-requests.destroy', $jobRequest->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150"
                                onclick="return confirm('Are you sure you want to cancel this job request?')">
                                Cancel Request
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>