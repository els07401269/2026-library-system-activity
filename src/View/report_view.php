<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Report</title>
</head>
<body>
    <h2>Library Report</h2>
    <p>Total Books: <?= htmlspecialchars((string) $report['totalBooks']) ?></p>
    <p>Borrowed: <?= htmlspecialchars((string) $report['totalBorrowed']) ?></p>
    <p>Returned: <?= htmlspecialchars((string) $report['totalReturned']) ?></p>
    <p>Total Fines Collected: ₱<?= htmlspecialchars(number_format((float) $report['totalFines'], 2)) ?></p>
</body>
</html>