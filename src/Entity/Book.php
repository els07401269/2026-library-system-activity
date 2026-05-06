<?php

declare(strict_types=1);

namespace App\Library\Entity;

class Book{
    
    private ?int $id;
    private string $title;
    private string $author;
    private int $year;
    private string $genre;

    public function __construct(
        string $title,
        string $author,
        int $year,
        string $genre,
        ?int $id = null
    ) {
        if ($year < 1000 || $year > (int) date('Y')) {
            throw new \InvalidArgumentException('Invalid publication year: ' . $year);
        }

        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->genre = $genre;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }
}