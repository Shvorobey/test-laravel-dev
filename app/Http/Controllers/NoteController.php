<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->notes;

        return response()->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    public function show($id)
    {
        $note = auth()->user()->notes()->find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note with id ' . $id . ' not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $note->toArray()
        ], 400);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'description' => 'required'
        ]);

        $note = new Note();
        $note->title = $request->title;
        $note->description = $request->description;

        if (auth()->user()->notes()->save($note))
            return response()->json([
                'success' => true,
                'data' => $note->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Note could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $note = auth()->user()->notes()->find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note with id ' . $id . ' not found'
            ], 400);
        }

        $updated = $note->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true,
                'data' => $note->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Note could not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $note = auth()->user()->notes()->find($id);

        if (!$note) {
            return response()->json([
                'success' => false,
                'message' => 'Note with id ' . $id . ' not found'
            ], 400);
        }

        if ($note->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Note could not be deleted'
            ], 500);
        }
    }
}
