<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Elearning;
use App\Models\User;
use Illuminate\Http\Request;

class ElearningController extends Controller
{
    public function index(Request $request)
    {
        // Get all classes that have elearning content
        $classes = Elearning::select('class_id')
            ->whereNotNull('class_id')
            ->where('class_id', '!=', '')
            ->distinct()
            ->pluck('class_id');

        // If class selected
        $contents = Elearning::when($request->class, function ($query) use ($request) {
            $query->where('class_id', $request->class);
        })->latest()->paginate(10);

        return view('admin.elearning.index', compact('classes', 'contents'));
    }

    public function create()
    {
        $classes = User::select('grade')
            ->whereNotNull('grade')
            ->where('grade', '!=', '')
            ->distinct()
            ->get();
        return view('admin.elearning.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'topic' => 'required',
            'sub_topic' => 'nullable',
            'content' => 'nullable',
            'video_link' => 'nullable',
            'video_file' => 'nullable|mimes:mp4,mov,avi|max:20480',
            'pdf_file' => 'nullable|mimes:pdf|max:10240'
        ]);

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')
                ->store('elearning/videos', 'public');
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')
                ->store('elearning/pdfs', 'public');
        }

        Elearning::create([
            'school_id' => auth('admin')->user()->school_id,
            'class_id' => $request->class_id, // FIXED
            'topic' => $request->topic,
            'sub_topic' => $request->sub_topic,
            'content' => $request->content,
            'video_link' => $request->video_link,
            'video_file' => $videoPath,
            'pdf_file' => $pdfPath
        ]);

        return redirect()->route('admin.elearning.index')
            ->with('success', 'Content uploaded successfully');
    }

    public function edit($id)
    {
        $content = Elearning::findOrFail($id);
        return view('admin.elearning.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = Elearning::findOrFail($id);

        $data = $request->except(['video_file', 'pdf_file']);

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $request->file('video_file')->store('elearning/videos', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $request->file('pdf_file')->store('elearning/pdfs', 'public');
        }

        $content->update($data);

        return redirect()->route('admin.elearning.index')
            ->with('success', 'Content updated successfully');
    }

    public function destroy($id)
    {
        Elearning::findOrFail($id)->delete();

        return back()->with('success', 'Content deleted');
    }
}
