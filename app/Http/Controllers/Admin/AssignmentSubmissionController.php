<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mirrors the original Filament SubmissionsRelationManager on AssignmentResource:
 * lists a single assignment's submissions and provides a "Grade" action
 * (grade + feedback), auto-stamping status/graded_at/graded_by when a grade is set.
 */
class AssignmentSubmissionController extends Controller
{
    public function index(Request $request, Assignment $assignment): View
    {
        $query = $assignment->submissions()->with(['user', 'grader']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $submissions = $query->latest('submitted_at')->paginate(15)->withQueryString();

        return view('admin.assignments.submissions.index', [
            'assignment'  => $assignment,
            'submissions' => $submissions,
        ]);
    }

    public function edit(Assignment $assignment, AssignmentSubmission $submission): View
    {
        abort_unless($submission->assignment_id === $assignment->id, 404);

        return view('admin.assignments.submissions.edit', [
            'assignment' => $assignment,
            'submission' => $submission->load(['user', 'grader']),
        ]);
    }

    public function update(Request $request, Assignment $assignment, AssignmentSubmission $submission): RedirectResponse
    {
        abort_unless($submission->assignment_id === $assignment->id, 404);

        $data = $request->validate([
            'grade'    => 'nullable|numeric|min:0|max:' . $assignment->max_points,
            'feedback' => 'nullable|string',
        ]);

        // Mirrors SubmissionsRelationManager's mutateFormDataUsing: setting a
        // grade automatically marks the submission as graded.
        if (! empty($data['grade']) && $data['grade'] !== '') {
            $data['status'] = 'graded';
            $data['graded_at'] = now();
            $data['graded_by'] = auth()->id();
        }

        $submission->update($data);

        return redirect()->route('admin.assignments.submissions.index', $assignment)
            ->with('success', 'Submission graded successfully!');
    }
}
