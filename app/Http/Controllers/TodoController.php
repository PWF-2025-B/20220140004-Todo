<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo; // Tambahkan ini
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        //$todos = Todo::all();
        $todos = Todo::where('user_id', Auth::id())->orderBy('created_at','desc')->get();
        //dd($todos);
        return view('todo.index',compact('todos'));
    }

    public function create()
    {
        return view('todo.create');
    }

    public function edit()
    {
        return view('todo.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo Created Successfully');
    }
}
