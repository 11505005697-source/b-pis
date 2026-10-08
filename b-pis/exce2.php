<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';

// Exercise 2 - Busiest Dzongkhag

// Step 1: Create an empty array to store the counts
$counts = [];

// Step 2: Loop through all requests
foreach ($requests as $request) {

    // Only count pending requests
    if (
        $request['status'] === 'Submitted' ||
        $request['status'] === 'Under review'
    ) {

        // Tidy the Dzongkhag name
        $dzongkhag = ucwords(strtolower(trim($request['dzongkhag'])));

        // If the Dzongkhag is not in the array, start its count at 0
        if (!isset($counts[$dzongkhag])) {
            $counts[$dzongkhag] = 0;
        }

        // Increase the count
        $counts[$dzongkhag]++;
    }
}

// Step 3: Find the highest count
$highest = 0;

foreach ($counts as $dzongkhag => $count) {

    if ($count > $highest) {
        $highest = $count;
    }
}

// Step 4: Find and print every Dzongkhag with the highest count
$winners = [];

foreach ($counts as $dzongkhag => $count) {

    if ($count === $highest) {
        $winners[] = $dzongkhag;
    }
}

// Step 5: Print the result
echo "Busiest: " . implode(', ', $winners);
echo " ($highest pending)";
?>