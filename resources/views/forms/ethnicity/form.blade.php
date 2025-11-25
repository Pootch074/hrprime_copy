@extends('layouts/contentNavbarLayout')

@section('title', 'Ethnicity')

@section('content')
<div class="container py-4">
  <h3>PART IV. ETHNICITY AND FAMILY BACKGROUND</h3>
  <hr>

  <form method="POST" action="{{ route('forms.ethnicity.store') }}">
    @csrf

    {{-- ===================== DESCRIPTION ====================== --}}
    <label class="form-label mt-2"><b>Description (optional)</b></label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $row->description ?? '') }}</textarea>

    {{-- ===================== ETHNICITY ====================== --}}
    <label class="form-label mt-3"><b>Ethnicity</b></label>
    <select name="ethnicity" id="ethnicity" class="form-select" required>
      @php
      $ethnicities = [
      "Tagalog", "Cebuano", "Ilocano", "Hiligaynon (Ilonggo)", "Bicolano", "Waray",
      "Kapampangan", "Pangasinense", "Tausug", "Maguindanao", "Maranao", "Ivatan",
      "Ifugao", "Kankanaey", "Bontoc", "Mangyan", "Aeta / Agta / Negrito", "Lumad",
      "Chavacano", "Chinese-Filipino", "Other"
      ];

      $savedEthnicity = $row->ethnicity ?? '';
      $isInList = in_array($savedEthnicity, $ethnicities);
      @endphp

      <option value="" disabled selected>Select ethnicity</option>
      @foreach($ethnicities as $ethnicity)
      @php
      $selected = (!$isInList && $ethnicity === "Other") || ($savedEthnicity === $ethnicity) ? "selected" : "";
      @endphp
      <option value="{{ $ethnicity }}" {{ $selected }}>{{ $ethnicity }}</option>
      @endforeach
    </select>

    <input type="text"
      name="ethnicity_other"
      id="ethnicity_other"
      class="form-control mt-2"
      placeholder="Please specify ethnicity"
      value="{{ !$isInList ? $savedEthnicity : old('ethnicity_other', $row->ethnicity_other ?? '') }}"
      style="{{ old('ethnicity', $row->ethnicity ?? '') === 'Other' || !$isInList ? '' : 'display:none;' }}">

    <hr>

    {{-- ===================== HOUSEHOLD MEMBERS ====================== --}}
    <h5><b>Household Members</b></h5>
    @php
    $fields = [
    'household_count' => 'Number of persons living in the household (including yourself)',
    'zero_above' => 'Number of children/dependents aged 0-5',
    'six_above' => 'Number of children/dependents aged 6-17',
    'eighteen_above' => 'Number of children/dependents aged 18-45',
    'forty_six_above' => 'Number of children/dependents aged 46-59',
    'sixty_above' => 'Number of elderly dependents aged 60 and above',
    'children_still_studying' => 'Number of children/dependents still studying',
    'special_needs' => 'Number of children/dependents with special needs',
    ];
    $householdOptions = range(0, 20);
    @endphp

    @foreach($fields as $name => $label)
    <label class="form-label mt-3"><b>{{ $label }}</b></label>
    <div class="row">
      <div class="col-md-12">
        <select name="{{ $name }}" class="form-select household-select" data-target="{{ $name }}_other">
          <option value="" disabled selected>Select</option>
          @foreach($householdOptions as $opt)
          <option value="{{ $opt }}" {{ old($name, $row->$name ?? '') == $opt ? 'selected' : '' }}>
            {{ $opt }}
          </option>
          @endforeach
          <option value="Other" {{ old($name, $row->$name ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
      </div>
      <div class="col-md-12">
        <input type="text"
          name="{{ $name }}_other"
          id="{{ $name }}_other"
          class="form-control mt-2"
          placeholder="Please specify"
          value="{{ old($name.'_other', $row->{$name.'_other'} ?? '') }}"
          style="{{ old($name, $row->$name ?? '') == 'Other' ? '' : 'display:none;' }}">
      </div>
    </div>
    @endforeach

    <hr>

    {{-- ===================== LIVING CONDITION ====================== --}}
    <label class="form-label mt-3"><b>Living Condition</b></label>
    @php
    $livingOptions = $livingOptions ?? [
    "Owned",
    "Living with parents",
    "Living with relatives",
    "Rental",
    "Others"
    ];
    @endphp

    <select name="living_condition" class="form-select" id="livingConditionSelect">
      <option value="" disabled selected>Select living condition</option>
      @foreach($livingOptions as $item)
      <option value="{{ $item }}" {{ old('living_condition', $row->living_condition ?? '') == $item ? 'selected' : '' }}>
        {{ $item }}
      </option>
      @endforeach
      <option value="Other" {{ old('living_condition', $row->living_condition ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>

    <input type="text"
      name="living_condition_other"
      id="livingConditionOther"
      class="form-control mt-2"
      placeholder="Please specify"
      value="{{ old('living_condition_other', $row->living_condition_other ?? '') }}"
      style="{{ old('living_condition', $row->living_condition ?? '') == 'Other' ? '' : 'display:none;' }}">

    {{-- ===================== SUBMIT BUTTON ====================== --}}
    <div class="mt-4 text-center">
      <button type="submit" class="btn btn-primary">
        {{ isset($row->id) ? 'Update' : 'Submit' }}
      </button>
    </div>
  </form>
</div>

<script>
  // Ethnicity "Other" toggle
  document.getElementById('ethnicity').addEventListener('change', function() {
    const input = document.getElementById('ethnicityOther');
    if (this.value === 'Other') {
      input.style.display = 'block';
    } else {
      input.style.display = 'none';
      input.value = '';
    }
  });

  // Household "Other" toggle
  document.querySelectorAll('.household-select').forEach(function(select) {
    select.addEventListener('change', function() {
      const target = document.getElementById(this.dataset.target);
      if (this.value === 'Other') {
        target.style.display = 'block';
      } else {
        target.style.display = 'none';
        target.value = '';
      }
    });
  });

  // Living condition "Other" toggle
  document.getElementById('livingConditionSelect').addEventListener('change', function() {
    const input = document.getElementById('livingConditionOther');
    input.style.display = this.value === 'Other' ? 'block' : 'none';
  });
</script>
@endsection
