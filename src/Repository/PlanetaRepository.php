<?php

namespace App\Repository;

use App\Entity\Planeta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Planeta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planeta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planeta[]    findAll()
 * @method Planeta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Planeta::class);
        $this->manager = $manager;
    }

    public function savePlanet($name, $description, $position)
    {
        $newPlanet = new Planeta();

        $newPlanet
            ->setName($name)
            ->setDescription($description)
            ->setPosition($position)
            ->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

        $this->manager->persist($newPlanet);
        $this->manager->flush();
    }

    public function updatePlanet(Planeta $planet): Planeta
    {
        $this->manager->persist($planet);
        $this->manager->flush();

        return $planet;
    }

    public function removePlanet(Planeta $planet)
    {
        $this->manager->remove($planet);
        $this->manager->flush();
    }
    // /**
    //  * @return Planeta[] Returns an array of Planeta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Planeta
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
