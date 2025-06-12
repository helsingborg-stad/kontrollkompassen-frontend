@extends('layout.containers.page')
@section('article')
@includeWhen(!isset($file), 'partials.uppslag.form')
@includeWhen(isset($file), 'partials.uppslag.result')
@endsection