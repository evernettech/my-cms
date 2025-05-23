<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class BlogController extends Controller
{
    public function __construct()
    {
       $this->middleware('permission:create blogs')->only(['create', 'store']);
       $this->middleware('permission:edit blogs')->only(['edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');
        $perPage = $request->input('perPage', 10); // Default to 10

        $query = Blog::query();

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $blogs = $query->orderBy($sortField, $sortDirection)
                        ->paginate($perPage)
                        ->appends($request->all());

        return view('blogs.index', compact('blogs', 'sortField', 'sortDirection', 'perPage', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blogs.create');
    }

    public function uploadImage(Request $request)
    {
        
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        
        // Validate manually (Laravel validation depends on realPath too)
        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid upload.'], 400);
        }

        $filename = $file->getClientOriginalName();
        $storagePath = storage_path('app/public/blogs');


        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $moved = $file->move($storagePath, $filename); // ← classic move

        if (!$moved) {
            return response()->json(['error' => 'Failed to move uploaded file.'], 500);
        }

        $url = asset('storage/blogs/' . rawurlencode($filename));

        return response()->json(['location' => $url]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'seo_title' => $request->input('seo_title'),
                'seo_keywords' => $request->input('seo_keywords'),
                'seo_description' => $request->input('seo_description'),
                'subdomain_id' => 1,
            ]);

            DB::commit();

            return redirect()->route('blogs.index')->with('success', __('Blog created!'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Failed to create blog: ') . $e->getMessage());
        }
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
        ]);

        $blog->update($validated);

        return redirect()->route('blogs.index')->with('success', __('Blog updated!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', __('Blog moved to trash!'));
    }
    /**
     * Restore the specified resource from trash.
     */
    public function restore($id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog->restore();

        return redirect()->route('blogs.index')->with('success', __('Blog restored!'));
    }

    public function trash()
    {
        $blogs = Blog::onlyTrashed()->latest()->get();

        return view('blogs.trash', compact('blogs'));
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->forceDelete();

        return redirect()->route('blogs.trash')->with('success', __('Blog permanently deleted.'));
    }

}
