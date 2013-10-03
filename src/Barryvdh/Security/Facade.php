<?php namespace Barryvdh\Security;

class Facade extends \Illuminate\Support\Facades\Facade {

    /**
     * Add a voter. Probably not the best way to do this..
     * @param $voter
     */
    public static function addVoter($voter){
        $app = self::$app;
        $voters = $app['security.voters'];
        $app['security.voters'] = $app->share(function() use ($voters, $voter){
                $voters[] = $voter;
                return $voters;
            });
    }
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor() { return 'security'; }

}