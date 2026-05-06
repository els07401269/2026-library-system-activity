<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow a Book</title>
</head>
<body>
    <h2>Borrow a Book</h2>
    <form method="POST" action="/index.php?act=borrow">
        <label for="student_id">Student ID:</label><br>
        <input type="number" id="student_id" name="student_id" required><br><br>

        <label for="book_id">Book ID:</label><br>
        <input type="number" id="book_id" name="book_id" required><br><br>

        <label for="days">Number of Days to Borrow:</label><br>
        <input type="number" id="days" name="days" value="14" min="1" required><br><br>

        <button type="submit">Borrow Book</button>
    </form>
</body>
</html>