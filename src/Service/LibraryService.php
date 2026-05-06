<?php

declare(strict_types=1);

namespace App\Library\Service;

use App\Library\Config\LibraryConfig;
use App\Library\Repository\BorrowRepository;
use App\Library\Repository\BookRepository;
use DateTime;
use RuntimeException;

class LibraryService
{
    
    private BookRepository $bookRepository;
    private BorrowRepository $borrowRepository;
    private float $dailyFineRate;

    public function __construct(
        BookRepository $bookRepository,
        BorrowRepository $borrowRepository,
        float $dailyFineRate = LibraryConfig::DAILY_FINE_RATE
    ) {
        $this->bookRepository = $bookRepository;
        $this->borrowRepository = $borrowRepository;
        $this->dailyFineRate = $dailyFineRate;
    }

    public function borrowBook(
        int $studentId,
        int $bookId,
        int $days = LibraryConfig::DEFAULT_BORROW_DAYS
    ): bool {
        $book = $this->bookRepository->findById($bookId);

        if ($book === null) {
            throw new RuntimeException('Book not found with ID: ' . $bookId);
        }

        $dueDate = new DateTime('+' . $days . ' days');

        return $this->borrowRepository->createBorrowRecord($studentId, $bookId, $dueDate);
    }

    public function returnBook(int $recordId): float
    {
        $record = $this->borrowRepository->findById($recordId);

        if ($record === null) {
            throw new RuntimeException('Borrow record not found with ID: ' . $recordId);
        }

        // Calculate overdue fine based on days past the due date
        $fine = $this->calculateOverdueFine($record->getDueDate());
        $this->borrowRepository->markAsReturned($recordId, $fine);

        return $fine;
    }

    public function calculateOverdueFine(DateTime $dueDate): float
    {
        $today = new DateTime();
        $interval = $today->diff($dueDate);

        // %r gives a minus sign if the date is in the future (not overdue)
        $daysOverdue = (int) $interval->format('%r%a');

        return $daysOverdue > 0 ? $daysOverdue * $this->dailyFineRate : 0.0;
    }
}