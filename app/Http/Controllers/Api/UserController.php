<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use App\Models\ImportLog;
use App\Models\Course; // ✅ Import Course model
use Illuminate\Support\Facades\Hash;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; // <-- Add this

class UserController extends Controller
{
  public function index(Request $request)
  {
    $query = User::with([
      'division:id,abbreviation',
      'section:id,abbreviation',
      'employmentStatus:id,abbreviation'
    ]);

    if ($request->has('search')) {
      $query->where(function ($q) use ($request) {
        $q->where('first_name', 'like', "%{$request->search}%")
          ->orWhere('last_name', 'like', "%{$request->search}%")
          ->orWhere('employee_id', 'like', "%{$request->search}%");
      });
    }

    return response()->json($query->get());
  }

  public function store(Request $request)
  {
    $request->validate([
      'first_name'         => 'required',
      'last_name'          => 'required',
      'employment_status'  => 'required|exists:employment_statuses,id',
      'division'           => 'required|exists:divisions,id',
      'section'            => 'required|exists:sections,id',
      'email'              => 'required|email|unique:users,email',
      'password'           => 'required|confirmed|min:6',
      'profile_image'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Auto-generate employee ID
    $latestUser = User::latest('id')->first();
    $latestId = $latestUser ? $latestUser->id + 1 : 1;
    $employeeId = '11-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

    $middleInitial = substr($request->middle_name, 0, 1);
    $empIdLast4 = substr($employeeId, -4);
    $username = strtolower(substr($request->first_name, 0, 1) . $middleInitial . $request->last_name . $empIdLast4);

    // Save profile image if present
    $imagePath = null;
    if ($request->hasFile('profile_image')) {
      $file = $request->file('profile_image');
      $filename = time() . '_' . $file->getClientOriginalName();
      $file->move(public_path('uploads/profile_images'), $filename);
      $imagePath = 'uploads/profile_images/' . $filename;
    }

    User::create([
      'employee_id'          => $employeeId,
      'first_name'           => $request->first_name,
      'middle_name'          => $request->middle_name,
      'last_name'            => $request->last_name,
      'extension_name'       => $request->extension_name,
      'employment_status_id' => $request->employment_status,
      'division_id'          => $request->division,
      'section_id'           => $request->section,
      'username'             => $username,
      'email'                => $request->email,
      'password'             => Hash::make($request->password),
      'profile_image'        => $imagePath,
    ]);

    return redirect()->route('employee.registration-form')->with('success', 'Employee registered successfully!');
  }

  public function show($id)
  {
    $user = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return response()->json($user);
  }

  public function showEmployeeView($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.view-employee', compact('employee'));
  }

  public function update(Request $request, $id)
  {
    $user = User::findOrFail($id);

    $data = $request->validate([
      'employee_id'           => 'required|unique:users,employee_id,' . $id,
      'first_name'            => 'required',
      'middle_name'           => 'nullable',
      'last_name'             => 'required',
      'extension_name'        => 'nullable',
      'username'              => 'required|unique:users,username,' . $id,
      'password'              => 'nullable|min:6',
      'employment_status_id'  => 'nullable|exists:employment_statuses,id',
      'division_id'           => 'nullable|exists:divisions,id',
      'section_id'            => 'nullable|exists:sections,id',
    ]);

    if (!empty($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    } else {
      unset($data['password']);
    }

    $user->update($data);

    return redirect()->route('employee.view', $id)->with('success', 'Employee updated successfully.');
  }

  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully.']);
  }

  public function bladeIndex()
  {
    $employees = User::with(['division', 'section', 'employmentStatus'])->get();
    return view('content.planning.list-of-employee', compact('employees'));
  }

  public function apiIndex()
  {
    $employees = User::with([
      'division:id,abbreviation',
      'section:id,abbreviation',
      'employmentStatus:id,abbreviation'
    ])->get();

    return response()->json($employees);
  }

  public function showEmpProfile()
  {
    $user = auth()->user();
    if (!$user) {
      return redirect()->route('login')->with('error', 'Please login first.');
    }

    $employee = $user->load(['division', 'section', 'employmentStatus']);
    return view('content.planning.profile.basic-information', compact('employee'));
  }

  public function editEmployeeView($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.edit-employee', compact('employee'));
  }

  public function edit($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.edit-employee', compact('employee'));
  }

  public function getSections(Request $request)
  {
    $divisionId = $request->division_id;

    if (!$divisionId) {
      return response()->json([]);
    }

    $sections = Section::where('division_id', $divisionId)->get(['id', 'name']);
    return response()->json($sections);
  }

  public function create()
  {
    $employmentStatuses = EmploymentStatus::all();
    $divisions = Division::all();

    do {
      $latestUser = User::latest('id')->first();
      $latestId = $latestUser ? $latestUser->id + 1 : 1;
      $generatedEmployeeId = '11-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
    } while (User::where('employee_id', $generatedEmployeeId)->exists());

    return view('content.planning.registration-form', compact(
      'employmentStatuses',
      'divisions',
      'generatedEmployeeId'
    ));
  }

  public function showImportForm()
  {
    return view('content.planning.import-form');
  }

  public function importEmployees(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    $file = $request->file('file');
    $filename = $file->getClientOriginalName();

    // Check if file already imported
    if (ImportLog::where('filename', $filename)->exists()) {
      return redirect()->back()->with('error', 'This file has already been imported.');
    }

    try {
      Excel::import(new EmployeesImport(), $file);

      ImportLog::create([
        'filename'    => $filename,
        'status'      => 'Imported',
        'imported_at' => now(),
      ]);

      return redirect()->back()->with('success', 'Employees imported successfully!');
    } catch (\Exception $e) {
      ImportLog::create([
        'filename'    => $filename,
        'status'      => 'Failed: ' . $e->getMessage(),
        'imported_at' => now(),
      ]);

      return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
    }
  }

  // ======================
  // Courses Section
  // ======================

  public function showCoursesEnrolled()
  {
    $user = auth()->user();
    if (!$user) {
      return redirect()->route('login')->with('error', 'Please login first.');
    }

    $courses = $user->courses; // relationship in User model

    return view('content.planning.profile.courses_enrolled', compact('courses', 'user'));
  }


  public function showCourses()
  {
    $user = auth()->user();
    if (!$user) {
      return redirect()->route('login')->with('error', 'Please login first.');
    }

    // Get all available courses
    $courses = Course::all();

    // IDs of courses the user is already enrolled in
    $enrolledIds = $user->courses->pluck('id')->toArray();

    return view('content.planning.profile.courses_list', compact('courses', 'user', 'enrolledIds'));
  }

  public function enrollCourse($id)
  {
    $user = auth()->user();
    if (!$user) {
      return redirect()->route('login')->with('error', 'Please login first.');
    }

    $course = Course::findOrFail($id);

    // Check if already enrolled
    $exists = $user->courses()->where('course_id', $id)->exists();
    if (!$exists) {
      // Attach with initial status
      $user->courses()->attach($course->id, [
        'status' => 'in_progress', // better than 'Ongoing'
        'score' => null,
        'time_spent' => null,
        'completed_at' => null
      ]);
    }

    return redirect()->route('employee.courses')
      ->with('success', 'You have enrolled in ' . $course->title);
  }

  public function takeCourse($id)
  {
    $user = auth()->user();
    $course = Course::findOrFail($id);

    // Record start time if not yet started
    $enrollment = CourseEnrollment::firstOrCreate(
      ['user_id' => $user->id, 'course_id' => $course->id],
      ['started_at' => now()]
    );

    $questions = $course->questions()->inRandomOrder()->get(); // optional for quiz later

    return view('content.planning.course.take_course', compact('course', 'user', 'enrollment', 'questions'));
  }

  public function submitAssessment(Request $request, $id)
  {
    $user = auth()->user();
    $course = Course::findOrFail($id);

    $enrollment = CourseEnrollment::where('user_id', $user->id)
      ->where('course_id', $course->id)
      ->firstOrFail();

    $answers = $request->input('answers', []);
    $questions = $course->questions;

    $score = 0;
    foreach ($questions as $q) {
      if (isset($answers[$q->id]) && $answers[$q->id] == $q->correct_answer) {
        $score++;
      }
    }

    $enrollment->update([
      'completed_at' => now(),
      'score' => $score,
      'time_spent' => now()->diffInSeconds($enrollment->started_at)
    ]);

    return redirect()->route('employee.courses_enrolled')->with('success', "Course completed! Score: $score / " . $questions->count());
  }

  // View the course
  public function viewCourse($id)
  {
    $user = auth()->user();
    if (!$user) {
      return redirect()->route('login')->with('error', 'Please login first.');
    }

    $course = Course::findOrFail($id);

    // Example questions (replace with DB table if needed)
    $questions = collect([
      (object)[
        'id' => 1,
        'question' => 'What is Laravel?',
        'options' => ['Framework', 'CMS', 'Library', 'Plugin'],
        'answer' => 'Framework'
      ],
      (object)[
        'id' => 2,
        'question' => 'Blade is a ____ in Laravel.',
        'options' => ['Template engine', 'Database', 'Cache', 'Middleware'],
        'answer' => 'Template engine'
      ],
      (object)[
        'id' => 3,
        'question' => 'Eloquent is used for ____ in Laravel.',
        'options' => ['Routing', 'ORM', 'Caching', 'Validation'],
        'answer' => 'ORM'
      ],
      // Add more questions up to 10
    ]);

    return view('content.planning.profile.course_viewer', compact('course', 'questions'));
  }

  // Complete course and update enrollment
  public function completeCourse(Request $request, $courseId)
  {
    $request->validate([
      'time_spent' => 'required|integer',
      'score' => 'required|integer',
    ]);

    $userId = auth()->id();

    // Update enrollment in course_enrollments
    DB::table('course_enrollments')
      ->where('user_id', $userId)
      ->where('course_id', $courseId)
      ->update([
        'status' => 'done',
        'score' => $request->score,
        'time_spent' => $request->time_spent,
        'completed_at' => now(),
      ]);

    return response()->json([
      'success' => true,
      'score' => $request->score,
    ]);
  }
}
