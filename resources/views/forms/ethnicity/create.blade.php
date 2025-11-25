@extends('layouts/contentNavbarLayout')

@section('title', 'Ethnicity ')

@section('content')
<div class="container py-4">
  <h3>Create Ethnicity </h3>
  <hr>
  @include('forms.ethnicity.form',['route' => route('forms.ethnicity.store'),'method'=>'POST'])
</div>
@endsection
