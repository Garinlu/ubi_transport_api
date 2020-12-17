<?php


namespace App\Tests\Controller;


use App\Tests\TestUtils;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class GradeControllerTest extends AbstractControllerTestService
{

    /** @var EntityManagerInterface */
    private $objectManager;

    public function setUp()
    {
        parent::setUp();
        $kernel = self::bootKernel();
        /** @var EntityManagerInterface $objectManager */
        $this->objectManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Adding grades
     * @return int[][]
     * @throws Exception
     */
    public function testAdd(): array
    {
        $students = TestUtils::createStudents($this->objectManager);
        $notes = [];
        foreach ($students as $student) {
            $currentNote = [rand(0, 20), rand(0, 20), rand(0, 20)];
            $notes[] = $currentNote;
            foreach ($currentNote as $note) {
                $this->client->request('PUT', '/grade/student/' . $student->getId(), [], [], [],
                    json_encode([
                        "subject" => "IT",
                        "value" => $note
                    ])
                );
                $response = $this->client->getResponse();
                $this->assertEquals(200, $response->getStatusCode());
                $grade = json_decode($response->getContent(), true);
                $this->assertEquals("IT", $grade["subject"]);
                $this->assertEquals($note, $grade["value"]);
                $this->assertArrayHasKey("student", $grade);
                $this->assertArrayHasKey("id", $grade["student"]);
                $this->assertEquals($student->getId(), $grade["student"]["id"]);
            }
        }
        return [$students, $notes];
    }

    /**
     * @depends testAdd
     * Get grades average of a student
     * @param array $data
     * @return int[][]
     */
    public function testStudentAverage(array $data): array
    {
        foreach ($data[0] as $index => $student) {
            $this->client->request('GET', '/grade/student/' . $student->getId() . '/average');
            $response = $this->client->getResponse();
            $this->assertEquals(200, $response->getStatusCode());
            $average = round(array_sum($data[1][$index]) / sizeof($data[1][$index]), 2);
            $this->assertEquals($average, $response->getContent());
        }
        return $data;
    }

    /**
     * @depends testStudentAverage
     * Get grades average of a student
     * @param array $data
     */
    public function testClassAverage(array $data)
    {
        $this->client->request('GET', '/grade/class/average');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $numberOfGrades = 0;
        $notesAverage = array_map(function ($notes) use (&$numberOfGrades) {
            $numberOfGrades += sizeof($notes);
            return array_sum($notes);
        }, $data[1]);
        $average = round(array_sum($notesAverage) / $numberOfGrades, 2);
        $this->assertEquals($average, $response->getContent());
        TestUtils::clearTables($this->objectManager);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->objectManager->close();
        $this->objectManager = null;
    }
}
