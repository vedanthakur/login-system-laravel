<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BlogController extends Controller
{
    public function index()
    {
        return view('admin.add_post');
    }

    public function AddPost()
    {
        return view('admin.add_post');
    }

    

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'slug' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'category' => 'required|string',
            'date' => 'date'
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('blogImages'), $imageName);

        $blog = new Blog;
        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->content = $request->content;
        $blog->category = $request->category;
        $blog->image = $imageName;
        $blog->user_id = Auth::id();
        $blog->updated_at = $request->date;
        $blog->status = $request->status;
        $blog->save();
        return view('admin.add_post');
    }
}
