<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function money(float $amount): string
{
    return 'Nu. ' . number_format($amount, 2);
}

const PROCESSING_CHARGE = 20.0;
const DISCOUNT_RATE = 0.10;
const DISCOUNT_MIN_QUANTITY = 5;

// Challenge: returns every part of the calculation, or an 'error' key.
function calculateServiceFee(array $fees, string $service, int $quantity): array
{
    if (!array_key_exists($service, $fees)) {
        return ['error' => "Unknown service: $service."];
    }
    if ($quantity < 1) {
        return ['error' => 'Quantity must be at least 1.'];
    }
    $unitFee  = (float) $fees[$service];
    $cost     = $unitFee * $quantity;
    // Decision: the discount applies to the cost only, not to the processing charge.
    $discount = $quantity >= DISCOUNT_MIN_QUANTITY ? $cost * DISCOUNT_RATE : 0.0;
    $final    = $cost - $discount + PROCESSING_CHARGE;

    return [
        'service' => $service, 'unitFee' => $unitFee, 'quantity' => $quantity,
        'cost' => $cost, 'discount' => $discount,
        'processing' => PROCESSING_CHARGE, 'final' => $final,
    ];
}

$serviceFees = ['Certificate' => 100, 'Verification' => 50, 'Replacement' => 200, 'Correction' => 75];

$scenarios = [
    ['Certificate', 2], ['Verification', 5], ['Replacement', 10],
    ['Passport', 1], ['Correction', 0],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Exercise 7 - Fee Calculator</title>
<style>body{font-family:Arial,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}table{border-collapse:collapse;margin:1rem 0}td,th{border:1px solid #999;padding:4px 10px;text-align:left}.warn{color:#b00020;font-weight:bold}.ok{color:#0a6b2b;font-weight:bold}</style>
</head><body>
<h1>Exercise 7: Service Fee Calculator</h1>
<?php foreach ($scenarios as [$service, $quantity]):
    $r = calculateServiceFee($serviceFees, $service, $quantity); ?>
<h2><?= e($service) ?> x <?= $quantity ?></h2>
<?php if (isset($r['error'])): ?>
    <p class="warn">Error: <?= e($r['error']) ?></p>
<?php else: ?>
    <table>
    <tr><th>Service</th><td><?= e($r['service']) ?></td></tr>
    <tr><th>Unit fee</th><td><?= money($r['unitFee']) ?></td></tr>
    <tr><th>Quantity</th><td><?= $r['quantity'] ?></td></tr>
    <tr><th>Cost before discount</th><td><?= money($r['cost']) ?></td></tr>
    <tr><th>Discount</th><td><?= money($r['discount']) ?></td></tr>
    <tr><th>Processing charge</th><td><?= money($r['processing']) ?></td></tr>
    <tr><th>Final amount</th><td><strong><?= money($r['final']) ?></strong></td></tr>
    </table>
<?php endif; ?>
<?php endforeach; ?>
</body></html>
