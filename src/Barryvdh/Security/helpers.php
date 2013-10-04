<?php

if ( ! function_exists('is_granted') ){

    /**
     * Checks if the attributes are granted against the current token.
     *
     * @throws AuthenticationCredentialsNotFoundException when the security context has no authentication token.
     *
     * @param mixed      $attributes
     * @param mixed|null $object
     *
     * @return Boolean
     */
    function is_granted($attributes, $object = null){
        return app('security')->isGranted($attributes, $object);
    }

}