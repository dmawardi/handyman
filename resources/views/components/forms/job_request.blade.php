@props([
    'jobRequest' => null,
    'formAction' => route('job-requests.store'),
    'formMethod' => 'POST',
    'submitButtonText' => 'Submit Request',
    'apiKey' => config('api.google-places'),
])
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

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

                    <!-- Contact Information Section -->
                    <x-forms.partials._contact :jobRequest="$jobRequest" />

                    <!-- Address Information Section -->
                    <x-forms.partials._address :jobRequest="$jobRequest" :apiKey="$apiKey" />

                    <!-- Job Details Section -->
                    <x-forms.partials._job_details :jobRequest="$jobRequest" />

                    <!-- Image Attachments Section -->
                    <x-forms.partials._image_attachment :jobRequest="$jobRequest" />

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        @if($jobRequest)
                            <a href="{{ route('job-requests.show', $jobRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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



