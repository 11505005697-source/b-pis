<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

// Exercise 3 - Reference numbers

// Loop through all 8 requests
foreach ($requests as $request) {

    // Create the reference number
    $reference = make_reference(
        $request['id'],
        $request['dzongkhag'],
        $request['submitted']
    );

    // Print the reference number
    echo $reference . "<br>";
}