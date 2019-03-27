<?php


namespace Tests\AppBundle\Controller;


use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnTheHomepage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ]);//if you use this data for every test, move this load method to setup is a great choice

        //when you set cache_sqlite_db: true in config_test.yml file, each time you call the load fixtures will reuse this cache file


//        self::$kernel->getContainer()->get('doctrine')->getManager();//if you want to get EntityManager

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client); //this is a shortcut $client->getResponse()->getStatusCode()

        $table = $crawler->filter('.table-enclosures');

        $this->assertCount(3,$table->filter('tbody tr'));
    }

    public function testThatThereIsAnAlarmButtonWithoutSecurity()
    {
        $fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ])->getReferenceRepository();

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        dump($client->getResponse()->getContent());

        $enclosure = $fixtures->getReference('carnivorous-enclosure');

        $selector = sprintf('#enclosure-%s .button-alarm', $enclosure->getId());//we expected css which id is 'enclosure-xx' has this class alarm button

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
    }

    public function testItGrowsADinosaurFromSpecification()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ]);

        $client = $this->makeClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('Grow dinosaur')->form();
        $form['enclosure']->select(3);
        $form['specification']->setValue('large herbivore');

        $client->submit($form);

        $this->assertContains(
            'Grew a large herbivore in enclosure #3',
            $client->getResponse()->getContent()
        );
    }

}