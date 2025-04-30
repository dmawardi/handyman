@props([
    'jobRequest' => null,
])

<div class="border-b pb-6 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Details</h3>
    <form method="POST" action="{{ route('admin.job-requests.update', $jobRequest->id) }}">
        @csrf
        @method('PATCH')

        <!-- Hidden Field for Job Request ID -->
        <input type="hidden" name="job_request_id" value="{{ $jobRequest->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Payment Method -->
            <div>
                <x-input-label for="payment_method" :value="__('Payment Method')" />
                <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select payment method</option>
                    <option value="Credit Card" {{ old('payment_method', $jobRequest?->payment_method) == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="PayPal" {{ old('payment_method', $jobRequest?->payment_method) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                    <option value="Bank Transfer" {{ old('payment_method', $jobRequest?->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cash" {{ old('payment_method', $jobRequest?->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                </select>
                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
            </div>

            <!-- Payment Status -->
            <div>
                <x-input-label for="payment_status" :value="__('Payment Status')" />
                <select id="payment_status" name="payment_status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="Pending" {{ old('payment_status', $jobRequest?->payment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ old('payment_status', $jobRequest?->payment_status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Failed" {{ old('payment_status', $jobRequest?->payment_status) == 'Failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
            </div>

            <!-- Transaction ID -->
            <div>
                <x-input-label for="transaction_id" :value="__('Transaction ID')" />
                <x-text-input id="transaction_id" name="transaction_id" type="text" class="mt-1 block w-full" :value="old('transaction_id', $jobRequest?->transaction_id)" placeholder="Enter transaction ID" />
                <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
            </div>

            <!-- Invoice Number -->
            <div>
                <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                <x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full" :value="old('invoice_number', $jobRequest?->invoice_number)" placeholder="Enter invoice number" />
                <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
            </div>

            <!-- Payment Amount -->
            <div>
                <x-input-label for="payment_amount" :value="__('Payment Amount')" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <x-text-input id="payment_amount" name="payment_amount" type="number" min="0" step="0.01" class="block w-full pl-7" :value="old('payment_amount', $jobRequest?->payment_amount)" placeholder="0.00" />
                </div>
                <x-input-error :messages="$errors->get('payment_amount')" class="mt-2" />
            </div>

            <!-- Payment Date -->
            <div>
                <x-input-label for="payment_date" :value="__('Payment Date')" />
                <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date', $jobRequest?->payment_date)" />
                <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
            </div>

            <!-- Payment Receipt -->
            <div>
                <x-input-label for="payment_receipt" :value="__('Payment Receipt')" />
                <x-text-input id="payment_receipt" name="payment_receipt" type="text" class="mt-1 block w-full" :value="old('payment_receipt', $jobRequest?->payment_receipt)" placeholder="Enter receipt URL" />
                <x-input-error :messages="$errors->get('payment_receipt')" class="mt-2" />
            </div>

            <!-- Full Amount -->
            <div>
                <x-input-label for="full_amount" :value="__('Full Amount')" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <x-text-input id="full_amount" name="full_amount" type="number" min="0" step="0.01" class="block w-full pl-7" :value="old('full_amount', $jobRequest?->full_amount)" placeholder="0.00" />
                </div>
                <x-input-error :messages="$errors->get('full_amount')" class="mt-2" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>
                {{ __('Update Financial Details') }}
            </x-primary-button>
        </div>
    </form>
</div>