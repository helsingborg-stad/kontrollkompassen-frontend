@extends('layout.containers.page')
@section('article')
    @typography([
        'element' => 'h1',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        @icon(['icon' => 'search', 'size' => 'inherit'])
        @endicon
        Kontrollkompassen
    @endtypography
    @includeWhen(!isset($checkOrgNoResult), 'partials.uppslag.form')
    @includeWhen(isset($checkOrgNoResult) && $checkOrgNoResult, 'partials.uppslag.result')
@endsection