<?php

namespace Barryvdh\Security\Authentication;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

class AuthenticationManager implements AuthenticationManagerInterface
{

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        //Check if not blocked or something?
        return $token;
    }
}
