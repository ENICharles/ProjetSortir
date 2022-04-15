<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\State;
use App\Entity\User;
use ContainerEv5lKh6\get_Maker_AutoCommand_MakeDockerDatabase_LazyService;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;


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
     * retourne tous les évènement de moins d'un mois non terminé
     * @return float|int|mixed|string
     */
    public function findAll1M(Campus $campus)
    {
        $sr = $this->getEntityManager()->getRepository(State::class);

        /* recherche de l'état passée */
        $state = $sr->findOneBy(['id'=>'5']);

        dump($state);
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

        dump($state);
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
     * @return float|int|mixed|string
     */
    public function findbyFilter(Campus   $campus,
                                 DateTime $debut,
                                 DateTime $fin,
                                 string   $motClef,
                                 string  $manage,
                                 string  $inscrit,
                                 string  $notInscrit,
                                 string  $older,
                                 User     $usr)
    {
        $sr = $this->getEntityManager()->getRepository(State::class);

        /* recherche de l'état passée */
        $state = $sr->findOneBy(['id'=>'5']);

        $rq = $this->createQueryBuilder('e');

        $rq->andWhere('e.campus    =  :cp');
        $rq->andWhere('e.dateStart >= :limite');
        $rq->andWhere('e.dateStart >= :debut');
        $rq->andWhere('e.dateStart <= :fin');
        $rq->andWhere('e.name    LIKE :mc');
        $rq->andWhere('e.state     != :state');

//        $rq->andWhere('e.organisator = :mng');
//        $rq->andWhere(':ins MEMBER OF e.users');
//        $rq->andWhere('e.dateStart <= :noins');

        $rq->setParameter('limite', (new DateTime())->modify('-1 month'));
        $rq->setParameter('cp',     $campus);
        $rq->setParameter('debut',  $debut);
        $rq->setParameter('fin',    $fin);
        $rq->setParameter('mc',     $motClef);

//        $rq->setParameter('mng',    $usr);
//        $rq->setParameter('ins',    $inscrit);
//        $rq->setParameter('noins',  $notInscrit);

        $rq->setParameter('state',  $state);


        return $rq->orderBy('e.dateStart', 'ASC')->getQuery()->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
