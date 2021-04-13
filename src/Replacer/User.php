<?php


namespace App\Replacer;


use App\Context\ApplicationContext;
use App\Entity\Quote as EntityQuote;

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

    public function replace($propertyName, array $data)
    {
        $user  = (isset($data['user']) && ($data['user']  instanceof \App\Entity\User))
            ? $data['user']
            : $this->applicationContext->getCurrentUser();

        switch ($propertyName) {
            case 'first_name':
                return $user ? ucfirst(mb_strtolower($user->firstname)) : '';
            default:
                return '';
        }
    }
}
