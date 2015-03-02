<?php namespace Barryvdh\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class Facade extends \Illuminate\Support\Facades\Facade {

    /**
     * Add a voter for the AccessDecisionManager
     * @param \Symfony\Component\Security\Core\Authorization\Voter\VoterInterface $voter
     */
    public static function addVoter(VoterInterface $voter){
        static::$app->extend('security.voters', function($voters) use ($voter) {
                $voters[] = $voter;
                return $voters;
            });
    }
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor() { return 'security.authorization_checker'; }

}
