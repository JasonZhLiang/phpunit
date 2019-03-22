<?php


namespace Tests\AppBundle\Factory;


use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    public function setUp() :void
    {
        $this->factory = new DinosaurFactory();
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
    public function testItGrowsADinosaurFromASpecification(string $spec, bool $expectedIsLarge, bool $expectedIsCarnivorous)
    {
        $dinosaur = $this->factory->growFromSpecification($spec);
        if ($expectedIsLarge){
            $this->assertGreaterThanOrEqual(Dinosaur::LARGE,$dinosaur->getLength());
        }else{
            $this->assertLessThan(Dinosaur::LARGE,$dinosaur->getLength());
        }

        $this->assertSame($expectedIsCarnivorous, $dinosaur->IsCarnivorous(),'Diets do not match');
    }

    public function getSpecificationTests()
    {
        return [
            //specification, is large, is carnivorous
            ['large carnivorous dinosaur', true, true ], //first true for large, second for carnivorous
            'default response' => ['give me all the cookies', false, false],
            ['large herbivore', true, false],
        ];
    }

    /**
     * @dataProvider getHugeDinosaurSpecTests
     */
    public function testItGrowsAHugeDinosaur(string $specification)
    {
        $dinosaur = $this->factory->growFromSpecification($specification);
        $this->assertGreaterThanOrEqual(Dinosaur::HUGE, $dinosaur->getLength());
    }

    public function getHugeDinosaurSpecTests()
    {
        return[
            ['huge dinosaur'],
            ['huge dino'],
            ['huge'],
            ['OMG'],
            ['😱'],
        ];
    }
}