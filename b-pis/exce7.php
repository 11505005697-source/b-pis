<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

// Exercise 7 - Duplicate and invalid CIDs


// --------------------------------------------------
// Part 1: Group request IDs by CID
// --------------------------------------------------

$cidGroups = [];

foreach ($requests as $request) {

    $cid = $request['cid'];
    $id = $request['id'];

    // If this CID has not been seen before,
    // create an empty array for it.
    if (!isset($cidGroups[$cid])) {
        $cidGroups[$cid] = [];
    }

    // Add the request ID to the CID's list.
    $cidGroups[$cid][] = $id;
}


// --------------------------------------------------
// Part 2: Print duplicate CIDs
// --------------------------------------------------

foreach ($cidGroups as $cid => $ids) {

    // Only print CIDs used by more than one request.
    if (count($ids) > 1) {

        echo 'Duplicate ' . $cid . ': requests ';
        echo implode(', ', $ids);
        echo '<br>';
    }
}


// --------------------------------------------------
// Part 3: Print invalid CIDs
// --------------------------------------------------

foreach ($requests as $request) {

    $cid = $request['cid'];

    if (!is_valid_cid($cid)) {

        echo 'Invalid CID in request ';
        echo $request['id'];
        echo ': ';
        echo $cid;
        echo '<br>';
    }
}