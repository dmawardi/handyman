@props([
    'jobRequest' => null,
])
<div class="border-b pb-4 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Contact Name -->
        <div>
            <x-input-label for="contact_name" :value="__('Contact Name')" />
            <x-text-input id="contact_name" name="contact_name" type="text" class="mt-1 block w-full" 
                :value="old('contact_name', $jobRequest ? $jobRequest->contact_name : (auth()->check() ? auth()->user()->name : ''))" 
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
        </div>

        <!-- Contact Email -->
        <div>
            <x-input-label for="contact_email" :value="__('Contact Email')" />
            @auth
                <x-text-input id="contact_email" name="contact_email" type="email" 
                    class="mt-1 block w-full bg-gray-100" 
                    value="{{ auth()->user()->email }}" readonly />
                <p class="mt-1 text-xs text-gray-500">Email address is linked to your account and cannot be changed</p>
            @else
                <x-text-input id="contact_email" name="contact_email" type="email" 
                    class="mt-1 block w-full" 
                    :value="old('contact_email', $jobRequest ? $jobRequest->contact_email : '')" 
                    required autocomplete="email" />
            @endauth
            <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
        </div>

        <!-- Contact Phone -->
        <div>
            <x-input-label for="contact_phone" :value="__('Contact Phone')" />
            <x-text-input id="contact_phone" name="contact_phone" type="tel" 
                class="mt-1 block w-full" 
                :value="old('contact_phone', $jobRequest ? $jobRequest->contact_phone : (auth()->check() ? auth()->user()->phone : ''))" 
                required autocomplete="tel" />
            <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
        </div>
    </div>
</div>