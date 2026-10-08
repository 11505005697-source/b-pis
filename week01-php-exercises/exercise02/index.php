<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Challenge: returns ageGroup, eligible and explanation together.
function classifyAge(int $age): array
{
    // Decision: invalid ages are rejected first so no later branch sees bad data.
    if ($age < 0 || $age > 120) {
        return [
            'ageGroup'    => 'Invalid',
            'eligible'    => false,
            'explanation' => "An age of $age is invalid. Ages must be between 0 and 120.",
        ];
    }
    if ($age < 13) {
        $group = 'Child';
    } elseif ($age <= 17) {
        $group = 'Teenager';
    } elseif ($age <= 59) {
        $group = 'Adult';
    } else {
        $group = 'Senior Citizen';
    }
    $eligible = $age >= 18;
    $explanation = $eligible
        ? "Age $age is 18 or above, so the citizen qualifies for the adult-only service."
        : "Age $age is below 18, so the citizen does not qualify for the adult-only service.";

    return ['ageGroup' => $group, 'eligible' => $eligible, 'explanation' => $explanation];
}

$citizens = [
    ['Karma Wangdi', 8], ['Tashi Lhamo', 15], ['Dechen Om', 18],
    ['Jigme Tenzin', 59], ['Rinchen Pelden', 60], ['Nima Zangmo', 130], ['Test Negative', -3],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 2 - Age and Eligibility</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 2: Age and Service Eligibility</h1>
<table>
<tr><th>Name</th><th>Age</th><th>Age group</th><th>Eligible</th><th>Explanation</th></tr>
<?php foreach ($citizens as [$name, $age]):
    $r = classifyAge($age); ?>
<tr>
    <td><?= e($name) ?></td>
    <td><?= $age ?></td>
    <td><?= e($r['ageGroup']) ?></td>
    <td class="<?= $r['eligible'] ? 'ok' : 'warn' ?>"><?= $r['eligible'] ? 'Yes' : 'No' ?></td>
    <td><?= e($r['explanation']) ?></td>
</tr>
<?php endforeach; ?>
</table>
</body></html>
