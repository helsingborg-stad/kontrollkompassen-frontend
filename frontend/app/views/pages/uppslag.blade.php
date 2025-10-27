@extends('layout.containers.page')
@section('article')
<div class="u-display--flex u-flex-direction--column u-flex--gridgap">

    @includeIf('notices.' . $action)

    @form([
        'method' => 'POST',
        'action' => '/uppslag',
        'classList' => ['u-margin__top--2']
    ])
    <div class="u-display--flex u-flex-direction--column u-flex--gridgap">
        @typography(['element' => 'h2'])
            Välj tjänster och ange organisationsnummer. Vilka tjänster du väljer påverkar vilka uppgifter som hämtas.
        @endtypography

        @foreach ($services as $service)
            @option([
                'id' => "check-service-{$service['id']}-option",
                'label' => $service['label'],
                'type' => 'checkbox',
                'value' => $service['id'],
                'name' => "selectedservices[{$service['id']}]",
                'checked' => $service['checked'] ?? false
            ])
            @endoption
        @endforeach

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
            'classList' => [
                'u-width--100'
            ]
        ])
        @endbutton
    </div>
    @endform
</div>
@endsection