@props([
    'attachments' => $attachments,
])
{{-- Image attachments --}}
<div class="md:col-span-2">
    <h4 class="text-lg font-medium text-gray-900 mb-4">Attachments</h4>
    @if($attachments->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caption</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($attachments as $attachment)
                        <tr>
                            <!-- Image Preview -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ $attachment->getSrc() }}" target="_blank" class="block w-24 h-24 overflow-hidden rounded-md shadow">
                                    <img src="{{ $attachment->getSrc() }}" alt="{{ $attachment->caption ?? 'Job image' }}" class="w-full h-full object-cover">
                                </a>
                            </td>
                            
                            <!-- Attachment Details -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attachment->original_filename ?? 'Untitled' }}</div>
                                <div class="text-xs text-gray-500">{{ $attachment->file_type ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $attachment->file_size ? round($attachment->file_size / 1024, 2) . ' KB' : 'Unknown size' }}</div>
                                <div class="text-xs text-gray-500">Added: {{ $attachment->created_at->format('M d, Y') }}</div>
                            </td>
                            
                            <!-- Caption -->
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 max-w-xs truncate">{{ $attachment->caption ?: 'No caption' }}</div>
                            </td>
                            
                            <!-- Image Type -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if(auth()->user()->user_type === 'admin')
                                    <select class="text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                            onchange="updateImageType('{{ $attachment->id }}', this.value)">
                                        <option value="user_upload" {{ $attachment->image_type === 'user_upload' ? 'selected' : '' }}>User Upload</option>
                                        <option value="worker_upload" {{ $attachment->image_type === 'worker_upload' ? 'selected' : '' }}>Worker Upload</option>
                                        <option value="completion_proof" {{ $attachment->image_type === 'completion_proof' ? 'selected' : '' }}>Completion Proof</option>
                                        <option value="admin_upload" {{ $attachment->image_type === 'admin_upload' ? 'selected' : '' }}>Admin Upload</option>
                                    </select>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $attachment->image_type)) }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Visibility Toggle -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if(auth()->user()->user_type === 'admin')
                                    <button type="button" 
                                            onclick="toggleVisibility('{{ $attachment->id }}', {{ $attachment->is_visible_to_customer ? 'false' : 'true' }})"
                                            class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded {{ $attachment->is_visible_to_customer ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $attachment->is_visible_to_customer ? 'Visible to Customer' : 'Hidden from Customer' }}
                                    </button>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attachment->is_visible_to_customer ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $attachment->is_visible_to_customer ? 'Visible to Customer' : 'Hidden from Customer' }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ $attachment->getSrc() }}" download class="text-indigo-600 hover:text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    
                                    @if(auth()->user()->user_type === 'admin')
                                        <button type="button" onclick="removeImage('{{ $attachment->id }}')" class="text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 italic">No attachments available.</p>
    @endif
</div>