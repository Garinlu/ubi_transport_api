<?php

namespace App\Repository;

use App\Entity\Grade;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Grade|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grade|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grade[]    findAll()
 * @method Grade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    /**
     * @param Student $student
     * @return float|null
     * @throws NonUniqueResultException
     */
    public function averageOfStudent(Student $student): ?float
    {
        return ($this->createQueryBuilder("g")
                ->where("g.student = :student")
                ->setParameter("student", $student)
                ->select("AVG(g.value) as note")
                ->groupBy("g.student")
                ->getQuery()->getOneOrNullResult() ?? ["note" => null])["note"];
    }

    /**
     * @return float|null
     * @throws NonUniqueResultException
     */
    public function averageOfClass(): ?float
    {
        return ($this->createQueryBuilder("g")
                ->select("AVG(g.value) as note")
                ->getQuery()->getOneOrNullResult() ?? ["note" => null])["note"];
    }
}
