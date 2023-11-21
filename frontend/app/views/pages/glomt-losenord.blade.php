@extends('layout.containers.page')
@section('article')
    @typography([
        'element' => 'h1',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        @icon(['icon' => 'help', 'size' => 'inherit'])
        @endicon
        Glömt lösenord
    @endtypography

    @typography(['element' => 'p'])
        För att använda den här tjänsten, loggar du in med ditt vanliga datorkonto (ad-konto).
    @endtypography

    @typography(['element' => 'p'])
        Det är bara användare som har begärt rättigheten till personsök från supportcenter som kan logga in och göra uppslag.
    @endtypography

    @typography(['element' => 'p'])
        Du kan begära åtkomst till tjänsten genom att maila till 
        @link(['href' => 'mailto:supportcenter@helsingborg.se'])
            supportcenter@helsingborg.se 
        @endlink
    @endtypography

    @button([
        'text' => 'Gå tillbaka',
        'color' => 'default',
        'type' => 'basic',
        'classList' => [
            'u-width--100',
            'u-margin__top--4'
        ],
        'href' => '/'
    ])
    @endbutton
@endsection