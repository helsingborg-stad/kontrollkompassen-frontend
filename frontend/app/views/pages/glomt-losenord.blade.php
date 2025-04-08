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
        För att använda den här tjänsten, loggar du in med ditt vanliga datorkonto (AD-konto).
    @endtypography

    @typography(['element' => 'p'])
        Det är bara användare som har begärt rättigheten från supportcenter som kan logga in och göra uppslag.
    @endtypography

    @typography(['element' => 'p'])
        Du kan begära åtkomst till tjänsten genom att skapa ett ärende i 
        @link(['href' => 'https://itportalen.helsingborg.se/'])
            it-portalen
        @endlink.
        Beställ tillgång till AD-gruppen "HBGADMA-XXX uppslag" för hela enheter, eller "HBGADMR-SLFKontrollkompassen" till enskilda användare.
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
