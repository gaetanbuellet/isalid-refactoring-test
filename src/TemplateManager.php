<?php

namespace App;

use App\Entity\Template;
use App\Replacer\Factory;

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
        //We find something like [domain:property] and catch domain group domain and property
        preg_match_all('/\[(?P<domain>\w+):(?P<property>\w+)\]/', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $replacer = $this->replacerFactory->create($match['domain']);
            $text = str_replace(
                $match[0],
                $replacer->replace($match['property'], $data),
                $text
            );
        }

        return $text;
    }
}
