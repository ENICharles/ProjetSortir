<?php

namespace App\Repository;

use App\Entity\Localisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Localisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Localisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Localisation[]    findAll()
 * @method Localisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Localisation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Localisation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Localisation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
