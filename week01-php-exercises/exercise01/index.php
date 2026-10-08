<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Challenge: one reusable function produces the HTML for every labelled field.
function displayProfileField(string $label, string $value): string
{
    return '<p><strong>' . e($label) . ':</strong> ' . e($value) . '</p>';
}

$profiles = [
    [
        'fullName'   => 'Pema Dorji',
        'cid'        => '10101000001',
        'dob'        => '1998-04-12',
        'dzongkhag'  => 'Thimphu',
        'gewog'      => 'Chang',
        'occupation' => 'Teacher',
        'isActive'   => true,
    ],
    [   // Unusual case: CID has only 9 characters
        'fullName'   => 'Sonam Choden',
        'cid'        => '101010001',
        'dob'        => '2001-09-30',
        'dzongkhag'  => 'Paro',
        'gewog'      => 'Doga',
        'occupation' => 'Nurse',
        'isActive'   => false,
    ],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 1 - Citizen Profile</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 1: Fictional Citizen Profile</h1>
<?php foreach ($profiles as $number => $profile):
    // Decision: the Boolean is converted only at display time, so the data stays a true Boolean.
    $statusText = $profile['isActive'] ? 'Active' : 'Inactive';
    $cidLength  = strlen($profile['cid']);
?>
<section>
    <h2>Profile <?= $number + 1 ?></h2>
    <?= displayProfileField('Full name', strtoupper($profile['fullName'])) ?>
    <?= displayProfileField('Fictional CID', $profile['cid']) ?>
    <?= displayProfileField('CID length', (string) $cidLength . ' characters') ?>
    <?php if ($cidLength !== 11): ?>
        <p class="warn">Warning: the CID must contain exactly 11 characters.</p>
    <?php endif; ?>
    <?= displayProfileField('Date of birth', $profile['dob']) ?>
    <?= displayProfileField('Dzongkhag', $profile['dzongkhag']) ?>
    <?= displayProfileField('Gewog', $profile['gewog']) ?>
    <?= displayProfileField('Occupation', $profile['occupation']) ?>
    <?= displayProfileField('Status', $statusText) ?>
</section>
<hr>
<?php endforeach; ?>
</body></html>
