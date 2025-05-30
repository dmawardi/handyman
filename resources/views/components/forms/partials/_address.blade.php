@props([
    'jobRequest' => null,
    'apiKey' => "",
])

<div class="border-b pb-4 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
    
    <!-- Manual Entry Toggle -->
    <div class="mb-4 flex items-center">
        <input type="checkbox" id="enable-manual-address" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2">
        <label for="enable-manual-address" class="text-sm text-gray-700">Enable manual address entry</label>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Google Maps API -->
        <div class="md:col-span-2">
            <x-input-label for="api_search" :value="__('Search Address')" />
            <x-text-input id="autocomplete-address" type="text" name="api_search" placeholder="Enter your address" class="mt-1 block w-full" :value="old('api_search', $jobRequest?->api_search)" />
            <x-input-error :messages="$errors->get('api_search')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500">Use the search to automatically fill address fields or enable manual entry above</p>
            <input type="hidden" name="latitude" id="lat" value="{{ old('latitude', $jobRequest?->latitude) }}">
            <input type="hidden" name="longitude" id="lng" value="{{ old('longitude', $jobRequest?->longitude) }}">
            <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&libraries=places"></script>
        </div>
        
        <!-- Street Address -->
        <div class="md:col-span-2">
            <x-input-label for="street_address" :value="__('Street Address')" />
            <x-text-input id="street_address" name="street_address" type="text" class="mt-1 block w-full address-field" 
                :value="old('street_address', $jobRequest?->street_address)" autocomplete="street-address" readonly />
            <x-input-error :messages="$errors->get('street_address')" class="mt-2" />
        </div>
        
        <!-- City -->
        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full address-field" 
                :value="old('city', $jobRequest?->city)" autocomplete="address-level2" readonly />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>
        
        <!-- State -->
        <div>
            <x-input-label for="state" :value="__('State')" />
            <x-text-input id="state" name="state" type="text" class="mt-1 block w-full address-field" 
                :value="old('state', $jobRequest?->state)" autocomplete="address-level1" readonly />
            <x-input-error :messages="$errors->get('state')" class="mt-2" />
        </div>
        
        <!-- Suburb -->
        <div>
            <x-input-label for="suburb" :value="__('Suburb')" />
            <x-text-input id="suburb" name="suburb" type="text" class="mt-1 block w-full address-field" 
                :value="old('suburb', $jobRequest?->suburb)" autocomplete="address-level2" readonly />
            <x-input-error :messages="$errors->get('suburb')" class="mt-2" />
        </div>
        
        <!-- Area -->
        <div>
            <x-input-label for="area" :value="__('Area')" />
            <x-text-input id="area" name="area" type="text" class="mt-1 block w-full address-field" 
                :value="old('area', $jobRequest?->area)" autocomplete="address-level2" readonly />
            <x-input-error :messages="$errors->get('area')" class="mt-2" />
        </div>
        
        <!-- Zip Code -->
        <div>
            <x-input-label for="zip_code" :value="__('Zip Code')" />
            <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full address-field" 
                :value="old('zip_code', $jobRequest?->zip_code)" autocomplete="postal-code" readonly />
            <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
        </div>
        
        <!-- Location (for precise instructions) -->
        <div>
            <x-input-label for="location" :value="__('Specific Location Details')" />
            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" 
                :value="old('location', $jobRequest?->location)" placeholder="e.g., Back entrance, Garage, etc." />
            <x-input-error :messages="$errors->get('location')" class="mt-2" />
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let autocomplete;
        const autocompleteInput = document.getElementById('autocomplete-address');
        const manualToggle = document.getElementById('enable-manual-address');
        const addressFields = document.querySelectorAll('.address-field');
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        
        // Initialize Google Maps Autocomplete
        function initAutocomplete() {
            autocomplete = new google.maps.places.Autocomplete(
                autocompleteInput,
                {
                    types: ['geocode'],
                    componentRestrictions: { country: 'id' } // Limit to Indonesia
                }
            );

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                // Get the address components
                const components = place.address_components;
                let state = '';
                let city = '';
                let suburb = '';
                let area = '';
                let streetNumber = '';
                let streetName = '';
                let postcode = '';

                // Loop through the components to find details and split them
                components.forEach(component => {
                    const types = component.types;

                    if (types.includes('street_number')) {
                        streetNumber = component.long_name;
                    }
                    if (types.includes('route')) {
                        streetName = component.long_name;
                    }
                    if (types.includes('administrative_area_level_4')) {
                        area = component.long_name;
                    }
                    if (types.includes('administrative_area_level_3')) {
                        suburb = component.long_name;
                    }
                    if (types.includes('administrative_area_level_2')) {
                        city = component.long_name;
                    }
                    if (types.includes('administrative_area_level_1')) {
                        state = component.long_name;
                    }
                    if (types.includes('postal_code')) {
                        postcode = component.long_name;
                    }
                });

                const location = place.geometry.location;
                // Set the latitude and longitude values to hidden fields
                latInput.value = location.lat();
                lngInput.value = location.lng();
                
                // Set the address details to the respective fields
                document.getElementById('street_address').value = streetName + ' ' + streetNumber;
                document.getElementById('city').value = city;
                document.getElementById('state').value = state;
                document.getElementById('suburb').value = suburb;
                document.getElementById('area').value = area;
                document.getElementById('zip_code').value = postcode;
            });
        }
        
        // Toggle between automatic and manual address entry
        function toggleAddressInput() {
            const isManual = manualToggle.checked;
            
            // Toggle searchbox
            autocompleteInput.disabled = isManual;
            if (isManual) {
                autocompleteInput.classList.add('bg-gray-100');
            } else {
                autocompleteInput.classList.remove('bg-gray-100');
            }
            
            // Toggle address fields
            addressFields.forEach(field => {
                field.readOnly = !isManual;
                if (isManual) {
                    field.classList.remove('bg-gray-50');
                } else {
                    field.classList.add('bg-gray-50');
                }
            });
            
            // If enabling manual entry, clear coordinates unless fields already have data
            if (isManual && !document.getElementById('street_address').value) {
                latInput.value = '';
                lngInput.value = '';
            }
        }
        
        // Initialize autocomplete
        initAutocomplete();
        
        // Set up the toggle event
        manualToggle.addEventListener('change', toggleAddressInput);
        
        // Apply initial state
        toggleAddressInput();
        
        // If we have existing data and fields have values, enable manual entry
        if ("{{ $jobRequest?->id }}" && !autocompleteInput.value && document.getElementById('street_address').value) {
            manualToggle.checked = true;
            toggleAddressInput();
        }
    });
</script>