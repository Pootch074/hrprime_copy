@extends('layouts/contentNavbarLayout')

@section('title', 'Travel Order ')

@section('content')
<div class="container py-4">
  <h3>Create Travel Order </h3>
  <hr>
  @include('forms.travel.form',['route' => route('forms.travel.store'),'method'=>'POST'])
</div>
@endsection