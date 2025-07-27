<x-app-layout title="RumahFix Bali | Maintenance Services in Bali" 
description="RumahFix Bali is your go-to destination for all handyman services in Bali. We offer a wide range of services including plumbing, electrical work, carpentry, and more. Our team of skilled professionals is dedicated to providing top-notch service to ensure your home or business is in perfect condition."
keywords="handyman, maintenance, maintenance requests, plumbing, electrician" canonical="url('/')">
    
    <x-whatsapp-button />
    <x-hero-section title="Reliable Maintenance Services in Bali" 
    description="Book skilled professionals for repairs, installations, and maintenance — fast, easy, and trustworthy." 
    buttonText="Explore Services" 
    :buttonAction="route('services')"
    backgroundImage="{{ asset('images/home/hero-team.jpg') }}"
    />
    <x-home.how-it-works />
    <x-home.service-areas />
    <x-home.testimonials />
    <x-hero-section title="Need Help Around the House?" description="Let us handle your repairs while you enjoy Bali life stress-free." buttonText="Request Service Now" />
</x-app-layout>