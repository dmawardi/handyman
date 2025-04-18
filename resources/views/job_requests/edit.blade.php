<!-- filepath: /Users/d/Web Development/projects/handyman/resources/views/job_requests/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Job Request') }}
            </h2>
            <a href="{{ route('job-requests.show', $jobRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                {{ __('Cancel Edit') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('job-requests.update', $jobRequest->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="flex items-center mb-6">
                            <p class="text-gray-600"><strong>Job Number:</strong> {{ $jobRequest->job_number }}</p>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contact Name -->
                                <div>
                                    <x-input-label for="contact_name" :value="__('Contact Name')" />
                                    <x-text-input id="contact_name" name="contact_name" type="text" class="mt-1 block w-full" :value="old('contact_name', $jobRequest->contact_name)" required autofocus />
                                    <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                                </div>

                                <!-- Contact Email - Read-only -->
                                <div>
                                    <x-input-label for="contact_email" :value="__('Contact Email')" />
                                    <x-text-input id="contact_email" type="email" class="mt-1 block w-full bg-gray-100" value="{{ $jobRequest->contact_email }}" disabled readonly />
                                    <p class="mt-1 text-xs text-gray-500">Email address cannot be changed</p>
                                </div>

                                <!-- Contact Phone -->
                                <div>
                                    <x-input-label for="contact_phone" :value="__('Contact Phone')" />
                                    <x-text-input id="contact_phone" name="contact_phone" type="tel" class="mt-1 block w-full" :value="old('contact_phone', $jobRequest->contact_phone)" required />
                                    <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Street Address -->
                                <div class="md:col-span-2">
                                    <x-input-label for="street_address" :value="__('Street Address')" />
                                    <x-text-input id="street_address" name="street_address" type="text" class="mt-1 block w-full" :value="old('street_address', $jobRequest->street_address)" />
                                    <x-input-error :messages="$errors->get('street_address')" class="mt-2" />
                                </div>

                                <!-- City -->
                                <div>
                                    <x-input-label for="city" :value="__('City')" />
                                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $jobRequest->city)" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <!-- State -->
                                <div>
                                    <x-input-label for="state" :value="__('State')" />
                                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $jobRequest->state)" />
                                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                </div>

                                <!-- Zip Code -->
                                <div>
                                    <x-input-label for="zip_code" :value="__('Zip Code')" />
                                    <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code', $jobRequest->zip_code)" />
                                    <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
                                </div>

                                <!-- Location (for precise instructions) -->
                                <div>
                                    <x-input-label for="location" :value="__('Specific Location Details')" />
                                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $jobRequest->location)" placeholder="e.g., Back entrance, Garage, etc." />
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Job Details Section -->
                        <div class="border-b pb-4 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Job Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Job Type -->
                                <div>
                                    <x-input-label for="job_type" :value="__('Job Type')" />
                                    <select id="job_type" name="job_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="Plumbing" {{ (old('job_type', $jobRequest->job_type) == 'Plumbing') ? 'selected' : '' }}>Plumbing</option>
                                        <option value="Electrical" {{ (old('job_type', $jobRequest->job_type) == 'Electrical') ? 'selected' : '' }}>Electrical</option>
                                        <option value="Painting" {{ (old('job_type', $jobRequest->job_type) == 'Painting') ? 'selected' : '' }}>Painting</option>
                                        <option value="Appliance Repair" {{ (old('job_type', $jobRequest->job_type) == 'Appliance Repair') ? 'selected' : '' }}>Appliance Repair</option>
                                        <option value="Outdoor/Garden" {{ (old('job_type', $jobRequest->job_type) == 'Outdoor/Garden') ? 'selected' : '' }}>Outdoor/Garden</option>
                                        <option value="Installations" {{ (old('job_type', $jobRequest->job_type) == 'Installations') ? 'selected' : '' }}>Installations</option>
                                        <option value="Cleaning/Maintenance" {{ (old('job_type', $jobRequest->job_type) == 'Cleaning/Maintenance') ? 'selected' : '' }}>Cleaning/Maintenance</option>
                                        <option value="Other" {{ (old('job_type', $jobRequest->job_type) == 'Other') ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('job_type')" class="mt-2" />
                                </div>

                                <!-- Urgency Level -->
                                <div>
                                    <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                                    <select id="urgency_level" name="urgency_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="Low - Within 2 weeks" {{ (old('urgency_level', $jobRequest->urgency_level) == 'Low - Within 2 weeks') ? 'selected' : '' }}>Low - Within 2 weeks</option>
                                        <option value="Medium - Within 1 week" {{ (old('urgency_level', $jobRequest->urgency_level) == 'Medium - Within 1 week') ? 'selected' : '' }}>Medium - Within 1 week</option>
                                        <option value="High - Within 48 hours" {{ (old('urgency_level', $jobRequest->urgency_level) == 'High - Within 48 hours') ? 'selected' : '' }}>High - Within 48 hours</option>
                                        <option value="Emergency - Same day" {{ (old('urgency_level', $jobRequest->urgency_level) == 'Emergency - Same day') ? 'selected' : '' }}>Emergency - Same day</option>
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
                                        <x-text-input id="job_budget" name="job_budget" type="number" min="0" class="block w-full pl-7" :value="old('job_budget', $jobRequest->job_budget)" placeholder="0.00" />
                                    </div>
                                    <x-input-error :messages="$errors->get('job_budget')" class="mt-2" />
                                </div>

                                <!-- Job Description -->
                                <div class="md:col-span-2">
                                    <x-input-label for="job_description" :value="__('Job Description')" />
                                    <textarea id="job_description" name="job_description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('job_description', $jobRequest->job_description) }}</textarea>
                                    <x-input-error :messages="$errors->get('job_description')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('job-requests.show', $jobRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>