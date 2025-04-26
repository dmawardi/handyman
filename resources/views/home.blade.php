<x-app-layout title="Handyman Bali | Handyman Services in Bali" 
description="Handyman Bali is your go-to destination for all handyman services in Bali. We offer a wide range of services including plumbing, electrical work, carpentry, and more. Our team of skilled professionals is dedicated to providing top-notch service to ensure your home or business is in perfect condition."
keywords="handyman, maintenance, maintenance requests, plumbing, electrician" canonical="url('/')">
    
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>
    <x-whatsapp-button :phoneNumber="'1234567890'" />
    <h1>
        {{ __('Welcome to the home page!') }}
    </h1>
    <p>
        {{ __('This is the home page of the application.') }}
    </p>
    <x-hero-section />
</x-app-layout>