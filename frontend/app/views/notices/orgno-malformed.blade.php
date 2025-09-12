@notice([
    'type' => 'warning',
    'message' => [
        'title' => $errorMessage,
        'text' => $previousException ?? null,
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