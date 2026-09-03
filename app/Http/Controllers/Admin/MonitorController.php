<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Queries\StudentMonitor;
use Inertia\Inertia;
use Inertia\Response;

class MonitorController extends Controller
{
    public function index(StudentMonitor $monitor): Response
    {
        return Inertia::render('admin/pantau/index', [
            'students' => $monitor->handle(),
        ]);
    }
}
