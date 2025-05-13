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
    <x-forms.admin.job_request
        formAction="{{ route('admin.job-requests.update', $jobRequest->id) }}"
        formMethod="PATCH"
        submitButtonText="Update Request"
        apiKey="{{ env('GOOGLE_API_KEY') }}"
        :jobRequest="$jobRequest"
        :users="$users"
        :workers="$workers"
    />
</x-app-layout>