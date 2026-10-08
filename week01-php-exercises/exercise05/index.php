<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Challenge: returns the matching profiles for a search term.
function searchProfiles(array $profiles, string $term): array
{
    $matches = [];
    $term = trim($term);
    foreach ($profiles as $profile) {
        // Decision: stripos gives a case-insensitive "contains" check.
        if ($term !== '' && stripos($profile['name'], $term) !== false) {
            $matches[] = $profile;
        }
    }
    return $matches;
}

function renderTable(array $profiles): string
{
    $html = '<table><tr><th>Name</th><th>Dzongkhag</th><th>Age</th><th>Status</th></tr>';
    foreach ($profiles as $p) {
        $html .= '<tr><td>' . e($p['name']) . '</td><td>' . e($p['dzongkhag']) . '</td><td>'
              . (int) $p['age'] . '</td><td>' . e($p['status']) . '</td></tr>';
    }
    return $html . '</table>';
}

$profiles = [
    ['name' => 'Pema Dorji',     'dzongkhag' => 'Thimphu',  'age' => 24, 'status' => 'Active'],
    ['name' => 'Pema Lhamo',     'dzongkhag' => 'Paro',     'age' => 31, 'status' => 'Active'],
    ['name' => 'Karma Dorji',    'dzongkhag' => 'Punakha',  'age' => 45, 'status' => 'Inactive'],
    ['name' => 'Sonam Choden',   'dzongkhag' => 'Thimphu',  'age' => 19, 'status' => 'Active'],
    ['name' => 'Tshering Wangmo','dzongkhag' => 'Bumthang', 'age' => 62, 'status' => 'Inactive'],
    ['name' => 'Jigme Norbu',    'dzongkhag' => 'Haa',      'age' => 28, 'status' => 'Active'],
    ['name' => 'Dechen Zangmo',  'dzongkhag' => 'Paro',     'age' => 37, 'status' => 'Active'],
    ['name' => 'Ugyen Tenzin',   'dzongkhag' => 'Wangdue',  'age' => 53, 'status' => 'Inactive'],
];

$searchTerms = ['pema', 'JIGME', 'xyz'];  // several matches, one match, no match
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 5 - Record Search</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 5: B-PIS Record Search</h1>
<h2>All profiles (<?= count($profiles) ?>)</h2>
<?= renderTable($profiles) ?>
<?php foreach ($searchTerms as $searchTerm):
    $results = searchProfiles($profiles, $searchTerm); ?>
<h2>Search: "<?= e($searchTerm) ?>"</h2>
<p>Matching profiles: <?= count($results) ?></p>
<?php if (count($results) === 0): ?>
    <p class="warn">No matching records found</p>
<?php else: ?>
    <?= renderTable($results) ?>
<?php endif; ?>
<?php endforeach; ?>
</body></html>
