<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Challenge: permissions live in an associative array, not a chain of if/else.
$permissions = [
    'administrator' => ['create', 'read', 'update', 'delete', 'export'],
    'data officer'  => ['create', 'read', 'update'],
    'viewer'        => ['read'],
];

function canPerformAction(array $permissions, string $role, string $action): bool
{
    // Decision: normalise once so comparisons are case-insensitive.
    $role   = strtolower(trim($role));
    $action = strtolower(trim($action));
    // Unknown roles fall back to an empty list, so they get no permissions.
    return in_array($action, $permissions[$role] ?? [], true);
}

$tests = [
    ['Administrator', 'export'], ['administrator', 'DELETE'], ['Data Officer', 'update'],
    ['Data Officer', 'delete'], ['Viewer', 'read'], ['VIEWER', 'create'],
    ['Guest', 'read'], ['Viewer', 'export'], ['Data Officer', 'Export'],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 8 - Permissions</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 8: Role Permission Checker</h1>
<table>
<tr><th>Role</th><th>Action</th><th>Result</th><th>Message</th></tr>
<?php foreach ($tests as [$role, $action]):
    $allowed = canPerformAction($permissions, $role, $action);
    $known = isset($permissions[strtolower(trim($role))]);
    if ($allowed) {
        $message = "The role '$role' is allowed to $action records.";
    } elseif (!$known) {
        $message = "The role '$role' is not recognised, so it has no permissions.";
    } else {
        $message = "The role '$role' is not allowed to $action records.";
    } ?>
<tr>
    <td><?= e($role) ?></td><td><?= e($action) ?></td>
    <td class="<?= $allowed ? 'ok' : 'warn' ?>"><?= $allowed ? 'Allowed' : 'Denied' ?></td>
    <td><?= e($message) ?></td>
</tr>
<?php endforeach; ?>
</table>
</body></html>
