<?php


namespace App\Controller;


use App\Entity\Student;
use App\Manager\StudentManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StudentController
 * @package App\Controller
 * @Route("/student")
 */
class StudentController extends BaseController
{

    /**
     * @Route("", methods={"PUT"})
     * @param Request $request
     * @param StudentManagerInterface $studentManager
     * @return Response
     */
    public function addAction(Request $request, StudentManagerInterface $studentManager): Response
    {
        /** @var Student $student */
        $student = $this->deserialize($request->getContent(), Student::class, 'json');
        return $this->createJsonResponse($studentManager->add($student), ["full_student"]);
    }

    /**
     * @Route("/{id}", methods={"POST"})
     * @param Request $request
     * @param Student $student
     * @param StudentManagerInterface $studentManager
     * @return Response
     */
    public function editAction(Request $request, Student $student, StudentManagerInterface $studentManager): Response
    {
        /** @var Student $newStudent */
        $newStudent = $this->deserialize($request->getContent(), Student::class, 'json');
        return $this->createJsonResponse($studentManager->edit($student, $newStudent), ["full_student"]);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @param Student $student
     * @param StudentManagerInterface $studentManager
     * @return Response
     */
    public function deleteAction(Student $student, StudentManagerInterface $studentManager): Response
    {
        return $this->createJsonResponse($studentManager->delete($student));
    }
}
