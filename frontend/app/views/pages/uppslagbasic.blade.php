<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('[data-js-form]');
        const button = document.querySelector('[data-js-submitter]');
        if (form && button) {
            form.addEventListener('submit', function(event) {
                button.disabled = true;
                button.textContent = 'Kontrollerar...';
            });
        }
    });
</script>

@extends('layout.containers.page')
@section('article')
    <div class="u-display--flex u-flex-direction--column u-flex--gridgap">

        @includeIf('notices.' . $action)

        @form([
        'method' => 'POST',
        'action' => '/uppslag-enkel',
        'classList' => ['u-margin__top--2'],
        'attributeList' => ['data-js-form' => 'true']
        ])
        <div class="u-display--flex u-flex-direction--column u-flex--gridgap">
            @typography(['element' => 'h2'])
                Ange ett organisationsnummer för att kontrollera status.
            @endtypography

            @field([
                'id' => 'check-orgno-field',
                'type' => 'text',
                'name' => 'orgno',
                'label' => 'Organisationsnummer',
                'required' => true,
                'placeholder' => '10 eller 12 siffror',
                'value' => $orgNo ?? '',
                'attributeList' => [
                    'autofocus' => 'autofocus'
                ]
            ])
            @endfield

            @button([
                'text' => 'Hämta uppgifter',
                'color' => 'primary',
                'type' => 'basic',
                'classList' => ['u-width--100'],
                'attributeList' => ['data-js-submitter' => 'true'],
            ])
            @endbutton
        </div>
        @endform
    </div>
@endsection
