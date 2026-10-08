<?php

function is_valid_cid(string $cid): bool
{
    return strlen($cid) === 11 && ctype_digit($cid);
}

function make_reference(
    int $id,
    string $dzongkhag,
    string $submitted
): string
{
    $dzongkhag = trim($dzongkhag);

    $code = strtoupper(substr($dzongkhag, 0, 3));

    $year = substr($submitted, 0, 4);

    $idNumber = sprintf('%04d', $id);

    return "$code-$year-$idNumber";
}

function wait_band(int $days): string
{
    if ($days < 0) {
        return 'Check date';
    }
    elseif ($days <= 7) {
        return 'On time';
    }
    elseif ($days <= 14) {
        return 'Follow up';
    }
    else {
        return 'Overdue';
    }
}

function can_move(string $from, string $to): bool
{
    $rules = [
        'Submitted' => ['Under review'],
        'Under review' => ['Approved', 'Rejected'],
        'Rejected' => ['Submitted'],
        'Approved' => []
    ];

    $allowed = $rules[$from] ?? [];

    return in_array($to, $allowed, true);
}

function can_move_as(
    string $role,
    string $from,
    string $to
): bool
{
    if ($role === 'officer') {
        return $to === 'Under review'
            && can_move($from, $to);
    }

    if ($role === 'approver') {
        return ($to === 'Approved' || $to === 'Rejected')
            && can_move($from, $to);
    }

    if ($role === 'requester') {
        return $to === 'Submitted'
            && can_move($from, $to);
    }

    return false;
}
?>