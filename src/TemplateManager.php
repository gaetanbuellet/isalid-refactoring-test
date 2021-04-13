<?php

namespace App;

use App\Context\ApplicationContext;
use App\Entity\Quote;
use App\Entity\Template;
use App\Entity\User;
use App\Repository\Repository;

class TemplateManager
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

    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote)
        {
            $_quoteFromRepository = $this->quoteRepository->getById($quote->id);
            $site = $this->siteRepository->getById($quote->siteId);
            $destinationOfQuote = $this->destinationRepository->getById($quote->destinationId);

            $text = str_replace(
                [
                    '[quote:summary_html]',
                    '[quote:summary]',
                    '[quote:destination_name]'
                ],
                [
                    Quote::renderHtml($_quoteFromRepository),
                    Quote::renderText($_quoteFromRepository),
                    $destinationOfQuote->countryName
                ],
                $text
            );
        }

        $text = str_replace(
            '[quote:destination_link]',
            isset($destinationOfQuote) ? $site->url . '/' . $destinationOfQuote->countryName . '/quote/' . $_quoteFromRepository->id : '',
            $text
        );

        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $this->applicationContext->getCurrentUser();
        if($_user) {
            $text = str_replace(
                '[user:first_name]',
                ucfirst(mb_strtolower($_user->firstname)),
                $text
            );
        }

        return $text;
    }
}
