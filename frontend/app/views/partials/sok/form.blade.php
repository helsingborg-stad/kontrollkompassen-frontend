<div class="u-display--flex u-flex-direction--column u-flex--gridgap">

    @includeIf('notices.' . $action)

    @form([
    'method' => 'POST',
    'action' => '/sok/?action=sok',
    'classList' => ['u-margin__top--2']
    ])
    <div class="u-display--flex u-flex-direction--column u-flex--gridgap">
        @field([
        'id' => 'orgno-search-field',
        'type' => 'text',
        'name' => 'orgno',
        'label' => 'Bolagsnummer, 10 eller 12 siffror',
        'required' => true,
        'placeholder' => 'Skriv in bolagsnummer här, 10 eller 12 siffror',
        'value' => isset($_GET['orgno']) ? $_GET['orgno'] : '',
        'attributeList' => [
        'maxlength' => '12',
        'minlength' => '10',
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