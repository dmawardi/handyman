<x-app-layout title="RumahFix Bali | Submit a Job Request" 
description="Have a job that needs doing? Submit a request for maintenance services in Bali. Our team is ready to assist you with all your maintenance needs, from plumbing to electrical work and more."
keywords="handyman services, plumbing, electrical work, carpentry, maintenance, Bali" canonical="url('/job-requests/create')">
    <x-slot name="header">
        <h1>
            {{ __('Welcome to the job request page!') }}
        </h1>
        <p>
            {{ __('Allows users to submit a maintenance/job request. Includes fields for details, image upload, email, etc.') }}
        </p>
    </x-slot>
    <x-forms.job_request />
</x-app-layout>