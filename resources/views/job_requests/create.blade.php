<x-app-layout title="RumahFix Bali | Submit a Job Request" 
description="Have a job that needs doing? Submit a request for maintenance services in Bali. Our team is ready to assist you with all your maintenance needs, from plumbing to electrical work and more."
keywords="handyman services, plumbing, electrical work, carpentry, maintenance, Bali" canonical="url('/job-requests/create')">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Submit Your Job Request') }}
        </h1>
        <p>
            {{ __('Need help with maintenance or repairs? Fill out the form below to submit your job request. Our team will review your request and get back to you promptly.') }}
        </p>
    </x-slot>
    <x-forms.job_request />
</x-app-layout>