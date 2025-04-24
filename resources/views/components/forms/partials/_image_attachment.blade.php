@props([
    'jobRequest' => null,
])
<div class="border-b pb-4 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Image Attachments</h3>
    <div class="space-y-4">
        <!-- Existing Images -->
        @if(isset($jobRequest) && $jobRequest->images && count($jobRequest->images) > 0)
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Current Attachments</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($jobRequest->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->getSrc() }}" alt="Attachment" data-id={{ $image->id }} class="existing-images w-full h-32 object-cover rounded-md shadow">
                                <button type="button" class="existing-images absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded shadow group-hover:opacity-100 opacity-0 transition-opacity duration-200" onclick="removeImage('{{ $image->id }}')">
                                    Remove
                                </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Add New Images -->
        <div>
            <x-input-label for="images" :value="__('Add Images')" />
            <input id="images" name="images[]" type="file" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" multiple accept="image/*">
            @if($errors->has('images'))
                <x-input-error :messages="$errors->get('images')" class="mt-2" />
            @endif
            @foreach($errors->get('images.*') as $key => $error)
                <x-input-error :messages="$error" class="mt-2" />
            @endforeach
            <p class="mt-1 text-xs text-gray-500">You can upload multiple images. Accepted formats: JPG, PNG, GIF.</p>
            {{-- Delete image hidden input --}}
            <input type="hidden" name="delete_images[]" id="delete_images" value="">
        </div>

        <!-- Preview New Images -->
        <div id="image-preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden">
            <!-- Preview images will be dynamically added here -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('images');
        const previewContainer = document.getElementById('image-preview-container');

        // Handle image input change
        imageInput.addEventListener('change', function () {
            // Clear previous previews
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');

            // Assign the selected files to a variable
            const files = imageInput.files;
            // Check if there are files
            if (files.length > 0) {
                previewContainer.classList.remove('hidden');

                // Loop through each file and create a preview
                Array.from(files).forEach(file => {
                    const reader = new FileReader();

                    // Create a preview for each file
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.className = 'w-full h-32 object-cover rounded-md shadow';
                        // Add a unique ID to the image element
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative group';
                        wrapper.appendChild(img);
                        // Add the preview to the preview container
                        previewContainer.appendChild(wrapper);
                    };
                    // Read the file as a data URL
                    reader.readAsDataURL(file);
                });
            }
        });
    });

    // This function should be called when the page loads to mark existing images for removal
    function markExistingImagesBasedOnDeleteImages() {
        // Grab the existing images
        const existingImages = document.querySelectorAll('img[class~="existing-images"]');
        // Get the delete_images input value
        const deleteImagesInput = document.querySelector('input[name="delete_images[]"]');
        const deleteImagesArray = deleteImagesInput.value.split(',').map(id => id.trim());


        // Loop through existing images and mark them if the id is in the delete_images array
        existingImages.forEach(image => {
            // Get the image ID from the data-id attribute
            const imageId = image.getAttribute('data-id');

            // Based on if the image ID is in the delete_images array, change the appearance
            changeAppearanceOfImagePreview(image, image.nextElementSibling, deleteImagesArray.includes(imageId));
        });
    }
    // Function to handle image removal by adding it to the delete_images array
    function removeImage(imageId) {
        // Confirm the removal action with prompt
        if (confirm('Are you sure you want to remove this image?')) {
            // Grab the delete_images input
            const deleteImagesInput = document.querySelector('input[name="delete_images[]"]');
            // Get the current value of delete_images
            let currentDeleteImages = deleteImagesInput.value ? deleteImagesInput.value.split(',') : [];
            // Add the image ID to the delete_images array
            if (!currentDeleteImages.includes(imageId)) {
                currentDeleteImages.push(imageId);
            }
            // Update the delete_images input value
            deleteImagesInput.value = currentDeleteImages.join(',');

            markExistingImagesBasedOnDeleteImages(); // Call the function to update the UI
        }
    }
    // Function to handle image restoration by removing it from the delete_images array
    function restoreImage(imageId) {
        // Grab the delete_images input
        const deleteImagesInput = document.querySelector('input[name="delete_images[]"]');
        // Get the current value of delete_images
        let currentDeleteImages = deleteImagesInput.value ? deleteImagesInput.value.split(',') : [];
        // Remove the image ID from the delete_images array
        currentDeleteImages = currentDeleteImages.filter(id => id !== imageId);
        // Update the delete_images input value
        deleteImagesInput.value = currentDeleteImages.join(',');

        markExistingImagesBasedOnDeleteImages(); // Call the function to update the UI
    }
    // Toggle the opacity of an image element
    function changeAppearanceOfImagePreview(imageElement, buttonElement, markAsDelete) {
        // If the image needs to be marked as delete, set classes and styles accordingly
        if (markAsDelete) {
            imageElement.style.opacity = '0.5';
            // change button text and color
            buttonElement.classList.remove('bg-red-600');
            buttonElement.classList.add('bg-green-600');
            buttonElement.innerText = 'Restore';
            // Remove the existing onclick event to prevent multiple calls
            buttonElement.onclick = null;
            // Add event listener to restore the image
            buttonElement.onclick = function () {
                restoreImage(imageElement.getAttribute('data-id'));
            };

        // If the image is not marked for delete, reset classes and styles
        } else {
            imageElement.style.opacity = '1';
            // change button text and color
            buttonElement.classList.remove('bg-green-600');
            buttonElement.classList.add('bg-red-600');
            buttonElement.innerText = 'Remove';

            // Remove the existing onclick event to prevent multiple calls
            buttonElement.onclick = null;
            // Add event listener to restore the image
            buttonElement.onclick = function () {
                removeImage(imageElement.getAttribute('data-id'));
            };
        }
    }
</script>