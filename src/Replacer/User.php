<?php


namespace App\Replacer;


use App\Context\ApplicationContext;

class User implements Replacer
{

    /**
     * @var ApplicationContext
     */
    private $applicationContext;

    public function __construct(ApplicationContext $applicationContext)
    {
        $this->applicationContext = $applicationContext;
    }

    public function replace($text, $data)
    {
        $_user  = (isset($data['user'])  and ($data['user']  instanceof \App\Entity\User))  ? $data['user']  : $this->applicationContext->getCurrentUser();
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
