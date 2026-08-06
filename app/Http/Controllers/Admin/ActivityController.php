<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::with('causer')
            ->when($request->causer_id, fn($q, $id) => $q->where('causer_id', $id))
            ->when($request->log_name, fn($q, $n) => $q->where('log_name', $n))
            ->latest()
            ->paginate(30);

        return view('admin.activity.index', compact('activities'));
    }
}
