<!-- filepath: /Users/d/Web Development/projects/handyman/resources/views/admin/job_requests/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Job Request') }} - {{ $jobRequest->job_number }}
            </h2>
            <a href="{{ route('admin.job-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                {{ __('Back to Job Requests') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.job-requests.update', $jobRequest->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Customer Information Section -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- User ID (Hidden) -->
                                <input type="hidden" name="user_id" value="{{ $jobRequest->user_id }}">
                                
                                <!-- Customer Details -->
                                <div>
                                    <x-input-label for="contact_name" :value="__('Contact Name')" />
                                    <x-text-input id="contact_name" name="contact_name" type="text" class="mt-1 block w-full" :value="old('contact_name', $jobRequest->contact_name)" required />
                                    <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="contact_email" :value="__('Contact Email')" />
                                    <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="old('contact_email', $jobRequest->contact_email)" required />
                                    <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="contact_phone" :value="__('Contact Phone')" />
                                    <x-text-input id="contact_phone" name="contact_phone" type="tel" class="mt-1 block w-full" :value="old('contact_phone', $jobRequest->contact_phone)" required />
                                    <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Worker Assignment Section -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Worker Assignment</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <x-input-label for="worker_id" :value="__('Assign Worker')" />
                                    <select id="worker_id" name="worker_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Not assigned</option>
                                        @foreach($workers as $worker)
                                            <option value="{{ $worker->id }}" {{ old('worker_id', $jobRequest->worker_id) == $worker->id ? 'selected' : '' }}>
                                                {{ $worker->name }} ({{ $worker->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('worker_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <x-forms.partials._address :jobRequest="$jobRequest" :apiKey="$apiKey" />

                        <!-- Job Details Section -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Job Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Job Type -->
                                <div>
                                    <x-input-label for="job_type" :value="__('Job Type')" />
                                    <select id="job_type" name="job_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Select job type</option>
                                        <option value="Plumbing" {{ old('job_type', $jobRequest->job_type) == 'Plumbing' ? 'selected' : '' }}>Plumbing</option>
                                        <option value="Electrical" {{ old('job_type', $jobRequest->job_type) == 'Electrical' ? 'selected' : '' }}>Electrical</option>
                                        <option value="Painting" {{ old('job_type', $jobRequest->job_type) == 'Painting' ? 'selected' : '' }}>Painting</option>
                                        <option value="Appliance Repair" {{ old('job_type', $jobRequest->job_type) == 'Appliance Repair' ? 'selected' : '' }}>Appliance Repair</option>
                                        <option value="Outdoor/Garden" {{ old('job_type', $jobRequest->job_type) == 'Outdoor/Garden' ? 'selected' : '' }}>Outdoor/Garden</option>
                                        <option value="Installations" {{ old('job_type', $jobRequest->job_type) == 'Installations' ? 'selected' : '' }}>Installations</option>
                                        <option value="Cleaning/Maintenance" {{ old('job_type', $jobRequest->job_type) == 'Cleaning/Maintenance' ? 'selected' : '' }}>Cleaning/Maintenance</option>
                                        <option value="Other" {{ old('job_type', $jobRequest->job_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('job_type')" class="mt-2" />
                                </div>

                                <!-- Urgency Level -->
                                <div>
                                    <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                                    <select id="urgency_level" name="urgency_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Select urgency</option>
                                        <option value="Low - Within 2 weeks" {{ old('urgency_level', $jobRequest->urgency_level) == 'Low - Within 2 weeks' ? 'selected' : '' }}>Low - Within 2 weeks</option>
                                        <option value="Medium - Within 1 week" {{ old('urgency_level', $jobRequest->urgency_level) == 'Medium - Within 1 week' ? 'selected' : '' }}>Medium - Within 1 week</option>
                                        <option value="High - Within 48 hours" {{ old('urgency_level', $jobRequest->urgency_level) == 'High - Within 48 hours' ? 'selected' : '' }}>High - Within 48 hours</option>
                                        <option value="Emergency - Same day" {{ old('urgency_level', $jobRequest->urgency_level) == 'Emergency - Same day' ? 'selected' : '' }}>Emergency - Same day</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                                </div>

                                <!-- Budget -->
                                <div>
                                    <x-input-label for="job_budget" :value="__('Budget (Optional)')" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <x-text-input id="job_budget" name="job_budget" type="number" min="0" step="0.01" class="block w-full pl-7" :value="old('job_budget', $jobRequest->job_budget)" placeholder="0.00" />
                                    </div>
                                    <x-input-error :messages="$errors->get('job_budget')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="Pending" {{ old('status', $jobRequest->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ old('status', $jobRequest->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Completed" {{ old('status', $jobRequest->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ old('status', $jobRequest->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <!-- Job Description -->
                                <div class="md:col-span-2">
                                    <x-input-label for="job_description" :value="__('Job Description')" />
                                    <textarea id="job_description" name="job_description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('job_description', $jobRequest->job_description) }}</textarea>
                                    <x-input-error :messages="$errors->get('job_description')" class="mt-2" />
                                </div>

                                <!-- Internal Notes -->
                                <div class="md:col-span-2">
                                    <x-input-label for="notes" :value="__('Internal Notes (Admin Only)')" />
                                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $jobRequest->notes) }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.job-requests.show', $jobRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button>
                                {{ __('Update Job Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>