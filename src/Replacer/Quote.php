<?php


namespace App\Replacer;


use App\Repository\Repository;
use App\Entity\Quote as EntityQuote;

class Quote implements Replacer
{
    /**
     * @var Repository
     */
    private $siteRepository;

    /**
     * @var Repository
     */
    private $quoteRepository;

    /**
     * @var Repository
     */
    private $destinationRepository;

    public function __construct(
        Repository $siteRepository,
        Repository $quoteRepository,
        Repository $destinationRepository
    ) {

        $this->siteRepository = $siteRepository;
        $this->quoteRepository = $quoteRepository;
        $this->destinationRepository = $destinationRepository;
    }

    public function replace($propertyName, array $data)
    {
        $quote = (isset($data['quote']) and $data['quote'] instanceof \App\Entity\Quote) ? $data['quote'] : null;

        switch ($propertyName) {
            case 'summary_html':
                return $quote ? EntityQuote::renderHtml($this->quoteRepository->getById($quote->id)) : '';
            case 'summary':
                return $quote ? EntityQuote::renderText($this->quoteRepository->getById($quote->id)): '';
            case 'destination_name':
                return $quote ? $this->destinationRepository->getById($quote->destinationId)->countryName : '';
            case 'destination_link':
                return $this->getDestinationLink($quote);
        }
    }

    private function getDestinationLink(EntityQuote $quote)
    {
        if (!$quote) {
            $destinationOfQuote = $this->destinationRepository->getById($quote->destinationId);
            $site = $this->siteRepository->getById($quote->siteId);
            $quoteFromRepository = $this->quoteRepository->getById($quote->id);

            return isset($destinationOfQuote) ? $site->url . '/' . $destinationOfQuote->countryName . '/quote/' . $quoteFromRepository->id : '';
        }
    }
}
