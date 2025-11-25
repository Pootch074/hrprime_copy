@extends('layouts/contentNavbarLayout')

@section('title', 'Medical Health ')

@section('content')
<div class="container py-4">
  <h3>Create Medical Health </h3>
  <hr>
  @include('forms.medical.form',['route' => route('forms.medical.store'),'method'=>'POST'])
</div>
@endsection
