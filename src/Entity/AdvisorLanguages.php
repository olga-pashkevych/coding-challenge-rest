<?php

namespace App\Entity;

use App\Repository\AdvisorLanguagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdvisorLanguagesRepository::class)
 */
class AdvisorLanguages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Advisor", inversedBy="languages")
     * @ORM\JoinColumn(name="advisor_id", referencedColumnName="id")
     */
    private $advisor;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $language_code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvisor(): ?advisor
    {
        return $this->advisor;
    }

    public function setAdvisor(?advisor $advisor): self
    {
        $this->advisor = $advisor;

        return $this;
    }

    public function getLanguageCode(): ?string
    {
        return $this->language_code;
    }

    public function setLanguageCode(string $language_code): self
    {
        $this->language_code = $language_code;

        return $this;
    }
}
