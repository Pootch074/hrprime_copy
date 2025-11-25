@extends('layouts/contentNavbarLayout')

@section('title', 'Solo Parent ')

@section('content')
<div class="container py-4">
  <h3>Create Solo Parent </h3>
  <hr>
  @include('forms.solo_parent.form',['route' => route('forms.solo_parent.store'),'method'=>'POST'])
</div>
@endsection
