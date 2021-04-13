<?php


namespace App\Replacer;

use App\Context\ApplicationContext;
use App\Repository\DestinationRepository;
use App\Repository\QuoteRepository;
use App\Repository\Repository;
use App\Repository\SiteRepository;

class Factory
{
    /**
     * @var ApplicationContext
     */
    private $applicationContext;

    /**
     * @var Repository
     */
    private $quoteRepository;

    /**
     * @var Repository
     */
    private $siteRepository;

    /**
     * @var Repository
     */
    private $destinationRepository;

    public function __construct(
        ApplicationContext $applicationContext,
        Repository $quoteRepository,
        Repository $siteRepository,
        Repository $destinationRepository
    ) {
        $this->applicationContext = $applicationContext;
        $this->quoteRepository = $quoteRepository;
        $this->siteRepository = $siteRepository;
        $this->destinationRepository = $destinationRepository;
    }

    public function create($domain)
    {
        switch ($domain) {
            case 'user':
                return new User($this->applicationContext);
            case 'quote':
                return new Quote($this->siteRepository,  $this->quoteRepository, $this->destinationRepository);
            default:
                return new Null();
        }
    }
}
