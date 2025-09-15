<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class EventsController extends Controller
{
  // List all events
  public function index()
  {
    $events = Event::all();
    return view('content.learning.events', compact('events'));
  }

  // Store new event
  public function store(Request $request)
  {
    try {
      $validated = $request->validate([
        'title'    => 'required|string|max:255',
        'type'     => 'required|string',
        'dateFrom' => 'required|date',
        'dateTo'   => 'required|date|after_or_equal:dateFrom',
        'status'   => 'required|string|in:Active,Inactive,Cancelled',
      ]);

      $event = Event::create([
        'title'      => $validated['title'],
        'type'       => $validated['type'],
        'dateFrom'  => $validated['dateFrom'], // validated from form
        'dateTo'    => $validated['dateTo'],
        'status'     => $validated['status'],
        'addedBy'   => auth()->check() ? auth()->user()->name : $request->input('addedBy', 'System'),
        'dateAdded' => $request->input('dateAdded', now()->toDateString()),
      ]);


      return response()->json(['message' => 'Event saved successfully!', 'event' => $event], 201);
    } catch (\Throwable $e) {
      Log::error('Event store failed', ['error' => $e->getMessage()]);
      return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
    }
  }

  // Update event status
  public function updateStatus(Request $request, $id)
  {
    try {
      $request->validate([
        'status' => 'required|in:Active,Inactive,Cancelled'
      ]);

      $event = Event::findOrFail($id);
      $event->status = $request->status;
      $event->save();

      return response()->json(['message' => 'Status updated successfully.']);
    } catch (\Throwable $e) {
      Log::error('Update status failed', ['error' => $e->getMessage()]);
      return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
    }
  }
}
