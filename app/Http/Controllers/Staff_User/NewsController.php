<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();
        return view('user_staff.berita.index', compact('news'));
    }
    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return view('user_staff.berita.show', compact('news'));
    }
    public function update(Request $request, $slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news->title = $request->title;
        $news->content = $request->content;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
            $news->image = $imageName;
        }

        $news->save();

        return redirect()->route('berita.staffIndex')->with('success', 'Berita berhasil diperbarui.');
    }

    public function create()
    {
        return view('user_staff.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|unique:news,title|string|max:255',
            'content'   => 'required|string',
            'image'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'title.required'    => 'Judul berita wajib diisi.',
            'title.string'      => 'Judul berita harus berupa teks.',
            'title.max'         => 'Judul berita maksimal 255 karakter.',
            'title.unique'      => 'Judul berita tidak boleh sama dengan berita lainnya.',

            'content.required'  => 'Konten berita wajib diisi.',
            'content.string'    => 'Konten berita harus berupa teks.',
            
            'image.required'    => 'Gambar berita wajib diisi.',
            'image.file'        => 'File gambar tidak valid.',
            'image.mimes'       => 'Gambar harus berupa JPG/PNG.',
            'image.max'         => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $imagePath = $file->storeAs('documents/news', $filename, 'public');
        }

        News::create([
            'title'   => $request->title,
            'slug'    => Str::slug($request->title),
            'content' => $request->content,
            'image'   => $imagePath,
        ]);

        return redirect()->route('berita.staffIndex')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image) {
            $documentPath = public_path('uploads/documents/news/' . basename($news->image));
            if (file_exists($documentPath)) {
                unlink($documentPath);
            }
        }

        $news->delete();

        return redirect()->route('berita.staffIndex')->with('success', 'Berita berhasil dihapus.');
    }

    public function toggleHeadline(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $news->is_headline = $request->input('is_headline');
        $news->save();

        return back()->with('success', 'Status headline diperbarui.');
    }
    
    public function togglePublish(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $news->is_published = $request->input('is_published');
        $news->save();

        return back()->with('success', 'Status publish diperbarui.');
    }
}
