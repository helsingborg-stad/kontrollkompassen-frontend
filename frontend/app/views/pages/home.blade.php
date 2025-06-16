@extends('layout.containers.page')
@section('article')
    @typography([
        'element' => 'h2',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        Välkommen!
    @endtypography

    @link(['href' => '/login'])
        Till inloggningssidan
    @endbutton
@stop
