<?php

namespace App\Entity;

use App\Repository\AdvisorRepository;
use App\Request\CreateAdvisorRequest;
use App\Request\UpdateAdvisorRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdvisorRepository::class)
 */
class Advisor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     * @Groups({"create_advisor", "update_advisor"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"create_advisor", "update_advisor"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     * @Groups({"create_advisor", "update_advisor"})
     */
    private $availability;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     * @OA\Property(type="number")
     * @Groups({"create_advisor", "update_advisor"})
     */
    private ?float $pricePerMinute;

    /**
     * @ORM\OneToMany(targetEntity=AdvisorLanguages::class, mappedBy="advisor", orphanRemoval=true, cascade={"persist"})
     * @OA\Property(type="array", @OA\Items(type="string"))
     * @Groups({"create_advisor", "update_advisor"})
     */
    private $languages;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
    }

    /**
     * @param CreateAdvisorRequest $request
     * @return static
     */
    public static function createFromRequest(CreateAdvisorRequest $request): self
    {
        $advisor = new Advisor();

        return $advisor->updateFields(
            $request->name,
            $request->description,
            $request->availability,
            $request->pricePerMinute,
            $request->languages
        );
    }

    /**
     * @param string $name
     * @param string $description
     * @param bool $availability
     * @param float $pricePerMinute
     * @param array $languages
     * @return $this
     */
    protected function updateFields(
        string $name,
        string $description,
        bool $availability,
        float $pricePerMinute,
        array $languages
    ): self
    {
        $this->setName($name)
            ->setDescription($description)
            ->setAvailability($availability)
            ->setPricePerMinute($pricePerMinute);

        foreach ($languages as $language) {
            $advisorLanguage = new AdvisorLanguages();
            $advisorLanguage
                ->setAdvisor($this)
                ->setLanguageCode($language);

            $this->addLanguage($advisorLanguage);
        }

        return $this;
    }

    /**
     * @param AdvisorLanguages $language
     * @return $this
     */
    public function addLanguage(AdvisorLanguages $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
            $language->setAdvisor($this);
        }

        return $this;
    }

    /**
     * @param UpdateAdvisorRequest $request
     * @return $this
     */
    public function updateFromRequest(UpdateAdvisorRequest $request): self
    {
        // Remove old languages
        $this->languages->clear();

        return $this->updateFields(
            $request->name,
            $request->description,
            $request->availability,
            $request->pricePerMinute,
            $request->languages
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(?bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getPricePerMinute(): ?float
    {
        return $this->pricePerMinute;
    }

    public function setPricePerMinute(float $pricePerMinute): self
    {
        $this->pricePerMinute = $pricePerMinute;

        return $this;
    }

    public function removeLanguage(AdvisorLanguages $language): self
    {
        if ($this->languages->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getAdvisor() === $this) {
                $language->setAdvisor(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "availability" => $this->availability,
            "price_per_minute" => $this->pricePerMinute,
            "languages" => $this->getLanguages()
        ];
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        $languages = [];
        foreach ($this->languages as $language) {
            $languages[] = $language->getLanguageCode();
        }
        return $languages;
    }
}
