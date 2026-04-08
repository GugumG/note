<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks with collaboration logic.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $filter = $request->get('filter', 'all'); // all, mine, assigned

        // Base Queries
        $myTasksQuery = $user->tasks();
        $assignedTasksQuery = Task::where('assigned_user_id', $user->id);

        if ($filter === 'mine') {
            $query = $myTasksQuery;
        } elseif ($filter === 'assigned') {
            $query = $assignedTasksQuery;
        } else {
            $query = Task::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_user_id', $user->id);
            });
        }

        $tasks = $query->orderBy('is_pinned', 'desc')
                    ->orderBy('deadline', 'asc')
                    ->get();

        return view('tasks.index', compact('tasks', 'filter'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        // Ambil semua user kecuali diri sendiri untuk dropdown "Invite"
        // Hanya Admin atau Leader yang bisa invite
        $users = [];
        if (auth()->user()->role !== 'member') {
            $users = \App\Models\User::where('id', '!=', auth()->id())
                        ->where('team', auth()->user()->team)
                        ->get();
        }
        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'category'         => 'nullable|string|max:255',
            'color'            => 'nullable|string|max:7',
            'deadline'         => 'nullable|date',
            'status'           => 'required|in:pending,suspend,on progress,complete',
            'content'          => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        // Jika owner sendiri yang membuat status 'complete', langsung set approved
        if ($validated['status'] === 'complete') {
            $validated['is_approved'] = true;
            $validated['completed_at'] = now();
        }

        auth()->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil ditambahkan!');
    }

    /**
     * Display the specific task (simplified view).
     */
    public function show(Task $task)
    {
        // Otoritas: Owner atau Assigned User
        if ($task->user_id !== auth()->id() && $task->assigned_user_id !== auth()->id()) {
            abort(403);
        }
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        // Hanya Owner yang bisa edit struktur task
        if ($task->user_id !== auth()->id()) abort(403);

        $users = [];
        if (auth()->user()->role !== 'member') {
            $users = \App\Models\User::where('id', '!=', auth()->id())
                        ->where('team', auth()->user()->team)
                        ->get();
        }
        
        return view('tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'category'         => 'nullable|string|max:255',
            'color'            => 'nullable|string|max:7',
            'deadline'         => 'nullable|date',
            'status'           => 'required|in:pending,suspend,on progress,complete',
            'content'          => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        if ($task->user_id !== auth()->id()) abort(403);

        // Jika status dirubah ke complete oleh owner
        if ($validated['status'] === 'complete' && $task->status !== 'complete') {
            $validated['is_approved'] = true;
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil diperbarui!');
    }

    public function completeForm(Task $task)
    {
        // Otoritas: Owner atau Assigned User
        if ($task->user_id !== auth()->id() && $task->assigned_user_id !== auth()->id()) {
            abort(403);
        }
        return view('tasks.complete', compact('task'));
    }

    /**
     * Mark the task as complete with validation data.
     */
    public function complete(Request $request, Task $task)
    {
        $validated = $request->validate([
            'completion_data' => 'required|string',
        ]);

        if ($task->user_id !== auth()->id() && $task->assigned_user_id !== auth()->id()) {
            abort(403);
        }

        $now = now();
        $daysDiff = null;
        if ($task->deadline) {
            $deadline = \Carbon\Carbon::parse($task->deadline);
            $daysDiff = (int)$now->diffInDays($deadline, false);
        }

        $isOwner = $task->user_id === auth()->id();
        
        // Alur Approval: Jika bukan owner, harus di-ACC (is_approved = false)
        $updateData = [
            'completion_data' => $validated['completion_data'],
            'status'          => 'complete',
            'completed_at'    => $now,
            'days_diff'       => $daysDiff,
            'is_approved'     => $isOwner, // True jika owner sendiri, False jika assignee
        ];

        $task->update($updateData);

        $msg = $isOwner ? 'Task berhasil diselesaikan!' : 'Task ditandai selesai. Menunggu persetujuan (ACC) pemilik.';
        return redirect()->route('tasks.index')->with('success', $msg);
    }

    /**
     * Memberikan ACC (Persetujuan) pada task yang diselesaikan orang lain.
     */
    public function approve(Task $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);

        $task->update(['is_approved' => true]);

        return redirect()->route('tasks.index')->with('success', 'Task telah disetujui (ACC) dan dianggap selesai.');
    }

    /**
     * Toggle the pinned status of the task.
     */
    public function togglePin(Task $task)
    {
        if ($task->user_id !== auth()->id() && $task->assigned_user_id !== auth()->id()) {
            abort(403);
        }
        $task->update([
            'is_pinned' => !$task->is_pinned,
        ]);

        return redirect()->back()->with('success', $task->is_pinned ? 'Task disematkan!' : 'Pin dilepas!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);
        $task->delete();

        return redirect()->back()->with('success', 'Task berhasil dihapus!');
    }
}
