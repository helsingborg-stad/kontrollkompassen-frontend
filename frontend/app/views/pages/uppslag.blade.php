@extends('layout.containers.page')
@section('article')
<div class="u-display--flex u-flex-direction--column u-flex--gridgap">

    @includeIf('notices.' . $action)
    <div id="error-message" class="hidden"></div>
    @form([
        'method' => 'POST',
        'action' => '/uppslag',
        'classList' => ['u-margin__top--2'],
        'id' => 'check-orgno-formX'
    ])
    <div class="u-display--flex u-flex-direction--column u-flex--gridgap">
        @field([
            'id' => 'check-orgno-field',
            'type' => 'text',
            'name' => 'orgno',
            'label' => 'Organisationsnummer',
            'required' => true,
            'placeholder' => '10 eller 12 siffror',
            'value' => isset($_GET['orgno']) ? $_GET['orgno'] : '',
            'attributeList' => [
                'autofocus' => 'autofocus'
            ]
        ])
        @endfield

        @button([
            'text' => 'Hämta uppgifter',
            'color' => 'primary',
            'type' => 'basic',
            'classList' => [
                'u-width--100'
            ]
        ])
        @endbutton
    </div>
    @endform
</div>
@endsection