<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Challenge: one function returns all summary values.
function summariseMembers(array $names): array
{
    if (count($names) === 0) {
        return ['count' => 0, 'shortest' => null, 'longest' => null, 'average' => 0.0];
    }
    $shortest = $names[0];
    $longest  = $names[0];
    $totalLength = 0;
    foreach ($names as $name) {
        $length = mb_strlen($name);
        $totalLength += $length;
        // Decision: strict < and > keep the FIRST name when there is a tie.
        if ($length < mb_strlen($shortest)) { $shortest = $name; }
        if ($length > mb_strlen($longest))  { $longest  = $name; }
    }
    return [
        'count'    => count($names),
        'shortest' => $shortest,
        'longest'  => $longest,
        'average'  => $totalLength / count($names),
    ];
}

$members = ['Ugyen Dorji', 'Pema Lhamo', 'Sonam Tobgay', 'Choki', 'Karma Yangzom Wangmo', 'Tenzin Norbu'];
$summary = summariseMembers($members);
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 3 - Household</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 3: Household Member Summary</h1>
<h2>Members</h2>
<ol>
<?php foreach ($members as $name): ?>
    <li><?= e($name) ?></li>
<?php endforeach; ?>
</ol>
<p><strong>Total members:</strong> <?= $summary['count'] ?></p>
<p><strong>First name:</strong> <?= e($members[0]) ?></p>
<p><strong>Last name:</strong> <?= e($members[count($members) - 1]) ?></p>
<p><strong>Shortest name:</strong> <?= e((string) $summary['shortest']) ?></p>
<p><strong>Longest name:</strong> <?= e((string) $summary['longest']) ?></p>
<p><strong>Average name length:</strong> <?= number_format($summary['average'], 2) ?> characters</p>

<h2>Character counts</h2>
<table>
<tr><th>Name</th><th>Characters</th></tr>
<?php foreach ($members as $name): ?>
<tr><td><?= e($name) ?></td><td><?= mb_strlen($name) ?></td></tr>
<?php endforeach; ?>
</table>
</body></html>
