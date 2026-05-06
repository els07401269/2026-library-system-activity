<?php

declare(strict_types=1);

namespace App\Library\Entity;

class Student{
   
    private ?int $id;
    private string $name;
    private string $email;
    
    public function __construct(string $name, string $email, ?int $id = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}