<?php

declare(strict_types=1);

namespace App\Library\Repository;

use App\Library\Config\LibraryConfig;
use App\Library\Entity\BorrowRecord;
use DateTime;
use RuntimeException;

class BorrowRepository{
    private DatabaseConnection $connection;

    public function __construct(DatabaseConnection $connection){
        $this->connection = $connection;
    }
    public function createBorrowRecord(int $studentId, int $bookId, DateTime $dueDate): bool
    {
        $sql = 'INSERT INTO borrow_records (student_id, book_id, borrow_date, due_date, status)
                VALUES (?, ?, ?, ?, ?)';
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new RuntimeException('Failed to prepare createBorrowRecord: ' . $this->connection->error);
        }

        $borrowDate = (new DateTime())->format('Y-m-d');
        $dueDateStr = $dueDate->format('Y-m-d');
        $status = LibraryConfig::STATUS_BORROWED;

        $statement->bind_param('iisss', $studentId, $bookId, $borrowDate, $dueDateStr, $status);

        if (!$statement->execute()) {
            throw new RuntimeException('Failed to execute createBorrowRecord: ' . $statement->error);
        }

        return true;
    }

    // Finds a borrow record by its ID.
    public function findById(int $recordId): ?BorrowRecord
    {
        $sql = 'SELECT * FROM borrow_records WHERE record_id = ?';
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new RuntimeException('Failed to prepare findById: ' . $this->connection->error);
        }

        $statement->bind_param('i', $recordId);

        if (!$statement->execute()) {
            throw new RuntimeException('Failed to execute findById: ' . $statement->error);
        }

        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        if ($row === null) {
            return null;
        }

        return new BorrowRecord(
            (int) $row['student_id'],
            (int) $row['book_id'],
            new DateTime($row['borrow_date']),
            new DateTime($row['due_date']),
            $row['status'],
            (float) $row['fine_amount'],
            (int) $row['record_id']
        );
    }

    // Marks a borrow record  if na returned na 
    public function markAsReturned(int $recordId, float $fineAmount): bool
    {
        $sql = 'UPDATE borrow_records
                SET return_date = ?, fine_amount = ?, status = ?
                WHERE record_id = ?';
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new RuntimeException('Failed to prepare markAsReturned: ' . $this->connection->error);
        }

        $returnDate = (new DateTime())->format('Y-m-d');
        $status = LibraryConfig::STATUS_RETURNED;

        $statement->bind_param('sdsi', $returnDate, $fineAmount, $status, $recordId);

        if (!$statement->execute()) {
            throw new RuntimeException('Failed to execute markAsReturned: ' . $statement->error);
        }

        return true;
    }

    // tigaretrieves lahat ng currently overdue borrow records
    public function findOverdue(): array
    {
        $sql = 'SELECT br.*, b.title, s.name
                FROM borrow_records br
                JOIN books b ON br.book_id = b.book_id
                JOIN students s ON br.student_id = s.student_id
                WHERE br.due_date < ? AND br.status = ?';
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new RuntimeException('Failed to prepare findOverdue: ' . $this->connection->error);
        }

        $today = (new DateTime())->format('Y-m-d');
        $status = LibraryConfig::STATUS_BORROWED;

        $statement->bind_param('ss', $today, $status);

        if (!$statement->execute()) {
            throw new RuntimeException('Failed to execute findOverdue: ' . $statement->error);
        }

        $result = $statement->get_result();
        $list = [];

        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }

        return $list;
    }

    //Counts records
    public function countByStatus(string $status): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM borrow_records WHERE status = ?';
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new RuntimeException('Failed to prepare countByStatus: ' . $this->connection->error);
        }

        $statement->bind_param('s', $status);

        if (!$statement->execute()) {
            throw new RuntimeException('Failed to execute countByStatus: ' . $statement->error);
        }

        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        return (int) $row['total'];
    }

    // Returns the total fines collected from all returned records.
   
    public function sumFinesCollected(): float
    {
        $sql = 'SELECT SUM(fine_amount) AS total FROM borrow_records WHERE fine_amount > 0';
        $statement = $this->connection->prepare($sql);

        if (!$statement) {
            throw new RuntimeException('Failed to prepare sumFinesCollected: ' . $this->connection->error);
        }

        if (!$statement->execute()) {
            throw new RuntimeException('Failed to execute sumFinesCollected: ' . $statement->error);
        }

        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        return (float) ($row['total'] ?? 0.0);
    }
}