<?php
namespace App\Model;

class SearchData
{
    /** @var int */
    public $page = 1;

    /** @var string */
    public string $query = '';

    public function __toString(): string
    {
        // Return a string representation of the object
        return $this->query;
    }
}