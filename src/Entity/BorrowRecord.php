<?php

declare(strict_types=1);

namespace App\Library\Entity;

use DateTime;

class BorrowRecord{
   
    private ?int $id;
    private int $studentId;
    private int $bookId;
    private DateTime $borrowDate;
    private DateTime $dueDate;
    private string $status;
    private float $fineAmount;

    public function __construct(
        int $studentId,
        int $bookId,
        DateTime $borrowDate,
        DateTime $dueDate,
        string $status,
        float $fineAmount = 0.0,
        ?int $id = null
    ) {
        $this->studentId = $studentId;
        $this->bookId = $bookId;
        $this->borrowDate = $borrowDate;
        $this->dueDate = $dueDate;
        $this->status = $status;
        $this->fineAmount = $fineAmount;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getBorrowDate(): DateTime
    {
        return $this->borrowDate;
    }

    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getFineAmount(): float
    {
        return $this->fineAmount;
    }
}