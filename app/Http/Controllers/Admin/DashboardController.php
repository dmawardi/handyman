<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\JobUpdate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Status counts for job requests
        $statusCounts = JobRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        // Ensure all statuses have a value (even if 0)
        $allStatuses = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
        foreach ($allStatuses as $status) {
            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = 0;
            }
        }
        
        // Recent job requests (last 10)
        $recentJobRequests = JobRequest::with(['requestor', 'worker'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Recently completed jobs (last 5)
        $recentlyCompletedJobs = JobRequest::with(['requestor', 'worker'])
            ->where('status', 'Completed')
            ->orderBy('completion_date', 'desc')
            ->take(5)
            ->get();
        
        // Urgent pending requests
        $urgentPendingRequests = JobRequest::with(['requestor', 'worker'])
            ->where('status', 'Pending')
            ->whereIn('urgency_level', ['High - Within 48 hours', 'Emergency - Same day'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Stale pending requests (pending for more than 3 days)
        $threeDaysAgo = Carbon::now()->subDays(3);
        $stalePendingRequests = JobRequest::with(['requestor', 'worker'])
            ->where('status', 'Pending')
            ->where('created_at', '<', $threeDaysAgo)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Worker metrics
        $workerMetrics = User::where('user_type', 'worker')
            ->withCount([
                'jobRequestsAssigned',
                'jobRequestsAssigned as completed_jobs_count' => function ($query) {
                    $query->where('status', 'Completed');
                },
                'jobRequestsAssigned as in_progress_jobs_count' => function ($query) {
                    $query->where('status', 'In Progress');
                }
            ])
            ->get()
            ->map(function ($worker) {
                $worker->completion_rate = $worker->assigned_jobs_count > 0 
                    ? round(($worker->completed_jobs_count / $worker->assigned_jobs_count) * 100, 1) 
                    : 0;
                return $worker;
            });
        
        // Job type distribution
        $jobTypeDistribution = JobRequest::select('job_type', DB::raw('count(*) as count'))
            ->groupBy('job_type')
            ->orderBy('count', 'desc')
            ->get();
        
        // Time-based metrics
        $now = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $weeklyJobRequestsCount = JobRequest::where('created_at', '>=', $startOfWeek)->count();
        $monthlyJobRequestsCount = JobRequest::where('created_at', '>=', $startOfMonth)->count();
        
        // Previous period comparisons
        $previousWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $previousWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $previousWeekCount = JobRequest::whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])->count();
        $previousMonthCount = JobRequest::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        
        $weeklyChange = $previousWeekCount > 0 
            ? round((($weeklyJobRequestsCount - $previousWeekCount) / $previousWeekCount) * 100, 1) 
            : 100;
        $monthlyChange = $previousMonthCount > 0 
            ? round((($monthlyJobRequestsCount - $previousMonthCount) / $previousMonthCount) * 100, 1) 
            : 100;
        
        // Recent updates/activity
        $recentUpdates = JobUpdate::with(['jobRequest', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'statusCounts',
            'recentJobRequests',
            'recentlyCompletedJobs',
            'urgentPendingRequests',
            'stalePendingRequests',
            'workerMetrics',
            'jobTypeDistribution',
            'weeklyJobRequestsCount',
            'monthlyJobRequestsCount',
            'weeklyChange',
            'monthlyChange',
            'recentUpdates'
        ));
    }
}
