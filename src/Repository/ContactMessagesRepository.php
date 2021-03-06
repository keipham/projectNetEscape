<?php

namespace App\Repository;

use App\Entity\ContactMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactMessages[]    findAll()
 * @method ContactMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactMessagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactMessages::class);
    }

    public function findContactbyId($value): ?ContactMessages
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    

    // /**
    //  * @return ContactMessages[] Returns an array of ContactMessages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
   
    
}
