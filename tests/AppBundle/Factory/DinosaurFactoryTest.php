<?php


namespace Tests\AppBundle\Factory;


use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    /**
     * @var MockObject
     */
    private $lengthDeterminator;

    public function setUp() :void
    {
        //test rule, if the object you need is a simple model object, just create it, if it's a service, then mock it
//        $this->factory = new DinosaurFactory(new DinosaurLengthDeterminator());//base on the rule, will not pass the real service, using mock as follow

        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($this->lengthDeterminator);
    }

    public function testItGrowsAVelociraptor()
    {
//        $factory = new DinosaurFactory();
//        $dinosaur = $factory->growVelociraptor(5);
        $dinosaur = $this->factory->growVelociraptor(5); //phpunit have a magic, if you have a method that's exactly called setUp, PHPUnit will automatically call it before each test.

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
//        $this->assertInternalType('string', $dinosaur->getGenus());
        $this->assertIsString($dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
    }

    public function testItGrowsATriceratops()
    {
        $this->markTestIncomplete('Waiting for confirmation from GenLab');
    }

    public function testItGrowsABabyVelociraptor()
    {
        if(!class_exists('Nanny')){
            $this->markTestSkipped('There is nobody to watch the baby');
        }
        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength());
    }

    /**
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromASpecification(string $spec,
//                                                           bool $expectedIsLarge,
                                                           bool $expectedIsCarnivorous)
    {
        $this->lengthDeterminator->expects($this->once())
            ->method('getLengthFromSpecification')
            ->with($spec)
            ->willReturn(20);//this will strict the method get back to 20
        $dinosaur = $this->factory->growFromSpecification($spec);


        //common the length test, we don't need test the length, no longer has any logic related to lengths, that is done in DinosaureLengthDeterminator
//        if ($expectedIsLarge){
//            $this->assertGreaterThanOrEqual(Dinosaur::LARGE,$dinosaur->getLength());
//        }else{
//            $this->assertLessThan(Dinosaur::LARGE,$dinosaur->getLength());
//        }

        $this->assertSame($expectedIsCarnivorous, $dinosaur->IsCarnivorous(),'Diets do not match');
        $this->assertSame(20, $dinosaur->getLength());
    }

    public function getSpecificationTests()
    {
        return [
            //specification, is large, is carnivorous
            ['large carnivorous dinosaur',
//                true,
                true ], //first true for large, second for carnivorous
            'default response' => ['give me all the cookies',
//                false,
                false],
            ['large herbivore',
//                true,
                false],
        ];
    }

//    /**
//     * @dataProvider getHugeDinosaurSpecTests
//     */
//    public function testItGrowsAHugeDinosaur(string $specification)
//    {
//        $dinosaur = $this->factory->growFromSpecification($specification);
//        $this->assertGreaterThanOrEqual(Dinosaur::HUGE, $dinosaur->getLength());
//    }
//
//    public function getHugeDinosaurSpecTests()
//    {
//        return[
//            ['huge dinosaur'],
//            ['huge dino'],
//            ['huge'],
//            ['OMG'],
//            ['😱'],
//        ];
//    }
}