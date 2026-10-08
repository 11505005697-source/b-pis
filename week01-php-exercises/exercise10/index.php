<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

const MAX_SCORE = 100;
const PENALTY = 25;

/** Returns a list of problems found in one profile (empty list = clean). */
function findProblems(array $p): array
{
    $problems = [];
    if (trim((string) ($p['name'] ?? '')) === '') {
        $problems[] = 'Missing name';
    }
    $age = $p['age'] ?? null;
    if (!is_int($age) || $age < 0 || $age > 120) {
        $problems[] = 'Invalid age';
    }
    if (trim((string) ($p['dzongkhag'] ?? '')) === '') {
        $problems[] = 'Missing dzongkhag';
    }
    if (!in_array($p['status'] ?? '', ['Active', 'Inactive'], true)) {
        $problems[] = 'Invalid status';
    }
    return $problems;
}

// Challenge: start at 100, deduct 25 per problem (never below 0).
function dataQualityScore(array $problems): int
{
    return max(0, MAX_SCORE - PENALTY * count($problems));
}

function filterProfiles(array $profiles, string $dzongkhag, string $status): array
{
    return array_values(array_filter($profiles, function (array $p) use ($dzongkhag, $status): bool {
        $dzOk = $dzongkhag === 'All' || ($p['dzongkhag'] ?? '') === $dzongkhag;
        $stOk = $status === 'All' || ($p['status'] ?? '') === $status;
        return $dzOk && $stOk;
    }));
}

function canPerformAction(array $permissions, string $role, string $action): bool
{
    return in_array(strtolower(trim($action)), $permissions[strtolower(trim($role))] ?? [], true);
}

/** Decision: only profiles with a valid integer age take part in age statistics. */
function validAges(array $profiles): array
{
    $ages = [];
    foreach ($profiles as $p) {
        if (is_int($p['age'] ?? null) && $p['age'] >= 0 && $p['age'] <= 120) {
            $ages[] = $p['age'];
        }
    }
    return $ages;
}

$datasets = [
    'valid' => [
        ['name' => 'Pema Dorji',     'dzongkhag' => 'Thimphu', 'age' => 24, 'status' => 'Active'],
        ['name' => 'Karma Wangmo',   'dzongkhag' => 'Paro',    'age' => 67, 'status' => 'Active'],
        ['name' => 'Sonam Tobgay',   'dzongkhag' => 'Thimphu', 'age' => 35, 'status' => 'Inactive'],
        ['name' => 'Dechen Om',      'dzongkhag' => 'Punakha', 'age' => 12, 'status' => 'Active'],
        ['name' => 'Jigme Norbu',    'dzongkhag' => 'Thimphu', 'age' => 61, 'status' => 'Active'],
        ['name' => 'Tshering Lhamo', 'dzongkhag' => 'Haa',     'age' => 29, 'status' => 'Inactive'],
        ['name' => 'Ugyen Tenzin',   'dzongkhag' => 'Paro',    'age' => 44, 'status' => 'Active'],
        ['name' => 'Choki Zangmo',   'dzongkhag' => 'Bumthang','age' => 18, 'status' => 'Active'],
    ],
    'problems' => [
        ['name' => 'Pema Dorji',     'dzongkhag' => 'Thimphu', 'age' => 24,    'status' => 'Active'],
        ['name' => '',               'dzongkhag' => 'Paro',    'age' => 67,    'status' => 'Active'],      // missing name
        ['name' => 'Sonam Tobgay',   'dzongkhag' => '',        'age' => 150,   'status' => 'Inactive'],    // dzongkhag + age
        ['name' => 'Dechen Om',      'dzongkhag' => 'Punakha', 'age' => 12,    'status' => 'Pending'],     // status
        ['name' => 'Jigme Norbu',    'dzongkhag' => 'Thimphu', 'age' => '61',  'status' => 'Active'],      // age is text
        ['name' => 'Tshering Lhamo', 'dzongkhag' => 'Haa',     'age' => 29,    'status' => 'Inactive'],
        ['name' => 'Ugyen Tenzin',   'dzongkhag' => 'Paro',    'age' => 44,    'status' => 'Active'],
        ['name' => 'Choki Zangmo',   'dzongkhag' => 'Bumthang','age' => 18,    'status' => 'Active'],
    ],
];

$permissions = [
    'administrator' => ['create', 'read', 'update', 'delete', 'export'],
    'data officer'  => ['create', 'read', 'update'],
    'viewer'        => ['read'],
];

// Selections come from the URL; each is checked against a fixed list before use.
$datasetKey = ($_GET['dataset'] ?? 'valid') === 'problems' ? 'problems' : 'valid';
$profiles   = $datasets[$datasetKey];

$dzongkhagOptions = array_merge(['All'], array_values(array_unique(array_filter(array_map(fn($p) => (string) $p['dzongkhag'], $profiles)))));
$statusOptions    = ['All', 'Active', 'Inactive'];
$roleOptions      = ['Administrator', 'Data Officer', 'Viewer', 'Guest'];
$actionOptions    = ['create', 'read', 'update', 'delete', 'export'];

$selDz     = in_array($_GET['dzongkhag'] ?? 'All', $dzongkhagOptions, true) ? ($_GET['dzongkhag'] ?? 'All') : 'All';
$selStatus = in_array($_GET['status'] ?? 'All', $statusOptions, true) ? ($_GET['status'] ?? 'All') : 'All';
$selRole   = in_array($_GET['role'] ?? 'Viewer', $roleOptions, true) ? ($_GET['role'] ?? 'Viewer') : 'Viewer';
$selAction = in_array($_GET['action'] ?? 'read', $actionOptions, true) ? ($_GET['action'] ?? 'read') : 'read';

// Statistics
$total    = count($profiles);
$active   = 0;
$inactive = 0;
foreach ($profiles as $p) {
    if ($p['status'] === 'Active') { $active++; }
    elseif ($p['status'] === 'Inactive') { $inactive++; }
}
$ages = validAges($profiles);
$averageAge = count($ages) > 0 ? array_sum($ages) / count($ages) : 0;

$youngest = null;
$oldest = null;
foreach ($profiles as $p) {
    if (!is_int($p['age']) || $p['age'] < 0 || $p['age'] > 120) { continue; }
    if ($youngest === null || $p['age'] < $youngest['age']) { $youngest = $p; }
    if ($oldest === null || $p['age'] > $oldest['age']) { $oldest = $p; }
}

// Data quality
$scores = [];
$imperfect = 0;
$worstIndex = null;
$worstScore = MAX_SCORE;
$scoreSum = 0;
foreach ($profiles as $i => $p) {
    $problems = findProblems($p);
    $score = dataQualityScore($problems);
    $scores[$i] = ['problems' => $problems, 'score' => $score];
    $scoreSum += $score;
    if ($score < MAX_SCORE) { $imperfect++; }
    if ($score < $worstScore) { $worstScore = $score; $worstIndex = $i; }
}
$averageScore = $total > 0 ? $scoreSum / $total : 0;

$filtered = filterProfiles($profiles, $selDz, $selStatus);
$allowed  = canPerformAction($permissions, $selRole, $selAction);
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 10 - B-PIS Dashboard</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 10: B-PIS Command Dashboard</h1>

<form method="get">
    <label>Dataset
        <select name="dataset">
            <option value="valid" <?= $datasetKey === 'valid' ? 'selected' : '' ?>>Complete valid dataset</option>
            <option value="problems" <?= $datasetKey === 'problems' ? 'selected' : '' ?>>Dataset with problems</option>
        </select></label>
    <label>Dzongkhag
        <select name="dzongkhag">
        <?php foreach ($dzongkhagOptions as $o): ?>
            <option <?= $o === $selDz ? 'selected' : '' ?>><?= e($o) ?></option>
        <?php endforeach; ?>
        </select></label>
    <label>Status
        <select name="status">
        <?php foreach ($statusOptions as $o): ?>
            <option <?= $o === $selStatus ? 'selected' : '' ?>><?= e($o) ?></option>
        <?php endforeach; ?>
        </select></label>
    <label>Role
        <select name="role">
        <?php foreach ($roleOptions as $o): ?>
            <option <?= $o === $selRole ? 'selected' : '' ?>><?= e($o) ?></option>
        <?php endforeach; ?>
        </select></label>
    <label>Action
        <select name="action">
        <?php foreach ($actionOptions as $o): ?>
            <option <?= $o === $selAction ? 'selected' : '' ?>><?= e($o) ?></option>
        <?php endforeach; ?>
        </select></label>
    <button type="submit">Apply</button>
</form>

<h2>Overview</h2>
<ul>
    <li>Total profiles: <?= $total ?></li>
    <li>Active: <?= $active ?> | Inactive: <?= $inactive ?></li>
    <li>Youngest: <?= $youngest ? e($youngest['name'] !== '' ? $youngest['name'] : '(no name)') . ' (' . $youngest['age'] . ')' : 'n/a' ?></li>
    <li>Oldest: <?= $oldest ? e($oldest['name'] !== '' ? $oldest['name'] : '(no name)') . ' (' . $oldest['age'] . ')' : 'n/a' ?></li>
    <li>Average age (valid ages only): <?= number_format($averageAge, 1) ?></li>
</ul>

<h2>Permission check</h2>
<p class="<?= $allowed ? 'ok' : 'warn' ?>">
    <?= e($selRole) ?> <?= $allowed ? 'may' : 'may not' ?> <?= e($selAction) ?> records.
</p>

<h2>Filtered profiles (<?= e($selDz) ?> / <?= e($selStatus) ?>)</h2>
<?php if (count($filtered) === 0): ?>
    <p class="warn">No matching records found</p>
<?php else: ?>
<table>
<tr><th>Name</th><th>Dzongkhag</th><th>Age</th><th>Status</th></tr>
<?php foreach ($filtered as $p): ?>
<tr>
    <td><?= e((string) $p['name']) ?></td><td><?= e((string) $p['dzongkhag']) ?></td>
    <td><?= e((string) $p['age']) ?></td><td><?= e((string) $p['status']) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<h2>Data-quality report</h2>
<table>
<tr><th>#</th><th>Name</th><th>Score</th><th>Warnings</th></tr>
<?php foreach ($profiles as $i => $p): ?>
<tr>
    <td><?= $i + 1 ?></td>
    <td><?= e((string) $p['name'] !== '' ? (string) $p['name'] : '(no name)') ?></td>
    <td><?= $scores[$i]['score'] ?></td>
    <td class="<?= $scores[$i]['problems'] ? 'warn' : 'ok' ?>">
        <?= $scores[$i]['problems'] ? e(implode('; ', $scores[$i]['problems'])) : 'None' ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<p>Average score: <strong><?= number_format($averageScore, 1) ?></strong> |
   Imperfect profiles: <strong><?= $imperfect ?></strong></p>
<?php if ($worstIndex !== null): ?>
    <p class="warn">Needs most correction: record #<?= $worstIndex + 1 ?> (score <?= $worstScore ?>).</p>
<?php else: ?>
    <p class="ok">All records are perfect. Nothing needs correction.</p>
<?php endif; ?>
</body></html>
