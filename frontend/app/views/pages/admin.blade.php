@extends('layout.containers.page')
@section('article')
    @typography([
        'element' => 'h2',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        Admin
    @endtypography
    @form([
    'method' => 'POST',
    'action' => '/admin/downloadHistory',
    'classList' => ['u-margin__top--2']
    ])
    @button([
        'text' => 'Ladda ner historikfil (.csv)',
        'color' => 'primary',
        'type' => 'basic',
        'classList' => ['u-width--50'],
    ])
    @endbutton
    @endform
@stop
