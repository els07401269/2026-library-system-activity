# Student Library Management System

A OOP PHP application for managing library books, borrow records,
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
1.Go to the add book section
2.Enter the book details such as the title , author ,published year , and category.
3.Click the "save" button to add the book to the LegacyLibrarySystem.

### Borrowing a Book
1.Select a book from the available book list
2.Click the "borrow" button.
3.Confirm the transaction to successfully borrow the book.

### Returning a Book
1.Go to the Borrowed book section.
2.Select the borrowed book.
3.Click the "returned" button to complete the return process.
