<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::all();
        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)
            ->whereNotIn('id', $category->children->pluck('id'))
            ->get();
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Prevent category from becoming its own parent or descendant
        if ($request->parent_id && $request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'Kategori tidak dapat menjadi parent dirinya sendiri.']);
        }

        // Check if selected parent is not a child of this category
        if ($request->parent_id && $category->children->pluck('id')->contains($request->parent_id)) {
            return back()->withErrors(['parent_id' => 'Child kategori tidak dapat menjadi parent.']);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Check if category has articles
        if ($category->articles->count() > 0) {
            return back()->withErrors(['delete' => 'Kategori masih memiliki artikel terkait.']);
        }

        // Handle child categories if any
        if ($category->children->count() > 0) {
            // Option 1: Set child categories to null parent
            foreach ($category->children as $child) {
                $child->update(['parent_id' => null]);
            }

            // Option 2: Move child categories to parent of this category
            /*
            foreach ($category->children as $child) {
                $child->update(['parent_id' => $category->parent_id]);
            }
            */
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category berhasil dihapus.');
    }
}
