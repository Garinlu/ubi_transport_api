<?php


namespace App\Manager;


use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class StudentManager implements StudentManagerInterface
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
     * Save a student
     * @param Student $student
     * @return Student
     */
    public function add(Student $student): Student
    {
        $this->em->persist($student);
        $this->em->flush();
        return $student;
    }

    /**
     * Edit an existing student
     * @param Student $student
     * @param Student $newStudent
     * @return Student
     */
    public function edit(Student $student, Student $newStudent): Student
    {
        $student->setFirstname($newStudent->getFirstname())
            ->setLastname($newStudent->getLastname())
            ->setBirthDate($newStudent->getBirthDate());
        $this->em->persist($student);
        $this->em->flush();
        return $student;
    }

    /**
     * Edit an existing student
     * @param Student $student
     * @return bool
     */
    public function delete(Student $student): bool
    {
        $this->em->remove($student);
        $this->em->flush();
        return true;
    }

}
