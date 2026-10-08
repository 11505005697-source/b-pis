<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

// Exercise 5 - Status rules

// Store all test moves in an array.
$testMoves = [
    [
        'from' => 'Submitted',
        'to' => 'Under review'
    ],
    [
        'from' => 'Submitted',
        'to' => 'Approved'
    ],
    [
        'from' => 'Under review',
        'to' => 'Rejected'
    ],
    [
        'from' => 'Approved',
        'to' => 'Submitted'
    ],
    [
        'from' => 'Rejected',
        'to' => 'Submitted'
    ],
    [
        'from' => 'Closed',
        'to' => 'Submitted'
    ]
];

// Print each test result.
foreach ($testMoves as $move) {

    $allowed = can_move($move['from'], $move['to']);

    if ($allowed) {
        $result = 'Allowed';
    } else {
        $result = 'Blocked';
    }

    echo $move['from'];
    echo ' → ';
    echo $move['to'];
    echo ': ';
    echo $result;
    echo '<br>';
}

echo '<br>';


// Extension - Who may make the move?

$roleTests = [
    [
        'role' => 'officer',
        'from' => 'Under review',
        'to' => 'Approved'
    ],
    [
        'role' => 'approver',
        'from' => 'Under review',
        'to' => 'Approved'
    ],
    [
        'role' => 'requester',
        'from' => 'Rejected',
        'to' => 'Submitted'
    ]
];

// Print role test results.
foreach ($roleTests as $test) {

    $result = can_move_as(
        $test['role'],
        $test['from'],
        $test['to']
    );

    echo $test['role'];
    echo ': ';
    echo $test['from'];
    echo ' → ';
    echo $test['to'];
    echo ': ';
    echo $result ? 'true' : 'false';
    echo '<br>';
}