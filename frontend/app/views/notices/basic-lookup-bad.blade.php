@notice([
    'type' => 'warning',
    'message' => [
        'title' => 'Uh oh',
        'text' =>
            'Organisationen med org. nr. ' .
            $orgNo .
            ' (' .
            $orgName .
            ') har anmärkningar eller övriga poster som behöver kontrolleras.',
        'size' => 'sm'
    ],
    'icon' => [
        'name' => 'report',
        'size' => 'md',
        'color' => 'white'
    ],
    'classList' => ['u-margin__top--2']
])
@endnotice
