<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for Advisor
 */
class UpdateAdvisorRequest
{
    /**
     * @var string[]
     * @Assert\NotNull
     * @Assert\Count(min = 1)
     * @Assert\All({
     *     @Assert\Language(message="'{{ value }}' is not a valid language.")
     * })
     */
    public array $languages;

    /**
     * @var string
     * @Assert\NotBlank(message="Name should not be blank.")
     */
    public string $name;

    /**
     * @var float
     * @Assert\Positive
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    public float $pricePerMinute;

    /**
     * @param string $name
     * @param float $pricePerMinute
     * @param string[] $languages
     * @param string $description
     * @param int $availability
     */
    public function __construct(
        public int $id,
        string $name,
        float $pricePerMinute,
        array $languages,
        public string $description = '',
        public int $availability = 0
    ){
        $this->languages = $languages;
        $this->name = $name;
        $this->pricePerMinute = $pricePerMinute;
    }
}