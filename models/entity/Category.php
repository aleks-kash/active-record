<?php
namespace models\entity;

use core\repository\EntityModel;

class Category extends EntityModel
{
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ? string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return null|string
     */
    public function setDescription(string $description): ? string
    {
        $this->description = $description;

        return $this;
    }
}
