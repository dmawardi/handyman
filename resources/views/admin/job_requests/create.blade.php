<!-- filepath: /Users/d/Web Development/projects/handyman/resources/views/admin/job_requests/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Job Request') }}
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
                    <form method="POST" action="{{ route('admin.job-requests.store') }}" class="space-y-6">
                        @csrf

                        <!-- Customer Selection Section -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            
                            <div class="mb-4">
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="customer_type" value="existing" class="form-radio" checked>
                                        <span class="ml-2">Existing Customer</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="customer_type" value="new" class="form-radio">
                                        <span class="ml-2">New Customer</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Existing Customer Selection -->
                            <div id="existing-customer-section" class="mb-4">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <x-input-label for="user_id" :value="__('Select Customer')" />
                                        <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">Select a customer</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- New Customer Details -->
                            <div id="new-customer-section" class="hidden mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Contact Name -->
                                    <div>
                                        <x-input-label for="contact_name" :value="__('Contact Name')" />
                                        <x-text-input id="contact_name" name="contact_name" type="text" class="mt-1 block w-full" :value="old('contact_name')" />
                                        <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                                    </div>

                                    <!-- Contact Email -->
                                    <div>
                                        <x-input-label for="contact_email" :value="__('Contact Email')" />
                                        <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="old('contact_email')" />
                                        <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                                        <p class="mt-1 text-xs text-gray-500">This will be used to create a new user account</p>
                                    </div>

                                    <!-- Contact Phone -->
                                    <div>
                                        <x-input-label for="contact_phone" :value="__('Contact Phone')" />
                                        <x-text-input id="contact_phone" name="contact_phone" type="tel" class="mt-1 block w-full" :value="old('contact_phone')" />
                                        <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Worker Assignment Section -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Worker Assignment (Optional)</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <x-input-label for="worker_id" :value="__('Assign Worker')" />
                                    <select id="worker_id" name="worker_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Not assigned</option>
                                        @foreach($workers as $worker)
                                            <option value="{{ $worker->id }}" {{ old('worker_id') == $worker->id ? 'selected' : '' }}>
                                                {{ $worker->name }} ({{ $worker->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('worker_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Street Address -->
                                <div class="md:col-span-2">
                                    <x-input-label for="street_address" :value="__('Street Address')" />
                                    <x-text-input id="street_address" name="street_address" type="text" class="mt-1 block w-full" :value="old('street_address')" />
                                    <x-input-error :messages="$errors->get('street_address')" class="mt-2" />
                                </div>

                                <!-- City -->
                                <div>
                                    <x-input-label for="city" :value="__('City')" />
                                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city')" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>

                                <!-- State -->
                                <div>
                                    <x-input-label for="state" :value="__('State')" />
                                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state')" />
                                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                </div>

                                <!-- Zip Code -->
                                <div>
                                    <x-input-label for="zip_code" :value="__('Zip Code')" />
                                    <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code')" />
                                    <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
                                </div>

                                <!-- Location (for precise instructions) -->
                                <div>
                                    <x-input-label for="location" :value="__('Specific Location Details')" />
                                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location')" placeholder="e.g., Back entrance, Garage, etc." />
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Job Details Section -->
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Job Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Job Type -->
                                <div>
                                    <x-input-label for="job_type" :value="__('Job Type')" />
                                    <select id="job_type" name="job_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Select job type</option>
                                        <option value="Plumbing" {{ old('job_type') == 'Plumbing' ? 'selected' : '' }}>Plumbing</option>
                                        <option value="Electrical" {{ old('job_type') == 'Electrical' ? 'selected' : '' }}>Electrical</option>
                                        <option value="Painting" {{ old('job_type') == 'Painting' ? 'selected' : '' }}>Painting</option>
                                        <option value="Appliance Repair" {{ old('job_type') == 'Appliance Repair' ? 'selected' : '' }}>Appliance Repair</option>
                                        <option value="Outdoor/Garden" {{ old('job_type') == 'Outdoor/Garden' ? 'selected' : '' }}>Outdoor/Garden</option>
                                        <option value="Installations" {{ old('job_type') == 'Installations' ? 'selected' : '' }}>Installations</option>
                                        <option value="Cleaning/Maintenance" {{ old('job_type') == 'Cleaning/Maintenance' ? 'selected' : '' }}>Cleaning/Maintenance</option>
                                        <option value="Other" {{ old('job_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('job_type')" class="mt-2" />
                                </div>

                                <!-- Urgency Level -->
                                <div>
                                    <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                                    <select id="urgency_level" name="urgency_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Select urgency</option>
                                        <option value="Low - Within 2 weeks" {{ old('urgency_level') == 'Low - Within 2 weeks' ? 'selected' : '' }}>Low - Within 2 weeks</option>
                                        <option value="Medium - Within 1 week" {{ old('urgency_level') == 'Medium - Within 1 week' ? 'selected' : '' }}>Medium - Within 1 week</option>
                                        <option value="High - Within 48 hours" {{ old('urgency_level') == 'High - Within 48 hours' ? 'selected' : '' }}>High - Within 48 hours</option>
                                        <option value="Emergency - Same day" {{ old('urgency_level') == 'Emergency - Same day' ? 'selected' : '' }}>Emergency - Same day</option>
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
                                        <x-text-input id="job_budget" name="job_budget" type="number" min="0" step="0.01" class="block w-full pl-7" :value="old('job_budget')" placeholder="0.00" />
                                    </div>
                                    <x-input-error :messages="$errors->get('job_budget')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <!-- Job Description -->
                                <div class="md:col-span-2">
                                    <x-input-label for="job_description" :value="__('Job Description')" />
                                    <textarea id="job_description" name="job_description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('job_description') }}</textarea>
                                    <x-input-error :messages="$errors->get('job_description')" class="mt-2" />
                                </div>

                                <!-- Internal Notes -->
                                <div class="md:col-span-2">
                                    <x-input-label for="notes" :value="__('Internal Notes (Admin Only)')" />
                                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Create Job Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Script for Customer Selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerTypeRadios = document.querySelectorAll('input[name="customer_type"]');
            const existingCustomerSection = document.getElementById('existing-customer-section');
            const newCustomerSection = document.getElementById('new-customer-section');
            const userIdSelect = document.getElementById('user_id');
            const contactNameInput = document.getElementById('contact_name');
            const contactEmailInput = document.getElementById('contact_email');
            const contactPhoneInput = document.getElementById('contact_phone');

            function toggleCustomerSections() {
                if (document.querySelector('input[name="customer_type"]:checked').value === 'existing') {
                    existingCustomerSection.classList.remove('hidden');
                    newCustomerSection.classList.add('hidden');
                    userIdSelect.setAttribute('required', 'required');
                    contactNameInput.removeAttribute('required');
                    contactEmailInput.removeAttribute('required');
                    contactPhoneInput.removeAttribute('required');
                } else {
                    existingCustomerSection.classList.add('hidden');
                    newCustomerSection.classList.remove('hidden');
                    userIdSelect.removeAttribute('required');
                    contactNameInput.setAttribute('required', 'required');
                    contactEmailInput.setAttribute('required', 'required');
                    contactPhoneInput.setAttribute('required', 'required');
                }
            }

            // Initial toggle based on default selection
            toggleCustomerSections();

            // Toggle on radio button change
            customerTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleCustomerSections);
            });
        });
    </script>
</x-app-layout>