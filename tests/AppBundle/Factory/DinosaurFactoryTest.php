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
}