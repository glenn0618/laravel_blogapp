<?php

namespace App\Http\Controllers;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
    {
        $blogs = Blog::latest()->get();
        return view('blogs.index', compact('blogs'));
    }


    public function create()
    {
    return view('blogs.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
    
    $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);


    $imageName = null;

    // 2️⃣ Handle image upload if exists
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('uploads'), $imageName);
    }

    // 3️⃣ Save blog post
    Blog::create([
        'title'   => $request->title,
        'content' => $request->content,
        'image'   => $imageName,
    ]);

    // 4️⃣ Redirect with success message
    return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
      return view('blogs.create');
}

 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
 public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }


    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imageName = $blog->image;

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($imageName && file_exists(public_path('uploads/' . $imageName))) {
                unlink(public_path('uploads/' . $imageName));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
        }

        $blog->update([
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $imageName,
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
 public function destroy(Blog $blog)
    {
        if ($blog->image && file_exists(public_path('uploads/' . $blog->image))) {
            unlink(public_path('uploads/' . $blog->image));
        }

        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
    }
}
