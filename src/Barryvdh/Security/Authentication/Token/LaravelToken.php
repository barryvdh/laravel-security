<?php

namespace Barryvdh\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Illuminate\Auth\UserInterface;
class LaravelToken extends AbstractToken
{

    /**
     * Constructor.
     * @param \Illuminate\Auth\UserInterface $user The user
     */
    public function __construct($user)
    {
        if(!is_null($user) and is_callable(array($user, 'getRoles'))){
            parent::__construct($user->getRoles());
        }else{
            parent::__construct(array());
        }


        if(!is_null($user)){
            $this->setUser($user);
            $this->setAuthenticated(true);
        }else{
            $this->setUser('');
            $this->setAuthenticated(false);
        }

    }


    public function getCredentials()
    {
        return '';
    }

}
