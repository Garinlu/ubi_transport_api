<?php


namespace App\Tests\Manager;


use App\Entity\Student;
use App\Manager\StudentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StudentTest extends KernelTestCase
{

    private static $firstname = "Lucas";
    private static $lastname = "Garin";
    /** @var EntityManagerInterface */
    private $objectManager;

    protected function setUp()
    {
        parent::setUp();
        $kernel = self::bootKernel();
        /** @var EntityManagerInterface $objectManager */
        $this->objectManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Adding a student
     * @return int
     */
    public function testAdd(): int
    {
        $studentManager = new StudentManager($this->objectManager);
        $student = new Student();
        $student->setFirstname(self::$firstname)
            ->setLastname(self::$lastname)
            ->setBirthDate(new \DateTime("16-11-1995"));
        $student = $studentManager->add($student);
        $this->assertEquals(self::$firstname, $student->getFirstname());
        $this->assertEquals(self::$lastname, $student->getLastname());
        $this->assertEquals(new \DateTime("16-11-1995"), $student->getBirthDate());
        $this->assertIsInt($student->getId());
        return $student->getId();
    }

    /**
     * @depends testAdd
     * Editing a student
     * @param int $id
     * @return int
     */
    public function testEdit(int $id): int
    {
        /** @var Student $student */
        $student = $this->objectManager->find(Student::class, $id);
        $studentManager = new StudentManager($this->objectManager);
        $newStudent = new Student();
        $newStudent->setFirstname("Jean")
            ->setLastname("Dupont")
            ->setBirthDate(new \DateTime("17-12-1996"));
        $student = $studentManager->edit($student, $newStudent);
        $this->assertEquals($id, $student->getId());
        $this->assertEquals("Jean", $student->getFirstname());
        $this->assertEquals("Dupont", $student->getLastname());
        $this->assertEquals(new \DateTime("17-12-1996"), $student->getBirthDate());
        return $student->getId();
    }

    /**
     * @depends testEdit
     * Editing a student
     * @param int $id
     */
    public function testDelete(int $id)
    {
        /** @var Student $student */
        $student = $this->objectManager->find(Student::class, $id);
        $studentManager = new StudentManager($this->objectManager);
        $returned = $studentManager->delete($student);
        $this->assertEquals(true, $returned);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->objectManager->close();
        $this->objectManager = null;
    }
}
