<?php

namespace App\Http\Controllers\learning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class CalendarController extends Controller
{
  public function index()
  {
    return view('content.learning.calendar'); // this view must exist!
  }

  public function getEvents()
  {
    $events = Event::all();

    $formatted = [];

    foreach ($events as $event) {
      $color = match ($event->type) {
        'Seminar'     => '#007bff', // Blue
        'Training'    => '#28a745', // Green
        'Orientation' => '#ffc107', // Yellow
        'Other'       => '#dc3545', // Red
        default       => '#6c757d', // Gray
      };

      $formatted[] = [
        'title' => $event->title,
        'start' => $event->dateFrom,
        'end'   => $event->dateTo ? date('Y-m-d', strtotime($event->dateTo . ' +1 day')) : null,
        'color' => $color,
        'type'  => $event->type,
        'status' => $event->status,
      ];
    }

    return response()->json($formatted);
  }
}
