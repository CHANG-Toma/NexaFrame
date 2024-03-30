<?php

namespace App\Models;

use App\Core\DB;

class Category extends DB
{
    private $id;
    protected string $label;

    public function __construct() {}

    public function getId() {
        return $this->id;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
    }
}