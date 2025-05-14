<x-app-layout title="Handyman Bali | Admin Dashboard">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <div>
                <a href="{{ route('admin.job-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    View Job Requests
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Status Overview Cards -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Overview</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Pending Jobs Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Jobs</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ $statusCounts['Pending'] }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <a href="{{ route('admin.job-requests.index', ['status' => 'Pending']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all</a>
                        </div>
                    </div>

                    <!-- In Progress Jobs Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ $statusCounts['In Progress'] }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <a href="{{ route('admin.job-requests.index', ['status' => 'In Progress']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all</a>
                        </div>
                    </div>

                    <!-- Completed Jobs Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Completed Jobs</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ $statusCounts['Completed'] }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <a href="{{ route('admin.job-requests.index', ['status' => 'Completed']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all</a>
                        </div>
                    </div>

                    <!-- Cancelled Jobs Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Cancelled Jobs</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ $statusCounts['Cancelled'] }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <a href="{{ route('admin.job-requests.index', ['status' => 'Cancelled']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Period Metrics Cards -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Business Metrics</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- This Week -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">This Week's Job Requests</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ $weeklyJobRequestsCount }}</div>
                                            <div class="ml-2 flex items-baseline text-sm font-semibold {{ $weeklyChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                @if($weeklyChange >= 0)
                                                    <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                                <span class="sr-only">{{ $weeklyChange >= 0 ? 'Increased' : 'Decreased' }} by</span>
                                                {{ abs($weeklyChange) }}%
                                            </div>
                                        </dd>
                                        <dd class="mt-1 text-sm text-gray-500">Compared to previous week</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- This Month -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">This Month's Job Requests</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ $monthlyJobRequestsCount }}</div>
                                            <div class="ml-2 flex items-baseline text-sm font-semibold {{ $monthlyChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                @if($monthlyChange >= 0)
                                                    <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                                <span class="sr-only">{{ $monthlyChange >= 0 ? 'Increased' : 'Decreased' }} by</span>
                                                {{ abs($monthlyChange) }}%
                                            </div>
                                        </dd>
                                        <dd class="mt-1 text-sm text-gray-500">Compared to previous month</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attention Required Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Attention Required</h2>
                
                <!-- Urgent Pending Requests -->
                <div class="bg-white overflow-hidden shadow rounded-lg mb-4">
                    <div class="px-4 py-5 sm:px-6 bg-red-50">
                        <h3 class="text-lg leading-6 font-medium text-red-800">
                            Urgent Pending Requests ({{ $urgentPendingRequests->count() }})
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-red-600">
                            High priority requests that need immediate attention
                        </p>
                    </div>
                    
                    @if($urgentPendingRequests->count() > 0)
                        <div class="border-t border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Number</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($urgentPendingRequests as $jobRequest)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $jobRequest->job_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->contact_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->job_type }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                                    {{ $jobRequest->urgency_level }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ route('admin.job-requests.show', $jobRequest->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
                            No urgent pending requests at this time
                        </div>
                    @endif
                </div>
                
                <!-- Stale Pending Requests -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-yellow-50">
                        <h3 class="text-lg leading-6 font-medium text-yellow-800">
                            Pending Requests > 3 Days Old ({{ $stalePendingRequests->count() }})
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-yellow-600">
                            Requests that have been waiting for action for more than 3 days
                        </p>
                    </div>
                    
                    @if($stalePendingRequests->count() > 0)
                        <div class="border-t border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Number</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waiting For</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stalePendingRequests as $jobRequest)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $jobRequest->job_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->contact_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->job_type }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ route('admin.job-requests.show', $jobRequest->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
                            No stale pending requests at this time
                        </div>
                    @endif
                </div>
            </div>

            <!-- Two-column layout for recent activity and job type distribution -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Activity Section -->
                <div class="lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
                    
                    <!-- Recent Job Requests -->
                    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Recent Job Requests
                            </h3>
                        </div>
                        
                        <div class="border-t border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Number</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentJobRequests->take(5) as $jobRequest)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <a href="{{ route('admin.job-requests.show', $jobRequest->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        {{ $jobRequest->job_number }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->contact_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $jobRequest->job_type }}
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                <a href="{{ route('admin.job-requests.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View all job requests</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Updates -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Recent Updates
                            </h3>
                        </div>
                        
                        <div class="border-t border-gray-200">
                            <ul class="divide-y divide-gray-200">
                                @forelse($recentUpdates as $update)
                                    <li class="px-4 py-4">
                                        <div class="flex space-x-3">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('admin.job-requests.show', $update->jobRequest->id) }}" class="hover:underline">
                                                        {{ $update->jobRequest->job_number }}
                                                    </a> - {{ $update->update_type }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ Str::limit($update->update_description, 100) }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Updated {{ $update->created_at->diffForHumans() }} by {{ $update->user->name ?? 'System' }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="px-4 py-6 text-center text-gray-500">
                                        No recent updates
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Job Type Distribution and Worker Stats -->
                <div class="lg:col-span-1">
                    <!-- Job Type Distribution -->
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Type Distribution</h2>
                    <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                        <div class="p-5">
                            <div class="space-y-4">
                                @foreach($jobTypeDistribution as $jobType)
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-medium text-gray-900">{{ $jobType->job_type }}</div>
                                            <div class="text-sm font-medium text-gray-500">{{ $jobType->count }}</div>
                                        </div>
                                        <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                            @php
                                                $total = $jobTypeDistribution->sum('count');
                                                $percentage = $total > 0 ? ($jobType->count / $total) * 100 : 0;
                                                $colorClass = match($jobType->job_type) {
                                                    'Plumbing' => 'bg-blue-600',
                                                    'Electrical' => 'bg-yellow-500',
                                                    'Painting' => 'bg-purple-500',
                                                    'Appliance Repair' => 'bg-red-500',
                                                    'Outdoor/Garden' => 'bg-green-500',
                                                    'Installations' => 'bg-indigo-500',
                                                    'Cleaning/Maintenance' => 'bg-gray-500',
                                                    default => 'bg-blue-500'
                                                };
                                            @endphp
                                            <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Worker Stats -->
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Worker Stats</h2>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Worker Performance
                            </h3>
                        </div>
                        
                        <div class="border-t border-gray-200">
                            <ul class="divide-y divide-gray-200">
                                @forelse($workerMetrics as $worker)
                                    <li class="px-4 py-4">
                                        <div class="flex justify-between">
                                            <div class="text-sm font-medium text-gray-900">{{ $worker->name }}</div>
                                            <div class="text-sm font-medium text-gray-500">{{ $worker->completed_jobs_count }}/{{ $worker->assigned_jobs_count }}</div>
                                        </div>
                                        <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $worker->completion_rate }}%"></div>
                                        </div>
                                        <div class="mt-1 flex justify-between text-xs text-gray-500">
                                            <div>Completion Rate: {{ $worker->completion_rate }}%</div>
                                            <div>In Progress: {{ $worker->in_progress_jobs_count }}</div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="px-4 py-6 text-center text-gray-500">
                                        No worker data available
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>