<?php

namespace App\Request;

/**
 * Object for sorting Advisors
 */
class OrderByAdvisor
{
    /**
     * @param string|null $field
     * @param string|null $direction
     */
    public function __construct(
        public ?string $field,
        public ?string $direction,
    ){}
}