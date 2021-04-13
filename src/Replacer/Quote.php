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

    public function replace($text, $data)
    {
        $quote = (isset($data['quote']) and $data['quote'] instanceof \App\Entity\Quote) ? $data['quote'] : null;
        $site = $this->siteRepository->getById($quote->siteId);

        if ($quote)
        {
            $quoteFromRepository = $this->quoteRepository->getById($quote->id);
            $destinationOfQuote = $this->destinationRepository->getById($quote->destinationId);

            $text = str_replace(
                [
                    '[quote:summary_html]',
                    '[quote:summary]',
                    '[quote:destination_name]'
                ],
                [
                    EntityQuote::renderHtml($quoteFromRepository),
                    EntityQuote::renderText($quoteFromRepository),
                    $destinationOfQuote->countryName
                ],
                $text
            );
        }

        $text = str_replace(
            '[quote:destination_link]',
            isset($destinationOfQuote) ? $site->url . '/' . $destinationOfQuote->countryName . '/quote/' . $quoteFromRepository->id : '',
            $text
        );

        return $text;
    }
}
