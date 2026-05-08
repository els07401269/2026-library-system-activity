<?php

declare(strict_types=1);

class LegacyLibrarySystem{

    private string $db_host = "localhost";
    private string $db_user = "root";
    private string $db_password = "";
    private string $db = "library_db";
    private mysqli $conn;

    private const FINE_RATE = 5;
    private const STATUS_BORROWED = 'borrowed';
    private const STATUS_RETURNED = 'returned';

    //sa pag connect sa database
    public function connect(): void
    {
        $this->conn = new mysqli(
            $this->db_host,
            $this->db_user,
            $this->db_password,
            $this->db
        );

        if ($this->conn->connect_error) {
            throw new Exception("Database connection failed: " . $this->conn->connect_error);
        }
    }

    //function for add book
    public function addBook(string $title, string $author, int $year, string $genre): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssis", $title, $author, $year, $genre);
        $stmt->execute();

        return $this->conn->insert_id;
    }

    //function for get book by id
    public function getBook(int $bookId): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM books WHERE book_id = ?"
        );

        $stmt->bind_param("i", $bookId);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

   //function sa pag borrow book
    public function borrowBook(int $studentId, int $bookId, int $days): bool
    {
        $borrowDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime("+$days days"));
        $status = self::STATUS_BORROWED;

        $stmt = $this->conn->prepare(
            "INSERT INTO borrow_records (student_id, book_id, borrow_date, due_date, status)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("iisss", $studentId, $bookId, $borrowDate, $dueDate, $status);

        return $stmt->execute();
    }

  //function for returning a book
    public function returnBook(int $recordId): float
    {
        $stmt = $this->conn->prepare(
            "SELECT due_date FROM borrow_records WHERE record_id = ?"
        );

        $stmt->bind_param("i", $recordId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            throw new Exception("Record not found");
        }

        $dueDate = strtotime($result['due_date']);
        $today = strtotime(date('Y-m-d'));

        $daysLate = max(0, ($today - $dueDate) / 86400);
        $fine = $daysLate * self::FINE_RATE;

        $returnDate = date('Y-m-d');
        $status = self::STATUS_RETURNED;

        $stmt = $this->conn->prepare(
            "UPDATE borrow_records 
             SET return_date = ?, fine_amount = ?, status = ?
             WHERE record_id = ?"
        );

        $stmt->bind_param("sdsi", $returnDate, $fine, $status, $recordId);
        $stmt->execute();

        return $fine;
    }

    //sa pag get by all books
    public function getBooks(): array
    {
        $result = $this->conn->query("SELECT * FROM books");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

//function for search sa books
    public function searchBooks(string $keyword): array
    {
        $keyword = "%$keyword%";

        $stmt = $this->conn->prepare(
            "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?"
        );

        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

   //function for overdueBooks
    public function getOverdueBooks(): array
    {
        $today = date('Y-m-d');
        $status = self::STATUS_BORROWED;

        $stmt = $this->conn->prepare(
            "SELECT br.*, b.title, s.name 
             FROM borrow_records br
             JOIN books b ON br.book_id = b.book_id
             JOIN students s ON br.student_id = s.student_id
             WHERE br.due_date < ? AND br.status = ?"
        );

        $stmt->bind_param("ss", $today, $status);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    //funciton for mgenerateReport
    public function generateReport(): array
    {
        return [
            'totalBooks' => $this->count("books"),
            'borrowed' => $this->count("borrow_records", "status = 'borrowed'"),
            'returned' => $this->count("borrow_records", "status = 'returned'"),
            'totalFines' => $this->sumFines()
        ];
    }

    private function count(string $table, string $condition = ''): int
    {
        $sql = "SELECT COUNT(*) as count FROM $table";
        if ($condition) {
            $sql .= " WHERE $condition";
        }

        $result = $this->conn->query($sql)->fetch_assoc();
        return (int)$result['count'];
    }

    private function sumFines(): float
    {
        $result = $this->conn
            ->query("SELECT SUM(fine_amount) as total FROM borrow_records")
            ->fetch_assoc();

        return (float)($result['total'] ?? 0);
    }
}

$lib = new LegacyLibrarySystem();
$lib->connect();

$action = $_GET['act'] ?? null;

if ($action === 'add') {
    $lib->addBook(
        $_POST['title'],
        $_POST['author'],
        (int)$_POST['year'],
        $_POST['genre']
    );
    echo "Book added!";
}

elseif ($action === 'list') {
    $books = $lib->getBooks();

    echo "<table border='1'>
            <tr>
                <th>ID</th><th>Title</th><th>Author</th><th>Year</th><th>Genre</th>
            </tr>";

    foreach ($books as $book) {
        echo "<tr>
                <td>{$book['book_id']}</td>
                <td>{$book['title']}</td>
                <td>{$book['author']}</td>
                <td>{$book['year']}</td>
                <td>{$book['genre']}</td>
              </tr>";
    }

    echo "</table>";
}

elseif ($action === 'report') {
    $report = $lib->generateReport();

    echo "<h2>Library Report</h2>";
    echo "<p>Total Books: {$report['totalBooks']}</p>";
    echo "<p>Borrowed: {$report['borrowed']}</p>";
    echo "<p>Returned: {$report['returned']}</p>";
    echo "<p>Total Fines: {$report['totalFines']}</p>";
}
