<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$requests = ['Thimphu', 'Paro', 'Thimphu', 'Punakha', 'Paro', 'Thimphu'];

// Challenge: counters are built dynamically, one array key per dzongkhag.
$counts = [];
foreach ($requests as $dzongkhag) {
    // Decision: initialise a key the first time it appears to avoid undefined-key warnings.
    if (!isset($counts[$dzongkhag])) {
        $counts[$dzongkhag] = 0;
    }
    $counts[$dzongkhag]++;
}

$total = count($requests);
$topDzongkhag = '';
$topCount = 0;
foreach ($counts as $dzongkhag => $count) {
    if ($count > $topCount) {
        $topCount = $count;
        $topDzongkhag = $dzongkhag;
    }
}
$thimphuPercent = $total > 0 ? (($counts['Thimphu'] ?? 0) / $total) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 4 - Service Counter</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 4: Dzongkhag Service Counter</h1>
<h2>Requests</h2>
<ul>
<?php foreach ($requests as $index => $dzongkhag): ?>
    <li>Request #<?= $index + 1 ?>: <?= e($dzongkhag) ?></li>
<?php endforeach; ?>
</ul>
<h2>Requests per dzongkhag</h2>
<ul>
<?php foreach ($counts as $dzongkhag => $count): ?>
    <li><?= e($dzongkhag) ?>: <?= $count ?></li>
<?php endforeach; ?>
</ul>
<p><strong>Total requests:</strong> <?= $total ?></p>
<p><strong>Highest:</strong> <?= e($topDzongkhag) ?> with <?= $topCount ?> requests</p>
<p><strong>Thimphu share:</strong> <?= number_format($thimphuPercent, 1) ?>%</p>
</body></html>
