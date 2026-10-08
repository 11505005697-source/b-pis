<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Challenge: returns ['isValid' => bool, 'errors' => array].
function validateProfile(array $profile): array
{
    $errors = [];

    // Decision: every rule runs (no early return) so ALL errors are reported at once.
    $name = trim((string) ($profile['name'] ?? ''));
    if ($name === '' || mb_strlen($name) < 3) {
        $errors[] = 'Name is required and must have at least three characters.';
    }

    $age = $profile['age'] ?? null;
    if (!is_int($age) || $age < 0 || $age > 120) {
        $errors[] = 'Age must be a whole number between 0 and 120.';
    }

    if (trim((string) ($profile['dzongkhag'] ?? '')) === '') {
        $errors[] = 'Dzongkhag is required.';
    }

    if (strlen((string) ($profile['cid'] ?? '')) !== 11) {
        $errors[] = 'CID must contain exactly 11 characters.';
    }

    if (!in_array($profile['status'] ?? '', ['Active', 'Inactive'], true)) {
        $errors[] = 'Status must be Active or Inactive.';
    }

    return ['isValid' => count($errors) === 0, 'errors' => $errors];
}

$tests = [
    'Valid profile' => ['name' => 'Pema Dorji', 'age' => 24, 'dzongkhag' => 'Thimphu', 'cid' => '10101000001', 'status' => 'Active'],
    'Invalid: several problems' => ['name' => 'Al', 'age' => 130, 'dzongkhag' => '', 'cid' => '123', 'status' => 'Unknown'],
    'Invalid: age is text, missing name' => ['name' => '', 'age' => 'twenty', 'dzongkhag' => 'Paro', 'cid' => '10101000002', 'status' => 'inactive'],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 6 - Validation</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 6: Profile Validation Engine</h1>
<?php foreach ($tests as $title => $profile):
    $result = validateProfile($profile); ?>
<h2><?= e($title) ?></h2>
<?php if ($result['isValid']): ?>
    <p class="ok">Profile is valid</p>
<?php else: ?>
    <p class="warn"><?= count($result['errors']) ?> error(s) found:</p>
    <ul>
    <?php foreach ($result['errors'] as $error): ?>
        <li><?= e($error) ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php endforeach; ?>
</body></html>
