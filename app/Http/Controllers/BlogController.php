<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
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
        $categories = Category::all();
        return view('admin.add_post', ['categories' => $categories]);
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

        try{
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
            $categories = Category::all();
            return redirect()->back()->with(['success' => 'Post added successfully!!'])
                                    ->with(['categories' => $categories]);
        } catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()])
                                    ->with(['categories' => $categories]);
        }
    }

    public function showBlog(string $id){
        if (is_numeric($id)) {
            $blog = Blog::find($id);
        } else {
            $blog = Blog::where('slug', $id)->first();
        }
        // $blog = Blog::find($id);
        $user_id = $blog->user_id;
        $user = User::find($user_id);
        $user_name = $user->name;
        if ($blog === null) {
            return abort(404);
        }
        return view("blog", ['blog' => $blog, 'user_name' => $user_name]);
    }

    public function addCategory(){
        return view('admin.add_category');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        try {
            $category = new Category;
            $category->title = $request->title;
            $category->user_id = Auth::id();
            $category->save();

            // Return a success message.
            return redirect()->back()->with(['success' => 'Category created successfully.']);
        } catch (\Exception $e) {
            // Return an error message.
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function showCategory($title){
        $blogs = Blog::where('category', $title)->get();;
        if ($blogs === null) {
            return abort(404);
        }
        return view("category", ['blogs' => $blogs]);   
    }
}
