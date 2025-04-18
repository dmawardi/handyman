<x-app-layout title="Handyman Bali | Dashboard"
    description="Handyman Bali is your go-to destination for all handyman services in Bali. We offer a wide range of services including plumbing, electrical work, carpentry, and more. Our team of skilled professionals is dedicated to providing top-notch service to ensure your home or business is in perfect condition."
    keywords="handyman, maintenance, maintenance requests, plumbing, electrician" canonical="url('/dashboard')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
