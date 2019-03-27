<?php


namespace Tests\AppBundle\Service;


use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

//        $this->truncateEntities([
//            Enclosure::class,
//            Security::class,
//            Dinosaur::class
//        ]);

        $this->truncateEntities();//this will loop over all entity objects and deleted them one by one, even delete them with order to avoid foreign key problems

    }


    public function testItBuildsEnclosureWithDefaultSpecification()
    {
//        self::bootKernel(); //best put this at setup()

        /** @var EnclosureBuilderService $enclosureBuilderService */
//        $enclosureBuilderService = self::$kernel->getContainer()
//            ->get('test.'.EnclosureBuilderService::class);


        $dinoFactory = $this->createMock(DinosaurFactory::class);
        $dinoFactory->expects($this->any())
            ->method('growFromSpecification')
//            ->willReturn(new Dinosaur());
            ->willReturnCallback(function($spec){
                return new Dinosaur();
            });

        $enclosureBuilderService = new EnclosureBuilderService(
            $this->getEntityManager(),
            $dinoFactory
        );

        $enclosureBuilderService->buildEnclosure();

        /** @var EntityManager $em */
//        $em = self::$kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();

        $em = $this->getEntityManager();

        $count = (int) $em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $count, 'Amount of security systems is not the same!');

        $count = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $count, 'Amount of dinosaurs is not the same!');
    }

//    private function truncateEntities(array $entities)
//    {
//        $connection = $this->getEntityManager()->getConnection();
//        $databasePlatform = $connection->getDatabasePlatform();
//
//        if ($databasePlatform->supportsForeignKeyConstraints()) {
//            $connection->query('SET FOREIGN_KEY_CHECKS=0');
//        }
//
//        foreach ($entities as $entity) {
//            $query = $databasePlatform->getTruncateTableSQL(
//                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
//            );
//
//            $connection->executeUpdate($query);
//        }
//
//        if ($databasePlatform->supportsForeignKeyConstraints()) {
//            $connection->query('SET FOREIGN_KEY_CHECKS=1');
//        }
//    }

    //another way -- using data-fixture way to clear database
    private function truncateEntities()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}