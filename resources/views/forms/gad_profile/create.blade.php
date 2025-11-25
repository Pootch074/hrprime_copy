@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')
<div class="container py-4">
  <h3>Create GAD Profile</h3>
  <hr>
  @include('forms.gad_profile.form',['route' => route('forms.gad_profile.store'),'method'=>'POST'])
</div>
@endsection