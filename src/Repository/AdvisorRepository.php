<?php

namespace App\Repository;

use App\Request\FilterAdvisor;
use App\Request\OrderByAdvisor;
use App\Entity\Advisor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Advisor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advisor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advisor[]    findAll()
 * @method Advisor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvisorRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Advisor::class);
        $this->manager = $manager;
    }

    /**
     * @param Advisor $advisor
     * @return Advisor
     */
    public function saveAdvisor(Advisor $advisor): Advisor
    {
        $this->manager->persist($advisor);
        $this->manager->flush();

        return $advisor;
    }

    /**
     * @param Advisor $advisor
     * @return void
     */
    public function removeAdvisor(Advisor $advisor)
    {
        $this->manager->remove($advisor);
        $this->manager->flush();
    }

    /**
     * Returns an array of Advisor objects
     *
     * @return Advisor[]
     */
    public function getAdvisorsByFilter(FilterAdvisor $filter, OrderByAdvisor $orderBy): array
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.languages', 'l');

        if ($filter->name) {
            $qb->andWhere('a.name LIKE :name')
                ->setParameter('name', "%$filter->name%");

        }
        if ($filter->language) {
            $qb->andWhere('l.language_code=:language')
                ->setParameter('language', $filter->language);
        }
        if ($orderBy->field === 'price') {
            $direction = $orderBy->direction === 'desc' ? 'DESC' : 'ASC';
            $qb->orderBy('a.price_per_minute', $direction);
        }

        return $qb->getQuery()->getResult();
    }
}
