@extends('layouts/contentNavbarLayout')

@section('title', 'Solo Parent Information')

@section('content')
<div class="container py-4">
  <h3>PART VI. SOLO PARENT INFORMATION</h3>
  <p>Note: Simply SKIP if not a Solo Parent</p>
  <hr>

  <form method="POST" action="{{ route('forms.solo_parent.store') }}">
    @csrf

    <select name="circumstance" id="circumstanceSelect" class="form-select">
      <option value="" disabled>Select circumstance</option>
      @foreach($circumstances as $item)
      @php
      // If circumstance_other exists, select "Others"; otherwise match circumstance
      $selected = ($row->circumstance_other && $item === 'Others') || ($row->circumstance == $item) ? 'selected' : '';
      @endphp
      <option value="{{ $item }}" {{ $selected }}>{{ $item }}</option>
      @endforeach
    </select>

    <input type="text" name="circumstance_other" id="circumstanceOther" class="form-control mt-2"
      placeholder="Please specify"
      value="{{ old('circumstance_other', $row->circumstance_other ?? '') }}"
      style="{{ ($row->circumstance_other) ? '' : 'display:none;' }}">


    <div class="mt-4 text-center">
      <button type="submit" class="btn btn-primary">{{ $row ? 'Update' : 'Submit' }}</button>
    </div>
  </form>
</div>

<script>
  document.getElementById('circumstanceSelect').addEventListener('change', function() {
    const otherInput = document.getElementById('circumstanceOther');
    if (this.value === 'Others') {
      otherInput.style.display = 'block';
    } else {
      otherInput.style.display = 'none';
      otherInput.value = '';
    }
  });
</script>
@endsection
