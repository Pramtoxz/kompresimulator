<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Queries\AuthLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthLogController extends Controller
{
    public function index(Request $request, AuthLog $log): Response
    {
        $event = $request->string('event')->toString();

        return Inertia::render('admin/riwayat-masuk/index', $log->handle(
            in_array($event, ['login', 'logout', 'failed'], true) ? $event : null,
        ));
    }
}
