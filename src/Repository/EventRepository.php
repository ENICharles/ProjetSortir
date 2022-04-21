<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\State;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Event $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush)
        {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Event $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * retourne tous les évènements de moins d'un mois non terminé pour un campus
     * @return float|int|mixed|string
     */
    public function findAll1M(Campus $campus)
    {
        $sr = $this->getEntityManager()->getRepository(State::class);

        /* recherche de l'état passée */
        $state = $sr->findOneBy(['id'=>'5']);

        return $this->createQueryBuilder('e')

            ->andWhere('e.dateStart > :debut')
            ->andWhere('e.campus = :cp')
            ->andWhere('e.state != :state')

            ->setParameter('debut', (new DateTime())->modify('-1 month'))
            ->setParameter('cp', $campus)
            ->setParameter('state', $state)

            ->orderBy('e.dateStart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * retourne tous les évènement passés(terminé)
     * @return float|int|mixed|string
     */
    public function findOlder(Campus $campus)
    {
        $sr = $this->getEntityManager()->getRepository(State::class);

        /* recherche de l'état passée */
        $state = $sr->findOneBy(['id'=>'5']);

        return $this->createQueryBuilder('e')

            ->andWhere('e.dateStart > :debut')
            ->andWhere('e.campus = :cp')
            ->andWhere('e.state = :state')

            ->setParameter('debut', (new DateTime())->modify('-1 month'))
            ->setParameter('cp', $campus)
            ->setParameter('state', $state)

            ->orderBy('e.dateStart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * retourne tous les évènement compris dans une plage de date
     * @return float|int|mixed|string
     */
    public function findbyDate(Campus $campus,DateTime $debut,DateTime $fin)
    {
        $sr = $this->getEntityManager()->getRepository(State::class);

        /* recherche de l'état passée */
        $state = $sr->findOneBy(['id'=>'5']);

        return $this->createQueryBuilder('e')

            ->andWhere('e.dateStart >= :limite')
            ->andWhere('e.dateStart >= :debut')
            ->andWhere('e.dateStart <= :fin')
            ->andWhere('e.campus = :cp')
            ->andWhere('e.state != :state')

            ->setParameter('limite', (new DateTime())->modify('-1 month'))
            ->setParameter('debut',  $debut)
            ->setParameter('fin',  $fin)
            ->setParameter('cp',     $campus)
            ->setParameter('state',  $state)

            ->orderBy('e.dateStart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * <h1>Retourne tous les évènements compris dans une plage de date</h1>
     * @param Campus $campus
     * @param DateTime $debut
     * @param DateTime $fin
     * @param string $motClef
     * @param User $isManager
     * @param User $inscrit
     * @param User $notInscrit
     * @param bool $older
     * @return float|int|mixed|string
     */
    public function findbyFilter(Campus   $campus,
                                 \DateTimeInterface $debut,
                                 \DateTimeInterface $fin,
                                 string   $motClef      ,
                                 bool     $isManager    ,
                                 bool     $inscrit      ,
                                 bool     $notInscrit   ,
                                 bool     $older        ,
                                 User     $user)
    {


        /* recherche de l'état passée */
        $state = ($this->getEntityManager()->getRepository(State::class))->findOneBy(['id'=>'5']);

        $rq = $this->createQueryBuilder('e');

        $rq->andWhere('e.dateStart >= :limite');
        $rq->setParameter('limite', (new DateTime())->modify('-1 month'));

        if(!$older)
        {
            $rq->andWhere('e.state     != :state');
        }
        else
        {
            $rq->andWhere('e.state     = :state');
        }

        $rq->setParameter('state', $state);

        $rq->andWhere('e.campus = :cp');
        $rq->setParameter('cp', $campus);

        $rq->andWhere('e.dateStart >= :debut');
        $rq->andWhere('e.dateStart <= :fin');
        $rq->setParameter('debut', $debut);
        $rq->setParameter('fin', $fin);

        if(($motClef != null) && ($motClef != ' '))
        {
            $rq->andWhere('e.name LIKE :mc');
            $rq->setParameter('mc', '%' . $motClef . '%');
        }

        if($isManager == true)
        {
            $rq->andWhere('e.organisator = :mng');
            $rq->setParameter('mng', $user);
        }

        if(($inscrit == true) && ($notInscrit == false))
        {
            $rq->andWhere(':ins MEMBER OF e.users');
            $rq->setParameter('ins', $user);
        }
        elseif(($inscrit == false) && ($notInscrit == true))
        {
            $rq->andWhere(':noins NOT MEMBER OF e.users');
            $rq->setParameter('noins', $user);
        }
        elseif(($inscrit == true) && ($notInscrit == true))
        {
            $rq->andWhere(':ins MEMBER OF e.users');
            $rq->setParameter('ins', $user);
            $rq->orWhere(':noins NOT MEMBER OF e.users');
            $rq->setParameter('noins', $user);
        }

        return $rq->orderBy('e.dateStart', 'ASC')->getQuery()->getResult();
    }
}
