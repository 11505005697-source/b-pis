<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

// Exercise 4 - Waiting-time alert

// Today's date
$today = '2026-10-07';

// Counters for each waiting-time band
$overdue = 0;
$followUp = 0;
$onTime = 0;

// Check every request
foreach ($requests as $r) {

    // Only check pending requests
    if (
        $r['status'] === 'Submitted' ||
        $r['status'] === 'Under review'
    ) {

        // Calculate how many days the request has waited
        $days = intdiv(
            strtotime($today) - strtotime($r['submitted']),
            86400
        );

        // Get the waiting-time band
        $band = wait_band($days);

        // Create the person's full name
        $name = trim($r['first']) . ' ' . ucwords(strtolower(trim($r['last'])));

        // Print the result
        echo '#' . $r['id'] . ' ';
        echo $name . ' — ';
        echo $days . ' days — ';
        echo $band;
        echo '<br>';

        // Count each band
        if ($band === 'Overdue') {
            $overdue++;
        } elseif ($band === 'Follow up') {
            $followUp++;
        } elseif ($band === 'On time') {
            $onTime++;
        }
    }
}

// Print the totals
echo '<br>';

echo $overdue . ' overdue · ';
echo $followUp . ' follow up · ';
echo $onTime . ' on time';