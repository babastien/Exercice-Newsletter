<?php

namespace App\Entity;

class Interest {

    private int $id;
    private string $interestLabel;

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
     * Get the value of interestLabel
     */
    public function getInterestLabel(): string
    {
        return $this->interestLabel;
    }

    /**
     * Set the value of interestLabel
     */
    public function setInterestLabel(string $interestLabel): self
    {
        $this->interestLabel = $interestLabel;

        return $this;
    }
}