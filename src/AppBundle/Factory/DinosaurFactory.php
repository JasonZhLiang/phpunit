<?php


namespace AppBundle\Factory;


use AppBundle\Entity\Dinosaur;

class DinosaurFactory
{
    public function growVelociraptor(int $length): Dinosaur
    {
//        $dinosaur = new Dinosaur('Velociraptor', true);
//
//        $dinosaur->setLength($length);
//
//        return $dinosaur;
        return $this->createDinosaur('Velociraptor', true, 5);
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);
        return $dinosaur;
    }
}