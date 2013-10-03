<?php

namespace Barryvdh\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Illuminate\Auth\UserInterface;
class LaravelToken extends AbstractToken
{

    /** @var \Illuminate\Auth\UserInterface $user  */
    private $user;

    /**
     * Constructor.
     * @param \Illuminate\Auth\UserInterface $user The user
     * @param array $roles
     */
    public function __construct($user, array $roles = array())
    {
        parent::__construct($roles);
        $this->setUser($user);

        if(!is_null($user)){
            $this->setAuthenticated(true);
        }else{
            $this->setAuthenticated(false);
        }

    }

    /**
     * Sets the user in the token.
     *
     * The user has to be an UserInterface instance.
     *
     * @param \Illuminate\Auth\UserInterface $user The user
     * @throws \InvalidArgumentException
     */
    public function setUser( $user)
    {
        if (!($user instanceof UserInterface ) and !is_null($user)) {
            throw new \InvalidArgumentException('$user must be an instanceof \Illuminate\Auth\UserInterface.');
        }
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getCredentials()
    {
        return $this->user->getAuthPassword();
    }

}
