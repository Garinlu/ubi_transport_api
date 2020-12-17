<?php


namespace App\Tests\Controller;


use DateTime;
use Exception;

class StudentControllerTest extends AbstractControllerTestService
{

    private static $firstname = "Lucas";
    private static $lastname = "Garin";

    /**
     * Adding a student
     * @return int
     * @throws Exception
     */
    public function testAdd(): int
    {
        $this->client->request('PUT', '/student', [], [], [],
            json_encode([
                "firstname" => self::$firstname,
                "lastname" => self::$lastname,
                "birthDate" => (new DateTime("16-11-1995"))->format('c')
            ])
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $student = json_decode($response->getContent(), true);
        $this->assertEquals(self::$firstname, $student["firstname"]);
        $this->assertEquals(self::$lastname, $student["lastname"]);
        $this->assertArrayHasKey("id", $student);
        $this->assertIsInt($student["id"]);
        $this->assertArrayHasKey("birthDate", $student);
        $birthDate = new DateTime($student["birthDate"]);
        $this->assertEquals("16", $birthDate->format('d'));
        $this->assertEquals("11", $birthDate->format('m'));
        $this->assertEquals("1995", $birthDate->format('Y'));
        return $student["id"];
    }

    /**
     * @depends testAdd
     * Editing a student
     * @param int $id
     * @return int
     * @throws Exception
     */
    public function testEdit(int $id): int
    {
        $this->client->request('POST', '/student/' . $id, [], [], [],
            json_encode([
                "firstname" => "Jean",
                "lastname" => "Dupont",
                "birthDate" => (new DateTime("17-12-1996"))->format('c')
            ])
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $student = json_decode($response->getContent(), true);
        $this->assertEquals($id, $student["id"]);
        $this->assertEquals("Jean", $student["firstname"]);
        $this->assertEquals("Dupont", $student["lastname"]);
        $this->assertArrayHasKey("birthDate", $student);
        $birthDate = new DateTime($student["birthDate"]);
        $this->assertEquals("17", $birthDate->format('d'));
        $this->assertEquals("12", $birthDate->format('m'));
        $this->assertEquals("1996", $birthDate->format('Y'));
        return $id;
    }

    /**
     * @depends testEdit
     * Editing a student
     * @param int $id
     */
    public function testDelete(int $id)
    {
        $this->client->request('DELETE', '/student/' . $id);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
