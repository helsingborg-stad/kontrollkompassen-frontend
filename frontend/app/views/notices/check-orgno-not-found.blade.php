<?php
$code = isset($_GET['code']) ? (int) $_GET['code'] : null;

$statusCodeMap = [
    400 => 'Felaktigt uppslag, vänligen kontrollera och försök igen',
    500 => 'Ett okänt fel inträffade',
    404 => 'Ingen information hittades för det angivna organisationsnumret',
];

$message = $statusCodeMap[$code] ?? array_key_exists($code, $statusCodeMap)
?>
@if ($message)
    @notice([
        'type' => 'info',
        'message' => [
            'text' => $message,
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
@endif
