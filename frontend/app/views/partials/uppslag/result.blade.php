<div class="u-display--flex u-flex-direction--column u-flex--gridgap">
    
    @includeIf('notices.' . $action)

    @if($isDeregistered)
        @notice([
            'type' => 'info',
            'message' => (object) [
                'text' => $deregistrationReason
            ],
            'icon' => [
                'name' => 'report',
                'size' => 'md',
                'color' => 'white'
            ]
        ])
        @endnotice
    @else
        @if($readableResult)
            @typography(['element' => 'p', 'classList' => ['u-margin__top--0']])
                {{ $readableResult }}
            @endtypography
        @endif
    @endif

    @table([
        'title'         => "Personuppgifter",
        'headings'      => false,
        'showHeader'    => false,
        'list'          => $basicData
    ])
    @endtable

    @if(!$isDeregistered && $adressData)
        @table([
            'title'         => "Adress",
            'headings'      => false,
            'showHeader'    => false,
            'list'          => $adressData
        ])
        @endtable
    @endif

    @if($searchResultFamilyRelations)
        @table([
            'title'         => "Familjerelationer",
            'headings'      => ['Personnummer', 'Far', 'Mor', 'Vårdnadshavare', 'Barn', 'Make/Maka'],
            'showHeader'    => true,
            'list'          => $searchResultFamilyRelations
        ])
        @endtable
    @endif

    @if(!$isDeregistered && $searchResultPropertyData)
        @table($searchResultPropertyData)
        @endtable
    @endif

    @button([
        'text' => 'Tillbaka till uppslag',
        'href' => '/uppslag',
        'color' => 'default',
        'style' => 'filled',
        'classList' => [
            'u-width--100',
        ]
    ])
    @endbutton
</div>