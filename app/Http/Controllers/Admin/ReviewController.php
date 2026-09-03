<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewStoreRequest;
use App\Models\Attempt;
use App\Queries\AttemptReview;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function index(AttemptReview $review): Response
    {
        return Inertia::render('admin/penilaian/index', [
            'attempts' => $review->list(),
        ]);
    }

    public function show(Attempt $attempt, AttemptReview $review): Response
    {
        return Inertia::render('admin/penilaian/show', [
            'attempt' => $review->detail($attempt),
        ]);
    }

    public function store(ReviewStoreRequest $request, Attempt $attempt, AttemptReview $review): RedirectResponse
    {
        $review->save(
            $attempt,
            (int) $request->user()->id,
            $request->score(),
            $request->body(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Penilaian tersimpan.']);

        return to_route('admin.reviews.show', $attempt);
    }
}
