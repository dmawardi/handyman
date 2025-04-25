<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Job Request Details') }} - {{ $jobRequest->job_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.job-requests.edit', $jobRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                    {{ __('Edit Request') }}
                </a>
                <a href="{{ route('admin.job-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                    {{ __('Back to All Requests') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- Job Request Summary -->
                    <div class="border-b pb-6 mb-6">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $jobRequest->job_number }}</h3>
                                <p class="mt-1 text-sm text-gray-500">Submitted on {{ $jobRequest->created_at->format('F j, Y, g:i a') }}</p>
                                @if($jobRequest->completion_date)
                                    <p class="mt-1 text-sm text-green-600">Completed on {{ $jobRequest->completion_date->format('F j, Y, g:i a') }}</p>
                                @endif
                            </div>
                            <div class="mt-4 sm:mt-0 flex flex-col items-end">
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
                                
                                <!-- Update Status Form -->
                                <form method="POST" action="{{ route('admin.job-requests.update-status', $jobRequest->id) }}" class="mt-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center space-x-2">
                                        <select name="status" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="Pending" {{ $jobRequest->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $jobRequest->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Completed" {{ $jobRequest->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $jobRequest->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        <!-- Customer Information -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h4>
                            <dl class="grid grid-cols-1 gap-y-3 text-sm">
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Customer:</dt>
                                    <dd class="col-span-2">
                                        @if($jobRequest->requestor)
                                            <a href="#" class="text-blue-600 hover:underline">{{ $jobRequest->requestor->name }}</a>
                                            <span class="text-xs text-gray-500">(ID: {{ $jobRequest->requestor->id }})</span>
                                        @else
                                            Not assigned to a user
                                        @endif
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Name:</dt>
                                    <dd class="col-span-2">{{ $jobRequest->contact_name }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Email:</dt>
                                    <dd class="col-span-2">
                                        <a href="mailto:{{ $jobRequest->contact_email }}" class="text-blue-600 hover:underline">{{ $jobRequest->contact_email }}</a>
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Phone:</dt>
                                    <dd class="col-span-2">
                                        <a href="tel:{{ $jobRequest->contact_phone }}" class="text-blue-600 hover:underline">{{ $jobRequest->contact_phone }}</a>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Worker Assignment -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Worker Assignment</h4>
                            <dl class="grid grid-cols-1 gap-y-3 text-sm mb-4">
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Assigned Worker:</dt>
                                    <dd class="col-span-2">
                                        @if($jobRequest->worker)
                                            <span class="text-green-600">{{ $jobRequest->worker->name }}</span>
                                            <span class="text-xs text-gray-500">(ID: {{ $jobRequest->worker->id }})</span>
                                        @else
                                            <span class="text-yellow-600">Not assigned</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                            
                            <!-- Assign Worker Form -->
                            <form method="POST" action="{{ route('admin.job-requests.assign-worker', $jobRequest->id) }}" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center space-x-2">
                                    <select name="worker_id" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">-- Select Worker --</option>
                                        @foreach($workers as $worker)
                                            <option value="{{ $worker->id }}" {{ $jobRequest->worker_id == $worker->id ? 'selected' : '' }}>
                                                {{ $worker->name }} ({{ $worker->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                        Assign
                                    </button>
                                </div>
                            </form>
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
                                @if($jobRequest->latitude && $jobRequest->longitude)
                                <div class="grid grid-cols-3">
                                    <dt class="font-medium text-gray-500">Coordinates:</dt>
                                    <dd class="col-span-2 text-xs text-gray-500">
                                        {{ $jobRequest->latitude }}, {{ $jobRequest->longitude }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Job Details -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Job Details</h4>
                            <dl class="grid grid-cols-1 gap-y-3 text-sm">
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
                            </dl>
                        </div>

                        <!-- Job Description -->
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Job Description</h4>
                            <div class="mt-1 whitespace-pre-line bg-gray-50 p-4 rounded-md border border-gray-200">
                                {{ $jobRequest->job_description }}
                            </div>
                        </div>
                        
                        <!-- Internal Notes -->
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Internal Notes</h4>
                            
                            <div class="mb-4">
                                @if($jobRequest->notes)
                                    <div class="mt-1 whitespace-pre-line bg-yellow-50 p-4 rounded-md border border-yellow-200">
                                        {{ $jobRequest->notes }}
                                    </div>
                                @else
                                    <p class="text-gray-500 italic">No internal notes have been added yet.</p>
                                @endif
                            </div>
                        </div>

                        <x-admin.partials._job_request_attachments :attachments="$jobRequest->images" />

                        {{-- Print the note updates --}}
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Note Updates</h4>
                            @if($jobRequest->noteUpdates->isNotEmpty())
                                <div class="overflow-y-auto max-h-64 border border-gray-200 rounded-md p-2" id="note-updates-container">
                                    <ul class="list-none pl-5">
                                        @foreach($jobRequest->noteUpdates as $noteUpdate)
                                            <li class="mb-2">
                                                <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                                    <p class="text-sm text-gray-700">{{ $noteUpdate->update_type }}</p>
                                                    <p class="text-sm text-gray-700">{{ $noteUpdate->update_description }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">Updated on {{ $noteUpdate->created_at->format('F j, Y, g:i a') }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const container = document.getElementById('note-updates-container');
                                        container.scrollTop = container.scrollHeight;
                                    });
                                </script>
                            @else
                                <p class="text-gray-500 italic">No note updates have been added yet.</p>
                            @endif
                            <!-- Add Update Notes Form -->
                            <form method="POST" action="{{ route('admin.job-requests.add-update', $jobRequest->id) }}" class="mt-4">
                                @csrf
                                <div class="mb-4">
                                    <textarea name="noteUpdate" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Add internal notes here..."></textarea>
                                    <x-input-error :messages="$errors->get('noteUpdate')" class="mt-2" />
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150">
                                        Save Note Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    <div class="mt-10 pt-6 border-t flex justify-end space-x-4">
                        <a href="{{ route('admin.job-requests.edit', $jobRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                            Edit Job Request
                        </a>
                        
                        <form method="POST" action="{{ route('admin.job-requests.destroy', $jobRequest->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150"
                                onclick="return confirm('Are you sure you want to delete this job request? This action cannot be undone.')">
                                Delete Job Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>