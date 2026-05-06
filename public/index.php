<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Library\Config\DatabaseConfig;
use App\Library\Repository\BookRepository;
use App\Library\Repository\BorrowRepository;
use App\Library\Service\LibraryService;
use App\Library\Entity\Book;

// Build dependencies
$config = new DatabaseConfig($db_host, $db_name, $db_user, $db_pass);
$bookRepository = new BookRepository($config);
$borrowRepository = new BorrowRepository($config);
$libraryService = new LibraryService($bookRepository, $borrowRepository);

$action = $_GET['act'] ?? '';

if ($action === 'add') {
    $book = new Book(
        $_POST['title'],
        $_POST['author'],
        (int) $_POST['year'],
        $_POST['genre']
    );
    $bookRepository->addBook($book);
} elseif ($action === 'list') {
    $books = $bookRepository->findAll();
    require __DIR__ . '/../src/View/book_list.php';
} elseif ($action === 'report') {
     $report = [
        'totalBooks'    => $bookRepository->countAll(),
        'totalBorrowed' => $borrowRepository->countBorrowed(),
        'totalReturned' => $borrowRepository->countReturned(),
        'totalFines'    => $borrowRepository->sumFines(),
    ];
    require __DIR__ . '/../src/View/report_view.php';
} elseif ($action === 'borrow') {
    $libraryService->borrowBook(
        (int) $_POST['student_id'],
        (int) $_POST['book_id']
    );
} elseif ($action === 'return') {
    $fine = $libraryService->returnBook((int) $_POST['record_id']);
    echo 'Book returned. Fine: ₱' . number_format($fine, 2);
}