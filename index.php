<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

class Author {
    private string $name;
    private string $email;
    private array $books = [];

    public function __construct(string $name, string $email) {
        $this->name = $name;
        $this->email = $email;
    }

    public function addBook(Book $book): void {
        $this->books[] = $book;
    }

    public function getAuthorBooks(): array {
        return $this->books;
    }

    public function getName(): string {
        return $this->name;
    }
}

class Book {
    protected string $title;
    protected Author $author;
    protected string $isbn;
    protected string $status;

    public function __construct(string $title, Author $author, string $isbn) {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->status = "available";
        $author->addBook($this);
    }

    public function borrowBook(): void {
        if ($this->status === "available") {
            $this->status = "borrowed";
        }
    }

    public function returnBook(): void {
        $this->status = "available";
    }

    public function getBookInfo(): string {
        return "Title: {$this->title}, Author: {$this->author->getName()}, ISBN: {$this->isbn}, Status: {$this->status}";
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getISBN(): string {
        return $this->isbn;
    }
}

class DigitalBook extends Book {
    private float $fileSize;

    public function __construct(string $title, Author $author, string $isbn, float $fileSize) {
        parent::__construct($title, $author, $isbn);
        $this->fileSize = $fileSize;
    }

    public function getBookInfo(): string {
        return parent::getBookInfo() . ", File Size: {$this->fileSize}MB";
    }
}

class Library {
    private array $books = [];

    public function addBook(Book $book): void {
        $this->books[] = $book;
    }

    public function getAllBooks(): array {
        return $this->books;
    }

    public function findBookByISBN(string $isbn): ?Book {
        foreach ($this->books as $book) {
            if ($book->getISBN() === $isbn) {
                return $book;
            }
        }
        return null;
    }

    public function listAvailableBooks(): array {
        return array_filter($this->books, fn($book) => $book->getStatus() === "available");
    }
}

$author1 = new Author("J.K. Rowling", "jk@example.com");
$author2 = new Author("George Orwell", "orwell@example.com");

$book1 = new Book("Harry Potter", $author1, "123-456-789");
$book2 = new DigitalBook("1984", $author2, "987-654-321", 2.5);

$library = new Library();
$library->addBook($book1);
$library->addBook($book2);

$book1->borrowBook();

foreach ($library->getAllBooks() as $book) {
    echo $book->getBookInfo() . "<br>";
}

echo "<br>Available Books:<br>";
foreach ($library->listAvailableBooks() as $book) {
    echo $book->getBookInfo() . "<br>";
}

?>
</body>
</html>
