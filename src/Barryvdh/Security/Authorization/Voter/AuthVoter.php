<?php

namespace Barryvdh\Security\Authorization\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AuthVoter implements VoterInterface
{
    const IS_AUTHENTICATED = 'IS_AUTHENTICATED';
    private $guard;

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return self::IS_AUTHENTICATED === $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;

            if (self::IS_AUTHENTICATED === $attribute && $token->isAuthenticated() ) {
                return VoterInterface::ACCESS_GRANTED;
            }

        }

        return $result;
    }
}
