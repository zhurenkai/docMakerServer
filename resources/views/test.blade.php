@extends('layouts.app')

@section('content')

  <div id="app">
    <passport-clients></passport-clients>
    <hr/>
    <passport-authorized-clients></passport-authorized-clients>
    <hr/>
    <passport-personal-access-tokens></passport-personal-access-tokens>
  </div>

@endsection

