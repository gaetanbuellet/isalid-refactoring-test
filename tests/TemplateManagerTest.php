<?php

use App\Context\ApplicationContext;
use App\Entity\Quote;
use App\Entity\Template;
use App\Entity\User;
use App\Repository\DestinationRepository;
use App\Repository\QuoteRepository;
use App\Repository\SiteRepository;
use App\TemplateManager;

class TemplateManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ApplicationContext|PHPUnit_Framework_MockObject_MockObject
     */
    private $applicationContext;

    /**
     * @var QuoteRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $quoteRepository;

    /**
     * @var SiteRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $siteRepository;

    /**
     * @var DestinationRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $destinationRepository;

    /**
     * Init the mocks
     */
    public function setUp()
    {
        $this->applicationContext = $this->createMock(ApplicationContext::class);
        $this->quoteRepository = $this->createMock(QuoteRepository::class);
        $this->siteRepository = $this->createMock(SiteRepository::class);
        $this->destinationRepository = $this->createMock(DestinationRepository::class);
    }

    /**
     * Closes the mocks
     */
    public function tearDown()
    {
    }

    /**
     * @test
     */
    public function test()
    {
        $faker = \Faker\Factory::create();

        $destinationId       = $faker->randomNumber();
        $expectedDestination = new \App\Entity\Destination(
            $destinationId,
            $faker->country,
            'en',
            $faker->slug
        );
        $this->destinationRepository->expects($this->any())
            ->method('getById')
            ->willReturn($expectedDestination);

        $expectedUser =  new User($faker->randomNumber(), $faker->firstName, $faker->lastName, $faker->email);
        $this->applicationContext->expects($this->any())
            ->method('getCurrentUser')
            ->willReturn($expectedUser);

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $destinationId, $faker->date());
        $this->quoteRepository->expects($this->any())
            ->method('getById')
            ->willReturn($quote);

        $template = new Template(
            1,
            'Votre livraison à [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci de nous avoir contacté pour votre livraison à [quote:destination_name].

Bien cordialement,

L'équipe de Shipper
");
        $templateManager = new TemplateManager(
            $this->applicationContext,
            $this->quoteRepository,
            $this->siteRepository,
            $this->destinationRepository
        );

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('Votre livraison à ' . $expectedDestination->countryName, $message->subject);
        $this->assertEquals("
Bonjour " . $expectedUser->firstname . ",

Merci de nous avoir contacté pour votre livraison à " . $expectedDestination->countryName . ".

Bien cordialement,

L'équipe de Shipper
", $message->content);
    }
}
