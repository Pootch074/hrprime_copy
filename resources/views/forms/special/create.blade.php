@extends('layouts/contentNavbarLayout')

@section('title', 'Special Order ')

@section('content')
<div class="container py-4">
  <h3>Create Special Order </h3>
  <hr>
  @include('forms.special.form',['route' => route('forms.special.store'),'method'=>'POST'])
</div>
@endsection