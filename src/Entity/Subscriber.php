<?php

namespace App\Entity;

use DateTimeImmutable;

class Subscriber {

    private int $id;
    private DateTimeImmutable $createdOn;
    private string $email;
    private string $firstname;
    private string $lastname;
    private int $originId;

    public function __construct(array $data = []) {

        foreach ($data as $propertyName => $value) {
            $setter = 'set' . ucfirst($propertyName);
            if(method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of createdOn
     */
    public function getCreatedOn(): DateTimeImmutable
    {
        return $this->createdOn;
    }

    /**
     * Set the value of createdOn
     */
    public function setCreatedOn(DateTimeImmutable $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of firstname
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of originId
     */
    public function getOriginId(): int
    {
        return $this->originId;
    }

    /**
     * Set the value of originId
     */
    public function setOriginId(int $originId): self
    {
        $this->originId = $originId;

        return $this;
    }
}