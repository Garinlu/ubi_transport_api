<?php


namespace App\Controller;


use App\Entity\Grade;
use App\Entity\Student;
use App\Manager\GradeManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GradeController
 * @package App\Controller
 * @Route("/grade")
 */
class GradeController extends BaseController
{
    /**
     * @Route("/student/{id}", methods={"PUT"})
     * @param Request $request
     * @param Student $student
     * @param GradeManagerInterface $gradeManager
     * @return Response
     */
    public function addAction(Request $request, Student $student, GradeManagerInterface $gradeManager): Response
    {
        /** @var Grade $grade */
        $grade = $this->deserialize($request->getContent(), Grade::class, 'json');
        return $this->createJsonResponse($gradeManager->add($student, $grade), ["full_grade", "small_student"]);
    }

    /**
     * @Route("/student/{id}/average", methods={"GET"})
     * @param Student $student
     * @param GradeManagerInterface $gradeManager
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getStudentAverageAction(Student $student, GradeManagerInterface $gradeManager): Response
    {
        return $this->createJsonResponse($gradeManager->getStudentAverage($student));
    }

    /**
     * @Route("/class/average", methods={"GET"})
     * @param GradeManagerInterface $gradeManager
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getClassAverageAction(GradeManagerInterface $gradeManager): Response
    {
        return $this->createJsonResponse($gradeManager->getClassAverage());
    }

}
