<?php


namespace App\Tests\Manager;


use App\Entity\Grade;
use App\Entity\Student;
use App\Manager\GradeManager;
use App\Tests\TestUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GradeTest extends KernelTestCase
{

    /** @var EntityManagerInterface */
    protected static $objectManager;
    /** @var Student[] */
    protected static $students;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $kernel = self::bootKernel();
        /** @var EntityManagerInterface $objectManager */
        self::$objectManager = $kernel->getContainer()->get('doctrine')->getManager();
        self::$students = TestUtils::createStudents(self::$objectManager);
    }

    /**
     * Adding a student
     */
    public function testAdd(): array
    {
        $gradeManager = new GradeManager(self::$objectManager);
        $notes = [];
        foreach (self::$students as $student) {
            $currentNote = [rand(0, 20), rand(0, 20), rand(0, 20)];
            $notes[] = $currentNote;
            foreach ($currentNote as $note) {
                $grade = new Grade();
                $grade->setSubject("IT")
                    ->setValue($note);
                $grade = $gradeManager->add($student, $grade);
                $this->assertEquals($note, $grade->getValue());
                $this->assertEquals("IT", $grade->getSubject());
                $this->assertEquals($student->getId(), $grade->getStudent()->getId());
            }
        }

        return $notes;
    }

    /**
     * @depends testAdd
     * Get grades average of a student
     * @param array $notes
     * @return int[][]
     * @throws NonUniqueResultException
     */
    public function testStudentAverage(array $notes): array
    {
        $gradeManager = new GradeManager(self::$objectManager);
        foreach (self::$students as $index => $student) {
            $average = $gradeManager->getStudentAverage($student);
            $actualAverage = round(array_sum($notes[$index]) / sizeof($notes[$index]), 2);
            $this->assertEquals($actualAverage, $average);
        }
        return $notes;
    }

    /**
     * @depends testStudentAverage
     * Get grades average of a student
     * @param array $notes
     * @throws NonUniqueResultException
     */
    public function testClassAverage(array $notes)
    {
        $gradeManager = new GradeManager(self::$objectManager);
        $average = $gradeManager->getClassAverage();
        $numberOfGrades = 0;
        $notesAverage = array_map(function ($notes) use (&$numberOfGrades) {
            $numberOfGrades += sizeof($notes);
            return array_sum($notes);
        }, $notes);
        $actualAverage = round(array_sum($notesAverage) / $numberOfGrades, 2);
        $this->assertEquals($actualAverage, $average);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::$objectManager->close();
        self::$objectManager = null;
    }
}
