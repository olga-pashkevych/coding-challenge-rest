<?php

namespace App\Tests\Controller\API\V1;

use App\Entity\Advisor;
use App\Entity\AdvisorLanguages;
use App\Repository\AdvisorRepository;
use App\Request\FilterAdvisor;
use App\Request\OrderByAdvisor;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdvisorControllerTest extends WebTestCase
{
    private Prophet $prophet;

    private $client;

    public function testItSuccessfullyCreates()
    {
        $advisor = $this->setAdvisorWithoutId();

        $repo = $this->prophet->prophesize(AdvisorRepository::class);
        $repo->saveAdvisor($advisor)->will(function ($args) {
            return $args[0]->setId(1);
        })->shouldBeCalled();

        $this->client->getContainer()->set(AdvisorRepository::class, $repo->reveal());
        $this->client->request(
            'POST',
            'http://127.0.0.1:8000/api/v1/advisors',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<JSON
{
    "name": "test",
    "description": "description",
    "availability": 0,
    "pricePerMinute": 11.43,
    "languages": [
        "de",
        "en"
    ]
}
JSON
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            '{"id":1,"name":"test","description":"description","availability":false,"price_per_minute":11.43,"languages":["de","en"]}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testItNotSuccessfullyCreates()
    {
        $repo = $this->prophet->prophesize(AdvisorRepository::class);

        $this->client->getContainer()->set(AdvisorRepository::class, $repo->reveal());
        $this->client->request(
            'POST',
            'http://127.0.0.1:8000/api/v1/advisors',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<JSON
{
    "name": "",
    "description": "description",
    "availability": 0,
    "pricePerMinute": 11.43,
    "languages": [
        "de",
        "en"
    ]
}
JSON
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(
            '"Name should not be blank."',
            $this->client->getResponse()->getContent()
        );
    }

    public function testItSuccessfullyUpdates()
    {
        $advisor = $this->setAdvisorWithId();

        $repo = $this->prophet->prophesize(AdvisorRepository::class);
        $repo->findOneBy(['id' => 1])->willReturn($advisor);
        $repo->saveAdvisor($advisor)->will(function ($args) {
            return $args[0];
        })->ShouldBeCalledOnce();

        $this->client->getContainer()->set(AdvisorRepository::class, $repo->reveal());
        $this->client->request(
            'PUT',
            'http://127.0.0.1:8000/api/v1/advisors/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<JSON
{
    "name": "test",
    "description": "description",
    "availability": 0,
    "pricePerMinute": 11.43,
    "languages": [
        "de",
        "en"
    ]
}
JSON
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            '{"id":1,"name":"test","description":"description","availability":false,"price_per_minute":11.43,"languages":["de","en"]}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testIsNotSuccessfullyUpdates()
    {
        $repo = $this->prophet->prophesize(AdvisorRepository::class);
        $repo->findOneBy(['id' => 222])->ShouldBeCalledOnce();
        $this->client->getContainer()->set(AdvisorRepository::class, $repo->reveal());

        $this->client->request(
            'PUT',
            'http://127.0.0.1:8000/api/v1/advisors/222',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<JSON
{
    "name": "test",
    "description": "description",
    "availability": 0,
    "pricePerMinute": 11.43,
    "languages": [
        "de",
        "en"
    ]
}
JSON
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(
            '"Advisor with id: 222 doesn\u0027t exist."',
            $this->client->getResponse()->getContent()
        );
    }

    public function testItSuccessfullyDeletes()
    {
        $advisor = $this->setAdvisorWithId();

        $repo = $this->prophet->prophesize(AdvisorRepository::class);
        $repo->findOneBy(['id' => 1])->willReturn($advisor);
        $repo->removeAdvisor($advisor)->ShouldBeCalledOnce();
        $this->client->getContainer()->set(AdvisorRepository::class, $repo->reveal());

        $this->client->request(
            'DELETE',
            'http://127.0.0.1:8000/api/v1/advisors/1',
            [],
            [],
            [],
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            '{"message":"Advisor has been deleted"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testIsSuccessfullyGetAdvisor()
    {
        $advisor = $this->setAdvisorWithId();

        $repo = $this->prophet->prophesize(AdvisorRepository::class);
        $repo->findOneBy(['id' => 1])->willReturn($advisor);
        $this->client->getContainer()->set(AdvisorRepository::class, $repo->reveal());

        $this->client->request(
            'GET',
            'http://127.0.0.1:8000/api/v1/advisors/1',
            [],
            [],
            [],
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            '{"id":1,"name":"test","description":"description","availability":false,"price_per_minute":11.43,"languages":["de","en"]}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testIsSuccessfullyGetAdvisors()
    {
        $advisor1 = $this->setAdvisorWithId();
        $advisor1->setId(2);
        $advisor2 = $this->setAdvisorWithId();

        $filterAdvisor = new FilterAdvisor('name', 'de');
        $orderByAdvisor = new OrderByAdvisor('price', 'asc');

        $repo = $this->prophet->prophesize(AdvisorRepository::class);
        $repo->getAdvisorsByFilter($filterAdvisor, $orderByAdvisor)->shouldBeCalled()->willReturn([$advisor1, $advisor2]);
        $this->client->getContainer()->set(AdvisorRepository::class, $repo->reveal());

        $this->client->request(
            'GET',
            'http://127.0.0.1:8000/api/v1/advisors?name=name&language=de&price=asc',
            [],
            [],
            [],
        );

        $this->prophet->checkPredictions();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            '[{"id":2,"name":"test","description":"description","availability":false,"price_per_minute":11.43,"languages":["de","en"]},{"id":1,"name":"test","description":"description","availability":false,"price_per_minute":11.43,"languages":["de","en"]}]',
            $this->client->getResponse()->getContent()
        );
    }

    protected function setUp(): void
    {
        $this->prophet = new Prophet;

        self::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    protected function setAdvisorWithId(): Advisor
    {
        $advisor = new Advisor();
        $advisor->setId(1)
            ->setName("test")
            ->setDescription("description")
            ->setAvailability(0)
            ->setPricePerMinute(11.43);

        foreach (["de", "en"] as $language) {
            $advisorLanguage = new AdvisorLanguages();
            $advisorLanguage
                ->setAdvisor($advisor)
                ->setLanguageCode($language);
            $advisor->addLanguage($advisorLanguage);
        }

        return $advisor;
    }

    protected function setAdvisorWithoutId(): Advisor
    {
        $advisor = new Advisor();
        $advisor
            ->setName("test")
            ->setDescription("description")
            ->setAvailability(0)
            ->setPricePerMinute(11.43);

        foreach (["de", "en"] as $language) {
            $advisorLanguage = new AdvisorLanguages();
            $advisorLanguage
                ->setAdvisor($advisor)
                ->setLanguageCode($language);
            $advisor->addLanguage($advisorLanguage);
        }

        return $advisor;
    }
}