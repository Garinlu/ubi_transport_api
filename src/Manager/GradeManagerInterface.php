<?php


namespace App\Manager;


use App\Entity\Grade;
use App\Entity\Student;
use Doctrine\ORM\NonUniqueResultException;

interface GradeManagerInterface
{
    /**
     * Add a grade to a student
     * @param Student $student
     * @param Grade $grade
     * @return Grade
     */
    public function add(Student $student, Grade $grade): Grade;

    /**
     * Get grade average of a student
     * @param Student $student
     * @return float|null
     * @throws NonUniqueResultException
     */
    public function getStudentAverage(Student $student): ?float;

    /**
     * Get grade average of the class
     * @return float
     * @throws NonUniqueResultException
     */
    public function getClassAverage(): ?float;
}
