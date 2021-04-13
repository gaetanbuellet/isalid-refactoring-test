<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Quote;
use App\Entity\Template;
use App\TemplateManager;

$faker = \Faker\Factory::create();

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
    new \App\Context\ApplicationContext(),
    new \App\Repository\QuoteRepository(),
    new \App\Repository\SiteRepository(),
    new \App\Repository\DestinationRepository()
);

$message = $templateManager->getTemplateComputed(
    $template,
    [
        'quote' => new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
    ]
);

echo $message->subject . "\n" . $message->content;
