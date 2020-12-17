<?php


namespace App\Manager;


use App\Entity\Grade;
use App\Entity\Student;
use App\Repository\GradeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class GradeManager implements GradeManagerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Add a grade to a student
     * @param Student $student
     * @param Grade $grade
     * @return Grade
     */
    public function add(Student $student, Grade $grade): Grade
    {
        $student->addGrade($grade);
        $this->em->persist($grade);
        $this->em->flush();
        return $grade;
    }

    /**
     * Get grade average of a student
     * @param Student $student
     * @return float|null
     * @throws NonUniqueResultException
     */
    public function getStudentAverage(Student $student): ?float
    {
        /** @var GradeRepository $gradeRepo */
        $gradeRepo = $this->em->getRepository(Grade::class);
        $avg = $gradeRepo->averageOfStudent($student);
        return $avg ? round($avg, 2) : null;
    }

    /**
     * Get grade average of the class
     * @return float
     * @throws NonUniqueResultException
     */
    public function getClassAverage(): ?float
    {
        /** @var GradeRepository $gradeRepo */
        $gradeRepo = $this->em->getRepository(Grade::class);
        $avg = $gradeRepo->averageOfClass();
        return $avg ? round($avg, 2) : null;
    }

}
