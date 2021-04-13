<?php

namespace App;

use App\Context\ApplicationContext;
use App\Entity\Template;
use App\Replacer\Factory;
use App\Repository\Repository;

class TemplateManager
{
    /**
     * @var Factory
     */
    private $replacerFactory;

    public function __construct(Factory $replacerFactory){

        $this->replacerFactory = $replacerFactory;
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
        $quoteReplacer = $this->replacerFactory->create('quote');
        $text = $quoteReplacer->replace($text, $data);

        $userReplacer = $this->replacerFactory->create('user');
        $text = $userReplacer->replace($text, $data);

        return $text;
    }
}
