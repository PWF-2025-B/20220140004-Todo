<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Category; // Tambahkan ini
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
{
    // $todos = Todo::all();
    // $todos = Todo::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    // dd($todos);
    // $todos = Todo::where('user_id', Auth::id())
    //     ->orderBy('is_done', 'asc')
    //     ->orderBy('created_at', 'desc')
    //     ->paginate(10);

    $todos = Todo::with('category')
        ->where('user_id', Auth::id())
        ->orderBy('is_done', 'asc')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $todosCompleted = Todo::where('user_id', Auth::id())
        ->where('is_done', true)
        ->count();

    return view('todo.index', compact('todos', 'todosCompleted'));
}

    public function create()
    {
        // Get categories belonging to the authenticated user
        $categories = Category::where('user_id', Auth::id())->get();
        return view('todo.create', compact('categories'));
    }

    public function edit(Todo $todo)
    {
        if (Auth::id() === $todo->user_id) {
            $categories = Category::where('user_id', Auth::id())->get();
            return view('todo.edit', compact('todo', 'categories'));
        }

        return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
    }


    public function update(Request $request, Todo $todo)
    {
        if (Auth::id() !== $todo->user_id) {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to update this todo!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }


    public function complete(Todo $todo)
    {
        if (Auth::id() !== $todo->user_id) {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        }

        // Ubah dari update() ke langsung mengubah properti dan simpan
        $todo->is_done = true;
        $todo->save();

        return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
    }

    public function uncomplete(Todo $todo)
    {
        if (Auth::id() !== $todo->user_id) {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        }

        // Ubah dari update() ke langsung mengubah properti dan simpan
        $todo->is_done = false;
        $todo->save();

        return redirect()->route('todo.index')->with('success', 'Todo marked as not completed.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure the category exists
        ]);

        // Create the todo and associate it with the selected category
        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
            'category_id' => $request->category_id, // Store the selected category ID
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo Created Successfully');
    }


    public function destroy(Todo $todo)
    {
        if (Auth::id() !== $todo->user_id) {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }

        $todo->delete();
        return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
    }

    public function destroyCompleted()
    {
        $todosCompleted = Todo::where('user_id', Auth::id())
                              ->where('is_done', true)
                              ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }

        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }
}

