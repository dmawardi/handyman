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
        <x-forms.job_request
            :jobRequest="$jobRequest"
            formAction="{{ route('job-requests.update', $jobRequest->id) }}"
            formMethod="PATCH"
            submitButtonText="Update Request"
            apiKey="{{ config('api.google-places') }}"
        />
    </x-slot>
</x-app-layout>