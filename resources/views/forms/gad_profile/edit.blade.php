@extends('layouts/contentNavbarLayout')

@section('title', 'Leave List')

@section('content')
<div class="container py-4">
  <h3>Edit GAD Profile</h3>
  <hr>
  @include('gad_profile.form',['route'=>route('gad_profile.update',$profile->id),'method'=>'PUT','data'=>$profile])
</div>
@endsection