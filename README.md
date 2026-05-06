# Student Library Management System

A refactored OOP PHP application for managing library books, borrow records,
and overdue fines. Built following PSR-12 coding standards.

## Author

- Elsa Mae Dela Cruz

## Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Git

## Installation

1. Clone the repository
   git clone https://github.com/dwcl-sirlana/2026-library-system-activity.git
2. Import `database/schema.sql` into MySQL
3. Copy `.env.example` to `.env` and configure database credentials
4. Run `composer install`

## File Structure

src/
  Config/         # Configuration and constants
  Entity/         # Data models (Book, BorrowRecord, Student)
  Exception/      # Custom exceptions
  Repository/     # Database access layer
  Service/        # Business logic
  View/           # HTML templates
public/           # Web-accessible entry point
docs/             # Generated PHPDoc output

## PSR-12 Compliance

All PHP files follow PSR-12 coding standards:
- 4-space indentation
- Unix LF line endings
- Strict typing enabled
- Descriptive naming conventions

## Usage Examples

### Adding a Book

```php
$connection = new DatabaseConnection($config);
$repository = new BookRepository($connection);
$book = new Book('The Great Gatsby', 'F. Scott Fitzgerald', 1925, 'Fiction');
$bookId = $repository->addBook($book);
```

### Borrowing a Book

```php
$service = new LibraryService($bookRepository, $borrowRepository);
$service->borrowBook(101, 42, 14); // student 101 borrows book 42 for 14 days
```

### Returning a Book

```php
$fine = $service->returnBook(55); // returns record #55, returns fine amount
echo 'Fine: ₱' . number_format($fine, 2);
```