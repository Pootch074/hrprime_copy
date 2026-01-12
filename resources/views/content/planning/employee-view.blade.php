@extends('layouts/contentNavbarLayout')

@section('title', 'Employee Profile Viewer')

@section('content')

<div class="container py-4">

    {{-- Page Header --}}
  <div class="card shadow-sm mb-4">
      <div class="card-body d-flex align-items-center">
          <h4 class="text-primary me-3 mb-0">Employee Profile</h4>
          <hr class="flex-grow-1 mb-0">
      </div>
  </div>

    {{-- Profile Header --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <img 
                src="{{ $employee->profile_image ? asset('storage/' . $employee->profile_image) : asset('default-avatar.png') }}"
                alt="Profile Photo"
                class="rounded-circle me-4"
                style="width: 130px; height: 130px; object-fit: cover; border: 2px solid #000000ff;"
            >
            <div>
                <h4 class="fw-bold mb-1">
                    {{ $employee->first_name }} {{ $employee->middle_name ? $employee->middle_name[0].'.' : '' }} {{ $employee->last_name }} {{ $employee->extension_name }}
                </h4>
                <p class="text-muted mb-0">{{ $employee->position_title ?? 'N/A' }}</p>
                <p class="text-muted mb-0">{{ $employee->division->name ?? 'N/A' }} | {{ $employee->section->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- Left Sidebar Menu --}}
        <div class="col-lg-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    @php
                        $tabs = [
                            ["slug" => "basic", "name" => "Basic Information"],
                            ["slug" => "family-background", "name" => "Family Background"],
                            ["slug" => "education", "name" => "Educational Background"],
                            ["slug" => "cs-eligibility", "name" => "Civil Service Eligibility"],
                            ["slug" => "work-experience", "name" => "Work Experience"],
                            ["slug" => "voluntary-work", "name" => "Voluntary Work"],
                            ["slug" => "landd", "name" => "Learning and Development"],
                            ["slug" => "references", "name" => "References"],
                            ["slug" => "id", "name" => "Government ID"],
                            ["slug" => "non-academic", "name" => "Non-academic"],
                            ["slug" => "organization", "name" => "Organization"],
                            ["slug" => "skills", "name" => "Skills"],
                            ["slug" => "other-information", "name" => "Other Information"],
                        ];
                    @endphp

                    <div class="nav flex-column nav-pills" id="profileTab" role="tablist">
                        @foreach($tabs as $index => $tab)
                            <button class="nav-link text-start {{ $index == 0 ? 'active' : '' }}"
                                    id="{{ $tab['slug'] }}-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#{{ $tab['slug'] }}"
                                    type="button"
                                    role="tab">
                                {{ $tab['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Tab Content --}}
        <div class="col-lg-9 ">
          <div class="card shadow-sm">
            <div class="tab-content" id="profileTabContent">
                {{-- Basic Information --}}
                <div class="tab-pane fade show active" id="basic" role="tabpanel">
                    <div class="row">
                          <h5 class="fw-bold mb-3 ">Basic Inforamtion</h5>
                          <div class="row">
                              <div class="col-md-6">
                                  <p><b>Full Name:</b> {{ $employee->first_name }} {{ $employee->middle_name ? $employee->middle_name[0].'.' : '' }} {{ $employee->last_name }} {{ $employee->extension_name }}</p>
                                  <p><b>Gender:</b> {{ $employee->gender ?? 'N/A' }}</p>
                                  <p><b>Birthday:</b> {{ $employee->birthday ?? 'N/A' }}</p>
                                  <p><b>Civil Status:</b> {{ $employee->civil_status ?? 'N/A' }}</p>
                                  <p><b>Blood Type:</b> {{ $employee->blood_type ?? 'N/A' }}</p>
                                  <p><b>Height/Weight:</b> {{ $employee->height ?? 'N/A' }} / {{ $employee->weight ?? 'N/A' }}</p>
                                  <p><b>Citizenship:</b> {{ $employee->citizenship ?? 'N/A' }}</p>
                              </div>
                              <div class="col-md-6">
                                  <p><b>Email:</b> {{ $employee->email ?? 'N/A' }}</p>
                                  <p><b>Username:</b> {{ $employee->username ?? 'N/A' }}</p>
                                  <p><b>Employee ID:</b> {{ $employee->employee_id ?? 'N/A' }}</p>
                                  <p><b>Mobile:</b> {{ $employee->mobile_no ?? 'N/A' }}</p>
                                  <p><b>Telephone:</b> {{ $employee->tel_no ?? 'N/A' }}</p>
                                  <p><b>Place of Birth:</b> {{ $employee->place_of_birth ?? 'N/A' }}</p>
                                  <p><b>Residential Address:</b> {{ $employee->res_house_no }} {{ $employee->res_street }} {{ $employee->res_barangay->name ?? '' }}, {{ $employee->res_city->name ?? '' }}, {{ $employee->res_province->name ?? '' }}</p>
                                  <p><b>Permanent Address:</b> {{ $employee->perm_house_no }} {{ $employee->perm_street }} {{ $employee->perm_barangay->name ?? '' }}, {{ $employee->perm_city->name ?? '' }}, {{ $employee->perm_province->name ?? '' }}</p>
                              </div>
                          </div>
                      </div>

                    </div>


                {{-- Other Tabs --}}
                @foreach($tabs as $tab)
                    @if($tab['slug'] != 'basic')
                        <div class="tab-pane fade" id="{{ $tab['slug'] }}" role="tabpanel">
                            <div class="card shadow-sm mb-3 p-3">
                                <h5 class="fw-bold mb-2">{{ $tab['name'] }}</h5>
                                <div id="{{ $tab['slug'] }}Content">
                                    {{-- AJAX content can load here --}}
                                    Content will load here.
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>

    </div>
</div>

{{-- Styles --}}
<style>
.nav-pills .nav-link {
    border-radius: .5rem;
    margin-bottom: .3rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.95rem;
}
.nav-pills .nav-link.active {
    background-color: #1d4bb2;
    color: #fff;
}
</style>

@endsection
