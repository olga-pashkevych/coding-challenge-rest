<?php

namespace App\Repository;

use App\Entity\AdvisorLanguages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdvisorLanguages|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvisorLanguages|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvisorLanguages[]    findAll()
 * @method AdvisorLanguages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvisorLanguagesRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, AdvisorLanguages::class);
        $this->manager = $manager;
    }
}
