@props([
    'jobRequest'=> null,
])
<tr>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">
        #{{ $jobRequest->job_number }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $jobRequest->job_type }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm">
        @php
            $urgencyClass = match($jobRequest->urgency_level) {
                'Low - Within 2 weeks' => 'text-blue-600',
                'Medium - Within 1 week' => 'text-yellow-600',
                'High - Within 48 hours' => 'text-orange-600',
                'Emergency - Same day' => 'text-red-600',
                default => 'text-gray-600'
            };
        @endphp
        <span class="{{ $urgencyClass }}">
            {{ $jobRequest->urgency_level }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @php
            $statusClass = match($jobRequest->status) {
                'Pending' => 'bg-yellow-100 text-yellow-800',
                'In Progress' => 'bg-blue-100 text-blue-800',
                'Completed' => 'bg-green-100 text-green-800',
                'Cancelled' => 'bg-red-100 text-red-800',
                default => 'bg-gray-100 text-gray-800'
            };
        @endphp
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
            {{ $jobRequest->status }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $jobRequest->created_at->format('M d, Y') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        <div class="flex space-x-2">
            <a href="{{ route('job-requests.show', $jobRequest->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
            @if($jobRequest->status === 'Pending')
                <form method="POST" action="{{ route('job-requests.destroy', $jobRequest->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this job request?')">Cancel</button>
                </form>
            @endif
        </div>
    </td>
</tr>