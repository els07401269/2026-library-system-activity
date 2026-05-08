<?php
//task 2.2
declare(strict_types=1);

namespace App\Library\Repository;

use App\Library\Entity\Book;

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

    public function findById(int $bookId): ?Book{
        $sql = 'SELECT * FROM books WHERE book_id = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i' , $bookId);
        $statement->execute();

        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        if($row === null){
            return null;
    }
        return new Book(
            $row['title],
            $row['author'],
           (int) $row['year'],
           $row['genre'],
           (int) $row['book_id']
    );
    }
     public function findAll():array{
     $result = $this->connection->query ('SELECT * FROM books');
     $books = [];
            
      while ($row = $result->fetch_assoc()){
            $books[] = new Book(
            $row['title],
            $row['author'],
           (int) $row['year'],
           $row['genre'],
           (int) $row['book_id']
    );
    }
    return $books;
}
public function countAll():int{
    $result = $this->connection->query ('SELECT COUNT (*) AS total FROM books');
    $row = $result->fetch_assoc();

    return (int) $row['total'];
    }
    }
    
