<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteCreateRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;


class NotesController extends Controller
{
    public function index()
    {
        // dd(Request::only('search'));

        return Inertia::render('Notes/Index', [
            // 'filters' => dd(Request::only('search')),
            'filters' => Request::all('search'),
            'notes' => new NoteResource(
                Note::when(Request::only('search') , function($q){
                    return $q->where('note','like','%'.Request::only('search')['search'] .'%');
                })->paginate()
                    
                    ->appends(Request::all())
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Notes/Create');
    }

    public function store(NoteCreateRequest $request)
    {
        Note::create(
            $request->validated()
        );

        return Redirect::route('notes')->with('success', 'Note created.');
    }

    public function edit(Note $note)
    {
        return Inertia::render('Notes/Edit', [
            'note' => new NoteResource($note),
        ]);
    }

    public function update(Note $note, NoteUpdateRequest $request)
    {
        $note->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Note updated.');
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return Redirect::back()->with('success', 'Note deleted.');
    }

}
