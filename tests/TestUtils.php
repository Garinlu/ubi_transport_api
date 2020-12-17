<?php


namespace App\Tests;


use App\Entity\Student;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class TestUtils
{

    /**
     * Save students in database
     * @param EntityManagerInterface $em
     * @return mixed
     */
    public static function createStudents(EntityManagerInterface $em): array
    {
        $data = [
            [
                "firstname" => "Lucas",
                "lastname" => "Garin",
                "birthdate" => new DateTime("16-11-1995")
            ],
            [
                "firstname" => "Jean",
                "lastname" => "Dupont",
                "birthdate" => new DateTime("22-7-1990")
            ],
            [
                "firstname" => "Zinedine",
                "lastname" => "Zidane",
                "birthdate" => new DateTime("23-6-1972")
            ],
        ];
        return array_map(function ($studentDate) use ($em) {
            $student = new Student();
            $student->setFirstname($studentDate["firstname"])
                ->setLastname($studentDate["lastname"])
                ->setBirthDate($studentDate["birthdate"]);
            $em->persist($student);
            $em->flush();
            return $student;
        }, $data);
    }

    public static function clearTables(EntityManagerInterface $em)
    {
        foreach ($em->getRepository(Student::class)->findAll() as $student) {
            $em->remove($student);
        }
        $em->flush();
    }
}
