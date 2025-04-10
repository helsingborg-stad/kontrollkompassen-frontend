@extends('layout.containers.page')
@section('article')
    @includeWhen(!isset($checkOrgNoResult), 'partials.uppslag.form')
    @includeWhen(isset($checkOrgNoResult) && $checkOrgNoResult, 'partials.uppslag.result')
@endsection