<?php

namespace App\Http\Controllers\learning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
  public function index()
  {
    $courses = Course::all();
    return view('content.learning.trainings', compact('courses'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title'    => 'required|string|max:255',
      'code'     => 'required|string|max:50|unique:courses,code',
      'type'     => 'required|string',
      'duration' => 'required|string',
      'date'     => 'required|string',
      'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,mp4,mov,avi|max:51200',
    ]);

    if ($request->hasFile('file_path')) {
      $file     = $request->file('file_path');
      $filename = time() . '_' . $file->getClientOriginalName();
      $file->storeAs('courses', $filename, 'public');
      $validated['file_path'] = 'courses/' . $filename;
    }

    $course = Course::create($validated);

    return response()->json([
      'message' => 'Course added successfully!',
      'course'  => $course
    ]);
  }

  public function update(Request $request, Course $course)
  {
    $validated = $request->validate([
      'title'    => 'required|string|max:255',
      'code'     => 'required|string|max:100|unique:courses,code,' . $course->id,
      'type'     => 'required|string',
      'duration' => 'required|string',
      'date'     => 'required|string',
      'file_path' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,mp4,mov,avi|max:51200',
    ]);

    // If a new file is uploaded, delete the old one first
    if ($request->hasFile('file_path')) {
      if ($course->file_path && Storage::disk('public')->exists($course->file_path)) {
        Storage::disk('public')->delete($course->file_path);
      }

      $file     = $request->file('file_path');
      $filename = time() . '_' . $file->getClientOriginalName();
      $file->storeAs('courses', $filename, 'public');
      $validated['file_path'] = 'courses/' . $filename;
    }

    $course->update($validated); //

    return response()->json([
      'message' => 'Course updated successfully.',
      'course'  => $course
    ]);
  }
}
