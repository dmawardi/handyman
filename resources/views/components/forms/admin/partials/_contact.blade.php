@props([
    'jobRequest' => null,
    'users' => [],
])
@if(!$jobRequest)
    <!-- Customer Selection for Create -->
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
@else
    <!-- Customer Info for Edit -->
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
@endif
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