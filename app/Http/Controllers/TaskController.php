<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks.
     */
    public function index()
    {
        $tasks = Task::orderBy('is_pinned', 'desc')
                    ->orderBy('deadline', 'asc')
                    ->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,suspend,on progress,complete',
            'content' => 'nullable|string',
        ]);

        Task::create($validated);

        return redirect()->route('notes.index')->with('success', 'Task berhasil ditambahkan!');
    }

    /**
     * Display the specific task (simplified view).
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the task.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'status' => 'required|in:pending,suspend,on progress,complete',
            'content' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->route('notes.index')->with('success', 'Task berhasil diperbarui!');
    }

    /**
     * Show the completion validation form.
     */
    public function completeForm(Task $task)
    {
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

        $now = now();
        $daysDiff = null;

        if ($task->deadline) {
            // hitung jumlah hari nya ( tanggal deadline - tgl selesai)
            $deadline = \Carbon\Carbon::parse($task->deadline);
            $daysDiff = $now->diffInDays($deadline, false);
            // $daysDiff > 0 means finished early, negative means late
        }

        $task->update([
            'completion_data' => $validated['completion_data'],
            'status' => 'complete',
            'completed_at' => $now,
            'days_diff' => $daysDiff,
        ]);

        return redirect()->route('notes.index')->with('success', 'Task berhasil diselesaikan!');
    }

    /**
     * Toggle the pinned status of the task.
     */
    public function togglePin(Task $task)
    {
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
        $task->delete();

        return redirect()->back()->with('success', 'Task berhasil dihapus!');
    }
}
