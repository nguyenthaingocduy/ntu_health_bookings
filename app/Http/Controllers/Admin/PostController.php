<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.posts.index', compact('posts'));
    }
    
    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }
    
    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $featuredImagePath = null;
            
            if ($request->hasFile('featured_image')) {
                $featuredImage = $request->file('featured_image');
                $featuredImagePath = $featuredImage->store('images/posts', 'public');
            }
            
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            
            while (Post::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            $publishedAt = $request->status === 'published' ? ($request->published_at ?? now()) : null;
            
            $post = Post::create([
                'id' => Str::uuid(),
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'featured_image' => $featuredImagePath,
                'author_id' => Auth::id(),
                'status' => $request->status,
                'is_featured' => $request->is_featured ?? false,
                'published_at' => $publishedAt,
            ]);
            
            return redirect()->route('admin.posts.show', $post->id)
                ->with('success', 'Bài viết đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi tạo bài viết: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('author')->findOrFail($id);
        
        return view('admin.posts.show', compact('post'));
    }
    
    /**
     * Show the form for editing the specified post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        
        return view('admin.posts.edit', compact('post'));
    }
    
    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $featuredImagePath = $post->featured_image;
            
            if ($request->hasFile('featured_image')) {
                // Delete old image if exists
                if ($featuredImagePath && Storage::disk('public')->exists($featuredImagePath)) {
                    Storage::disk('public')->delete($featuredImagePath);
                }
                
                $featuredImage = $request->file('featured_image');
                $featuredImagePath = $featuredImage->store('images/posts', 'public');
            }
            
            // Update slug only if title has changed
            if ($post->title !== $request->title) {
                $slug = Str::slug($request->title);
                $originalSlug = $slug;
                $count = 1;
                
                while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                
                $post->slug = $slug;
            }
            
            $publishedAt = $request->status === 'published' ? ($request->published_at ?? $post->published_at ?? now()) : null;
            
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'featured_image' => $featuredImagePath,
                'status' => $request->status,
                'is_featured' => $request->is_featured ?? false,
                'published_at' => $publishedAt,
            ]);
            
            return redirect()->route('admin.posts.show', $post->id)
                ->with('success', 'Bài viết đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật bài viết: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified post from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        try {
            // Delete featured image if exists
            if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $post->delete();
            
            return redirect()->route('admin.posts.index')
                ->with('success', 'Bài viết đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa bài viết: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the status of the specified post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,published,archived',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $publishedAt = $request->status === 'published' ? ($post->published_at ?? now()) : $post->published_at;
            
            $post->update([
                'status' => $request->status,
                'published_at' => $publishedAt,
            ]);
            
            return redirect()->route('admin.posts.show', $post->id)
                ->with('success', 'Trạng thái bài viết đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật trạng thái bài viết: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle the featured status of the specified post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleFeatured($id)
    {
        $post = Post::findOrFail($id);
        
        try {
            $post->update([
                'is_featured' => !$post->is_featured,
            ]);
            
            return redirect()->route('admin.posts.show', $post->id)
                ->with('success', 'Trạng thái nổi bật của bài viết đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật trạng thái nổi bật của bài viết: ' . $e->getMessage());
        }
    }
}
