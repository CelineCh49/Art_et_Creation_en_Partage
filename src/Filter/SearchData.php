<?php

namespace App\Filter;

use App\Entity\Category;

class SearchData
{
    public ?string $q = null;
    public ?Category $category = null;
}