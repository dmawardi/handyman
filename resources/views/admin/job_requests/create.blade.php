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
    <x-forms.admin.job_request
        formAction="{{ route('admin.job-requests.store') }}"
        formMethod="POST"
        submitButtonText="Submit Request"
        apiKey="{{ env('GOOGLE_API_KEY') }}"
        :jobRequest="null"
        :users="$users"
        :workers="$workers"
    />
    <!-- Toggle Script for Customer Selection -->
    {{-- <script>
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
    </script> --}}
</x-app-layout>