@notice([
    'type' => 'warning',
    'message' => [
        'title' => $errorMessage,
        'text' => $previousException ?? null,
        'size' => 'sm'
    ],
    'icon' => [
        'icon' => 'report',
        'size' => 'md',
        'color' => 'white'
    ],
    'classList' => ['u-margin__top--2']
])
@endnotice