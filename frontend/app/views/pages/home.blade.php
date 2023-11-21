@extends('layout.containers.page')
@section('article')

    @typography([
        'element' => 'h1',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        @icon(['icon' => 'lock_person', 'size' => 'inherit'])
        @endicon
        Logga in
    @endtypography

    @typography([
        'element' => 'p',
        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
    ])
        Vänligen logga in nedan med ditt datorkonto, för att göra uppslag mot Navet personuppgiftsdatabas.
    @endtypography

    @includeIf('notices.' . $action)

    @form([
        'method' => 'POST',
        'action' => '/?action=login',
        'classList' => ['u-margin__top--2']
    ])
        <div class="u-display--flex u-flex-direction--column u-flex--gridgap">

                @field([
                    'type' => 'text',
                    'name' => 'username',
                    'label' => "Användarnamn",
                    'required' => true,
                    'autocomplete' => "username",
                    'placeholder' => "T.ex: aaaa0000",
                    'value' => isset($_GET['username']) ? $_GET['username'] : ''
                ])
                @endfield


                @field([
                    'label' => 'Password',
                    'type' => 'password',
                    'name' => 'password',
                    'required' => true,
                    'autocomplete' => "new-password",
                    'invalidMessage' => 'Du måste ange ett lösenord.',
                    'label' => "Lösenord"
                ])
                @endfield

                @button([
                    'text' => 'Logga in',
                    'color' => 'primary',
                    'type' => 'basic',
                    'classList' => [
                        'u-width--100',
                        'u-margin__top--2'
                    ]
                ])
                @endbutton

                @button([
                    'text' => 'Glömt lösenord?',
                    'href' => '/glomt-losenord',
                    'color' => 'default',
                    'style' => 'basic',
                    'classList' => [
                        'u-width--100',
                        'u-margin--0'
                    ]
                ])
                @endbutton

        </div>
        
    @endform

@stop