<?php

namespace App\Entity;

class Origin {

    private int $id;
    private string $originLabel;

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
     * Get the value of originLabel
     */
    public function getOriginLabel(): string
    {
        return $this->originLabel;
    }

    /**
     * Set the value of originLabel
     */
    public function setOriginLabel(string $originLabel): self
    {
        $this->originLabel = $originLabel;

        return $this;
    }
}