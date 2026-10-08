<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Decision: one generic counter, wrapped by named functions the exercise asks for.
function countByField(array $records, string $field): array
{
    $counts = [];
    foreach ($records as $record) {
        $key = (string) $record[$field];
        $counts[$key] = ($counts[$key] ?? 0) + 1;
    }
    arsort($counts);
    return $counts;
}
function countByStatus(array $records): array     { return countByField($records, 'status'); }
function countByDzongkhag(array $records): array  { return countByField($records, 'dzongkhag'); }
function countByRecordType(array $records): array { return countByField($records, 'type'); }

function calculatePercentage(int $part, int $total): float
{
    return $total > 0 ? ($part / $total) * 100 : 0.0;
}

function countAgeGroup(array $records, int $min, int $max): int
{
    $n = 0;
    foreach ($records as $r) {
        if ($r['age'] >= $min && $r['age'] <= $max) { $n++; }
    }
    return $n;
}

$records = [
    ['name' => 'Pema Dorji',      'dzongkhag' => 'Thimphu',  'age' => 24, 'status' => 'Active',   'type' => 'Birth Certificate'],
    ['name' => 'Karma Wangmo',    'dzongkhag' => 'Paro',     'age' => 67, 'status' => 'Active',   'type' => 'Citizenship'],
    ['name' => 'Sonam Tobgay',    'dzongkhag' => 'Thimphu',  'age' => 35, 'status' => 'Inactive', 'type' => 'Citizenship'],
    ['name' => 'Dechen Om',       'dzongkhag' => 'Punakha',  'age' => 12, 'status' => 'Active',   'type' => 'Birth Certificate'],
    ['name' => 'Jigme Norbu',     'dzongkhag' => 'Thimphu',  'age' => 61, 'status' => 'Active',   'type' => 'Census'],
    ['name' => 'Tshering Lhamo',  'dzongkhag' => 'Haa',      'age' => 29, 'status' => 'Inactive', 'type' => 'Census'],
    ['name' => 'Ugyen Tenzin',    'dzongkhag' => 'Paro',     'age' => 44, 'status' => 'Active',   'type' => 'Citizenship'],
    ['name' => 'Choki Zangmo',    'dzongkhag' => 'Bumthang', 'age' => 18, 'status' => 'Active',   'type' => 'Citizenship'],
    ['name' => 'Namgay Dema',     'dzongkhag' => 'Thimphu',  'age' => 72, 'status' => 'Inactive', 'type' => 'Census'],
    ['name' => 'Rinchen Pelden',  'dzongkhag' => 'Wangdue',  'age' => 50, 'status' => 'Active',   'type' => 'Birth Certificate'],
    ['name' => 'Tandin Wangchuk', 'dzongkhag' => 'Thimphu',  'age' => 9,  'status' => 'Active',   'type' => 'Birth Certificate'],
];

$total        = count($records);
$statusCounts = countByStatus($records);
$active       = $statusCounts['Active'] ?? 0;
$inactive     = $statusCounts['Inactive'] ?? 0;
$adults       = countAgeGroup($records, 18, 59);
$seniors      = countAgeGroup($records, 60, 120);
$dzongkhags   = countByDzongkhag($records);
$types        = countByRecordType($records);
$topDzongkhag = array_key_first($dzongkhags);
$topType      = array_key_first($types);
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 9 - Records Report</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 9: B-PIS Records Report</h1>

<h2>Summary</h2>
<table>
<tr><th>Measure</th><th>Value</th></tr>
<tr><td>Total records</td><td><?= $total ?></td></tr>
<tr><td>Active</td><td><?= $active ?></td></tr>
<tr><td>Inactive</td><td><?= $inactive ?></td></tr>
<tr><td>Percentage active</td><td><?= number_format(calculatePercentage($active, $total), 1) ?>%</td></tr>
<tr><td>Adults (18-59)</td><td><?= $adults ?></td></tr>
<tr><td>Senior citizens (60+)</td><td><?= $seniors ?></td></tr>
</table>

<h2>Records by dzongkhag</h2>
<table>
<tr><th>Dzongkhag</th><th>Records</th></tr>
<?php foreach ($dzongkhags as $name => $count): ?>
<tr><td><?= e($name) ?></td><td><?= $count ?></td></tr>
<?php endforeach; ?>
</table>

<h2>Records by type</h2>
<table>
<tr><th>Record type</th><th>Records</th></tr>
<?php foreach ($types as $name => $count): ?>
<tr><td><?= e($name) ?></td><td><?= $count ?></td></tr>
<?php endforeach; ?>
</table>

<h2>Interpretation</h2>
<p><?= e((string) $topDzongkhag) ?> appears most often with <?= $dzongkhags[$topDzongkhag] ?> of <?= $total ?> records
(<?= number_format(calculatePercentage($dzongkhags[$topDzongkhag], $total), 1) ?>%).
The most common record type is <?= e((string) $topType) ?> with <?= $types[$topType] ?> records.</p>
</body></html>
