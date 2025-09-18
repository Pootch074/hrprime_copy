<h5>Position: {{ $item->position->name ?? '-' }}</h5>
<p>
  <strong>Item Number:</strong> {{ $item->item_number }} <br>
  <strong>Salary Grade:</strong> {{ $item->salaryGrade->name ?? '-' }} <br>
  <strong>Employment Status:</strong> {{ $item->employmentStatus->name ?? '-' }} <br>
  <strong>Stature:</strong> {{ ucfirst($item->stature) }}
</p>

<hr>

<h5>Applicants</h5>
<ul class="list-group mb-3">
  @forelse($applicants as $applicant)
  <li class="list-group-item">{{ $applicant->name }} ({{ $applicant->email }})</li>
  @empty
  <li class="list-group-item text-muted">No applicants yet.</li>
  @endforelse
</ul>

<h5>Add Applicant</h5>
<form method="POST" action="{{ route('unfilled-positions.applicants.store', $item->id) }}">
  @csrf
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-success">Save Applicant</button>
</form>