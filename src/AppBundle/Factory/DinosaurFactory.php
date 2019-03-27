<?php


namespace AppBundle\Factory;


use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterminator;

class DinosaurFactory
{
    private $determinator;

    public function __construct(DinosaurLengthDeterminator $determinator)
    {

        $this->determinator = $determinator;
    }

    public function growVelociraptor(int $length): Dinosaur
    {
//        $dinosaur = new Dinosaur('Velociraptor', true);
//
//        $dinosaur->setLength($length);
//
//        return $dinosaur;
        return $this->createDinosaur('Velociraptor', true, 5);
    }

    public function growFromSpecification(string $specification): Dinosaur
    {
        //defaults
        $codeName = 'InG-' . random_int(1,99999);
//        $length = random_int(1, Dinosaur::LARGE -1);
//        $length = $this->getLengthFromSpecification($specification);
        $length = $this->determinator->getLengthFromSpecification($specification);
//        $length =1;//if by accidently hardcode the length, the test will detect it and fail the test, because mocked method will return 20
        $isCarnivorous = false;

//        if (stripos($specification, 'huge') !== false) {
//            $length = random_int(Dinosaur::HUGE, 100);
//        }
//
//        if (stripos($specification, 'OMG') !== false) {
//            $length = random_int(Dinosaur::HUGE, 100);
//        }
//
//        if (stripos($specification, '😱') !== false) {
//            $length = random_int(Dinosaur::HUGE, 100);
//        }
//
//        if (stripos($specification, 'large') !== false) {
//            $length = random_int(Dinosaur::LARGE, Dinosaur::HUGE - 1);
//        }


        if (stripos($specification, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        $dinosaur = $this->createDinosaur($codeName, $isCarnivorous, $length);
        return $dinosaur;
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);
        return $dinosaur;
    }

//    private function getLengthFromSpecification(string $specification): int
//    {
//        $availableLengths = [
//            'huge' => ['min' => Dinosaur::HUGE, 'max' => 100],
//            'omg' => ['min' => Dinosaur::HUGE, 'max' => 100],
//            '😱' => ['min' => Dinosaur::HUGE, 'max' => 100],
//            'large' => ['min' => Dinosaur::LARGE, 'max' => Dinosaur::HUGE - 1],
//        ];
//        $minLength = 1;
//        $maxLength = Dinosaur::LARGE - 1;
//
//        foreach (explode(' ', $specification) as $keyword) {
//            $keyword = strtolower($keyword);
//
//            if (array_key_exists($keyword, $availableLengths)) {
//                $minLength = $availableLengths[$keyword]['min'];
//                $maxLength = $availableLengths[$keyword]['max'];
//
//                break;
//            }
//        }
//
//        return random_int($minLength, $maxLength);
//    }
}