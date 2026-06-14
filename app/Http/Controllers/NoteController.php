<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::where('user_id', $request->user()->id);
        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('title', 'like', '%'.$request->search.'%')->orWhere('body', 'like', '%'.$request->search.'%'));
        }
        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }
        $notes = $query->orderByDesc('is_pinned')->latest()->get();
        return view('notes.index', compact('notes'));
    }

    public function show(Note $note)
    {
        $this->authorizeOwner($note);
        return view('notes.show', compact('note'));
    }

    public function store(Request $request)
    {
        $note = Note::create($this->validated($request) + ['user_id' => $request->user()->id]);
        return response()->json(['message' => 'Note saved.', 'note' => $note, 'html' => view('notes._card', compact('note'))->render()]);
    }

    public function update(Request $request, Note $note)
    {
        $this->authorizeOwner($note);
        $data = $this->validated($request);
        if (! $request->has('is_pinned')) {
            $data['is_pinned'] = $note->is_pinned;
        }
        $note->update($data);
        return response()->json(['message' => 'Note updated.', 'note' => $note]);
    }

    public function pin(Note $note)
    {
        $this->authorizeOwner($note);
        $note->update(['is_pinned' => ! $note->is_pinned]);
        return response()->json(['message' => $note->is_pinned ? 'Note pinned.' : 'Note unpinned.', 'note' => $note]);
    }

    public function destroy(Note $note)
    {
        $this->authorizeOwner($note);
        $note->delete();
        return response()->json(['message' => 'Note deleted.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['nullable', 'string'],
            'category' => ['required', 'in:Ideas,Learning,Work,Personal,Important'],
            'color' => ['nullable', 'string', 'max:40'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);

        return $data + ['color' => 'soft-yellow', 'is_pinned' => $request->boolean('is_pinned')];
    }

    private function authorizeOwner(Note $note): void
    {
        abort_unless($note->user_id === auth()->id(), 403);
    }
}
