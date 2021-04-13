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
        $quoteReplacer = new \App\Replacer\Quote($this->siteRepository,  $this->quoteRepository, $this->destinationRepository);
        $text = $quoteReplacer->replace($text, $data);

        $userReplacer = new \App\Replacer\User($this->applicationContext);
        $text = $userReplacer->replace($text, $data);

        return $text;
    }
}
