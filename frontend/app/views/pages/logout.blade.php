@extends('layout.containers.page')
@section('article')
    @typography([
        'element' => 'h2',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        Du är nu utloggad
    @endtypography

    @link(['href' => '/login'])
        Logga in igen
    @endbutton
@stop
