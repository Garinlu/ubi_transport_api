<?php


namespace App\Manager;


use App\Entity\Student;

interface StudentManagerInterface
{
    /**
     * Save a student
     * @param Student $student
     * @return Student
     */
    public function add(Student $student): Student;

    /**
     * Edit an existing student
     * @param Student $student
     * @param Student $newStudent
     * @return Student
     */
    public function edit(Student $student, Student $newStudent): Student;

    /**
     * Edit an existing student
     * @param Student $student
     * @return bool
     */
    public function delete(Student $student): bool;
}
