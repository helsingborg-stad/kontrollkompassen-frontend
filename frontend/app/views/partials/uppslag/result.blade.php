<div class="u-display--flex u-flex-direction--column u-flex--gridgap">
    
    @includeIf('notices.' . $action)

    @if($checkOrgNoResult)
        @typography(['element' => 'p', 'classList' => ['u-margin__top--0']])
            Organisationsnummer: {{ $checkOrgNoResult['checkFor'] }}
        @endtypography
    @endif

    @button([
        'text' => 'Tillbaka till formuläret',
        'href' => '/uppslag',
        'color' => 'default',
        'style' => 'filled',
        'classList' => [
            'u-width--100',
        ]
    ])
    @endbutton
</div>