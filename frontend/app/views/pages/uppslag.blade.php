@extends('layout.containers.page')
@section('article')
@includeWhen(!isset($link), 'partials.uppslag.form')
@includeWhen(isset($link), 'partials.uppslag.result')
@endsection