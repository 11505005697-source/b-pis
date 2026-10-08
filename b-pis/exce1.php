<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';

// Exercise 1 - Predict the Output

// Snippet A
$x = 0;

$label = $x ?: 'none';

$size = $x ?? 'none';

echo "Snippet A: ";
echo "$label | $size";
echo "<br>";

// Snippet B
$total = 0;

for ($i = 1; $i <= 10; $i++) {

    if ($i % 3 === 0) {
        continue;
    }

    if ($i > 7) {
        break;
    }

    $total += $i;
}

echo "Snippet B: ";
echo $total;
echo "<br>";

// Snippet C
$m = ['b' => 2, 'a' => 1, 'c' => 3];

$out = '';

foreach ($m as $k => $v) {

    $up = $v > 1;

    $out .= $up ? strtoupper($k) : $k;
}

echo "Snippet C: ";
echo $out;
echo "<br>";

// Snippet D
$n = strlen(trim('  Paro  '));

echo "Snippet D: ";
echo $n . substr('Paro', -2);