<?php
//task 2.2
declare(strict_types=1);

namespace App\Repository;

use DateTime;
use DateInterval;

class BookRepository{

    private DatabaseConnection $connection;

    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    public function addBook(Book $book): int
    {
        $sql = 'INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param(
            'ssis',
            $book->getTitle(),
            $book->getAuthor(),
            $book->getYear(),
            $book->getGenre()
        );
        $statement->execute();

        return $this->connection->insert_id;
    }
}
