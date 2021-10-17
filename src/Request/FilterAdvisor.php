<?php

namespace App\Request;

/**
 * Object for filtering Advisors
 */
class FilterAdvisor
{
    /**
     * @param string|null $name
     * @param string|null $language
     */
    public function __construct(
        public ?string $name,
        public ?string $language
    ){}
}