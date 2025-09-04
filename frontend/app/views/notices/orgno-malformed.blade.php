@notice([
    'type' => 'warning',
    'message' => [
        'text' => $errorMessage ?? 'Det angivna organisationsnumret är felaktigt formaterat. Kontrollera och försök igen.',
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