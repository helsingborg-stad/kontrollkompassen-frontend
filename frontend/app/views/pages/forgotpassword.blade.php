@extends('layout.containers.page')
@section('article')
    @typography([
        'element' => 'h1',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        @icon(['icon' => 'help', 'size' => 'inherit'])
        @endicon
        Glömt lösenord?
    @endtypography

    @typography(['element' => 'p'])
        Logga in med ditt datorkonto (AD-konto) för att göra uppslag i kontrollkompassen
    @endtypography

    @typography(['element' => 'p'])
        Beställ åtkomst till tjänsten genom att skapa ärende via
        @link(['href' => 'https://itportalen.helsingborg.se/', 'target' => '_blank'])
            it-portalen
        @endlink<br />
        Be om att bli inlagd i AD-gruppen: <b>HBGADMR-SLFKontrollkompassen-Avancerad</b>
    @endtypography

    @button([
        'text' => 'Tillbaka till inloggningssidan',
        'color' => 'default',
        'type' => 'basic',
        'classList' => [
            'u-width--100',
            'u-margin__top--4'
        ],
        'href' => '/login'
    ])
    @endbutton
@endsection
