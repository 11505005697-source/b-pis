<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

// Exercise 6 - Find the bugs

function count_pending(array $requests): int
{
    $total = 0;

    // Start at index 0 and check every request
    for ($i = 0; $i < count($requests); $i++) {

        // The correct array key is lowercase 'status'
        $status = $requests[$i]['status'];

        // Check if the status is Submitted OR Under review
        if (
            $status === 'Submitted' ||
            $status === 'Under review'
        ) {
            // Actually increase the total
            $total++;
        }
    }

    // Return the final count
    return $total;
}

echo count_pending($requests);