@props([
    'jobRequest' => null,
    'formAction' => route('admin.job-requests.store'),
    'formMethod' => 'POST',
    'submitButtonText' => 'Create Job Request',
    'users' => [],
    'workers' => [],
    'apiKey' => config('api.google-places'),
])

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="{{ $formMethod === 'GET' ? 'GET' : 'POST' }}" action="{{ $formAction }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @if($formMethod !== 'POST' && $formMethod !== 'GET')
                        @method($formMethod)
                    @endif

                    @if($jobRequest)
                        <div class="flex items-center mb-6">
                            <p class="text-gray-600"><strong>Job Number:</strong> {{ $jobRequest->job_number }}</p>
                        </div>
                    @endif

                    <!-- Customer Selection/Information Section -->
                    <x-forms.admin.partials._contact :jobRequest="$jobRequest" :users="$users" />

                    <!-- Worker Assignment Section -->
                    <div class="border-b pb-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Worker Assignment {{ !$jobRequest ? '(Optional)' : '' }}</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="worker_id" :value="__('Assign Worker')" />
                                <select id="worker_id" name="worker_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Not assigned</option>
                                    @foreach($workers as $worker)
                                        <option value="{{ $worker->id }}" {{ old('worker_id', $jobRequest?->worker_id) == $worker->id ? 'selected' : '' }}>
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
                    <x-forms.admin.partials._job_details :jobRequest="$jobRequest" />

                    <!-- Image Attachments Section -->
                    <x-forms.partials._image_attachment :jobRequest="$jobRequest" />

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        @if($jobRequest)
                            <a href="{{ route('admin.job-requests.show', $jobRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        @endif
                        <x-primary-button>
                            {{ $submitButtonText }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(!$jobRequest)
<!-- Toggle Script for Customer Selection on Create page only -->
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
@endif