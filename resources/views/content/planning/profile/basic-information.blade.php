@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Information')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card shadow-sm">
    <div class="card-body">
        <form id="employeeForm" method="POST" action="{{ route('employee.update', $employee->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Left Column --}}
                <div class="col-md-9 border-end">
                    <h5 class="mb-4 fw-bold">Basic Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ $employee->first_name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control mt-1" value="{{ $employee->middle_name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" name="last_name" class="form-control mt-1" value="{{ $employee->last_name }}">
                        </div>
                        <div class="col-md-4">
                        <label>Extension Name</label>
                        <select class="form-select" name="extension_name">
                            <option value="">--Extension name --</option>
                            @foreach(['JR','SR','II','III','IV'] as $ext)
                            <option value="{{ $ext }}" {{ old('extension_name') == $ext ? 'selected' : '' }}>{{ $ext }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" value="{{ $employee->employee_id }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ $employee->username }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Birthday</label>
                            <input type="date" name="birthday" class="form-control" value="{{ $employee->birthday }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Place of Birth</label>
                            <input type="text" name="place_of_birth" class="form-control" value="{{ $employee->place_of_birth }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sex</label>
                            <select name="sex" class="form-select">
                                <option value="Male" {{ $employee->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $employee->sex == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Civil Status</label>
                            <select name="civil_status" class="form-select">
                                <option value="" {{ empty($employee->civil_status) ? 'selected' : '' }}>-- Select Civil Status --</option>
                                <option value="Single" {{ $employee->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ $employee->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ $employee->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Separated" {{ $employee->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Height(M)</label>
                            <input type="number" step="0.01" name="height" class="form-control" value="{{ $employee->height }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Weight(KG)</label>
                            <input type="number" step="0.1" name="weight" class="form-control" value="{{ $employee->weight }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Blood Type</label>
                            <select name="blood_type" class="form-select">
                                <option value="" {{ empty($employee->blood_type) ? 'selected' : '' }}>-- Select Blood Type --</option>
                                <option value="A+" {{ $employee->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ $employee->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ $employee->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ $employee->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ $employee->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ $employee->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ $employee->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ $employee->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tel No.</label>
                            <input type="text" name="tel_no" class="form-control" value="{{ $employee->tel_no }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Mobile Number</label>
                            <input type="text" name="mobile_no" class="form-control" value="{{ $employee->mobile_no }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="form-label fw-bold fs-6">Permanent Address</h6>
                            <label class="form-label fw-bold">Region</label>
                            <input type="text" name="perm_region" class="form-control mb-4"  value="{{ $employee->perm_region }}">
                            <label class="form-label fw-bold">Province</label>
                            <input type="text" name="perm_province" class="form-control mb-4" value="{{ $employee->perm_province }}">
                            <label class="form-label fw-bold">City</label>
                            <input type="text" name="perm_city" class="form-control mb-4"  value="{{ $employee->perm_city }}">
                            <label class="form-label fw-bold">Barangay</label>
                            <input type="text" name="perm_barangay" class="form-control mb-4" value="{{ $employee->perm_barangay }}">
                            <label class="form-label fw-bold">Street</label>
                            <input type="text" name="perm_street" class="form-control mb-4"  value="{{ $employee->perm_street }}">
                            <label class="form-label fw-bold">House No.</label>
                            <input type="text" name="perm_house_no" class="form-control mb-4"  value="{{ $employee->perm_house_no }}">
                            <label class="form-label fw-bold">ZIP</label>
                            <input type="text" name="perm_zip" class="form-control"  value="{{ $employee->perm_zip }}">
                        </div>

                        <div class="col-md-6">
                            <h6 class="form-label fw-bold fs-6">Residence Address</h6>
                            <label class="form-label fw-bold">Region</label>
                            <input type="text" name="res_region" class="form-control mb-4"  value="{{ $employee->res_region }}">
                            <label class="form-label fw-bold">Province</label>
                            <input type="text" name="res_province" class="form-control mb-4"  value="{{ $employee->res_province }}">
                            <label class="form-label fw-bold">City</label>
                            <input type="text" name="res_city" class="form-control mb-4"  value="{{ $employee->res_city }}">
                            <label class="form-label fw-bold">Barangay</label>
                            <input type="text" name="res_barangay" class="form-control mb-4" value="{{ $employee->res_barangay }}">
                            <label class="form-label fw-bold">Street</label>
                            <input type="text" name="res_street" class="form-control mb-4"  value="{{ $employee->res_street }}">
                            <label class="form-label fw-bold">House No.</label>
                            <input type="text" name="res_house_no" class="form-control mb-4" value="{{ $employee->res_house_no }}">
                            <label class="form-label fw-bold">ZIP</label>
                            <input type="text" name="res_zip" class="form-control"  value="{{ $employee->res_zip }}">
                        </div>
                    </div>
                </div>


                {{-- Right Column: Profile Picture --}}
                <div class="col-md-3 text-center">
                    @if($employee->profile_image)
                        <div style="width: 200px; height: 200px; border: 2px solid #000000ff; border-radius: 50%; overflow: hidden;  margin: 20% auto 0 auto;">
                            <img src="{{ asset($employee->profile_image) }}"
                                alt="Profile Image"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @else
                        <div style="width: 200px; height: 200px; border: 4px solid #6c757d; border-radius: 50%; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('default-user.png') }}"
                                alt="No Photo"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endif

                    {{-- Upload Button --}}
                    <div class="mt-3">
                        <label for="profile_image" class="btn btn-outline-primary btn-sm">Change Photo</label>
                        <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">
                    </div>

                    <div class="fw-bold mt-2">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                    <small class="text-muted">Employee</small>
                </div>

                
            </div>

            

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
