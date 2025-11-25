@extends('layouts/contentNavbarLayout')

@section('title', 'Medical Information')

@section('content')
<div class="container py-4">
  <h3>PART V. MEDICAL/HEALTH INFORMATION</h3>
  <hr>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('forms.medical.store') }}">
    @csrf

    {{-- Description --}}
    {{-- Blood Type --}}
    <label class="form-label mt-3"><b>Blood Type</b></label>
    <select name="blood_type" class="form-select">
      <option value="" disabled selected>Select blood type</option>
      @foreach($bloodTypes as $type)
      <option value="{{ $type }}" {{ old('blood_type', $row->blood_type ?? '') == $type ? 'selected' : '' }}>
        {{ $type }}
      </option>
      @endforeach
    </select>

    {{-- Qualified to donate blood --}}
    <label class="form-label mt-3"><b>Are you qualified to donate blood?</b></label>
    <select name="qualified_blood_donation" class="form-select">
      <option value="" disabled selected>Select</option>
      @foreach($yesNoUnknown as $option)
      <option value="{{ $option }}" {{ old('qualified_blood_donation', $row->qualified_blood_donation ?? '') == $option ? 'selected' : '' }}>
        {{ $option }}
      </option>
      @endforeach
    </select>

    {{-- Blood donation willingness --}}
    <label class="form-label mt-3"><b>Are you willing to donate blood?</b></label>
    <select name="blood_donation" class="form-select">
      <option value="" disabled selected>Select</option>
      @foreach($yesNo as $option)
      <option value="{{ $option }}" {{ old('blood_donation', $row->blood_donation ?? '') == $option ? 'selected' : '' }}>
        {{ $option }}
      </option>
      @endforeach
    </select>
    @php
    $questions = [
    'asthma' => 'Do you currently have or manage asthma?',
    'autoimmune' => 'Do you currently have or manage an autoimmune disease?',
    'cancer' => 'Do you currently have or manage cancer?',
    'diabetes_mellitus' => 'Do you currently have or manage diabetes mellitus?',
    'heart_disease' => 'Do you currently have or manage heart disease?',
    'hiv_aids' => 'Do you currently have or manage HIV/AIDS?',
    'hypertension' => 'Do you currently have or manage hypertension?',
    'kidney_disease' => 'Do you currently have or manage kidney disease?',
    'liver_disease' => 'Do you currently have or manage liver disease?',
    'mental_health' => 'Do you currently have or manage a mental health condition?',
    'seizures' => 'Do you currently have or manage seizures?',
    'health_condition' => 'Do you currently have or manage other health conditions not stated?',
    'maintenance_med' => 'Do you have maintenance medications?'
    ];

    // Only these require a "specific" input if yes
    $requiresSpecific = ['autoimmune', 'cancer', 'mental_health', 'health_condition'];
    @endphp

    @foreach($questions as $field => $label)
    <label class="form-label mt-3"><b>{{ $label }}</b></label>
    <select name="{{ $field }}" class="form-select question-select" data-target="{{ $field }}_other">
      <option value="" disabled selected>Select</option>
      @foreach($yesNo as $option)
      <option value="{{ $option }}" {{ old($field, $row->$field ?? '') == $option ? 'selected' : '' }}>
        {{ $option }}
      </option>
      @endforeach
    </select>

    @if(in_array($field, $requiresSpecific))
    <input type="text"
      name="{{ $field }}_other"
      id="{{ $field }}_other"
      class="form-control mt-1"
      placeholder="If Yes, please specify"
      value="{{ old($field.'_other', $row->{$field.'_other'} ?? '') }}"
      style="{{ old($field, $row->$field ?? '') == 'Yes' ? '' : 'display:none;' }}"
      {{ old($field, $row->$field ?? '') == 'Yes' ? 'required' : '' }}>
    @endif
    @endforeach


    {{-- Disability --}}
    <label class="form-label mt-3"><b>If a PWD, please indicate type of Disability</b></label>
    <select name="disability_type" class="form-select">
      <option value="" disabled selected>Select</option>
      @foreach($disabilityTypes as $type)
      <option value="{{ $type }}" {{ old('disability_type', $row->disability_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
      @endforeach
    </select>

    <label class="form-label mt-3"><b>Cause of Disability</b></label>
    <select name="disability_cause" class="form-select">
      <option value="" disabled selected>Select</option>
      @foreach($disabilityCauses as $cause)
      <option value="{{ $cause }}" {{ old('disability_cause', $row->disability_cause ?? '') == $cause ? 'selected' : '' }}>{{ $cause }}</option>
      @endforeach
    </select>

    {{-- Submit --}}
    <div class="mt-4 text-center">
      <button type="submit" class="btn btn-primary">
        {{ $row ? 'Update' : 'Submit' }}
      </button>
    </div>
  </form>
</div>

{{-- JS for conditional "Other" fields --}}
<script>
  document.querySelectorAll('.question-select').forEach(function(select) {
    select.addEventListener('change', function() {
      const target = document.getElementById(this.dataset.target);
      if (this.value === 'Yes') {
        target.style.display = 'block';
      } else {
        target.style.display = 'none';
        target.value = '';
      }
    });
  });
</script>
@endsection
