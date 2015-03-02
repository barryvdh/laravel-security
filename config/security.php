<?php

return array(

 /*
  |--------------------------------------------------------------------------
  | Access Decision Strategy
  |--------------------------------------------------------------------------
  |
  | Set which strategy should be used to determine access. Possible values are:
  |
  | affirmative (default)
  |     grant access as soon as any voter returns an affirmative response;
  |  consensus
  |     grant access if there are more voters granting access than there are denying;
  |  unanimous
  |     only grant access if none of the voters has denied access;
  |
  */
    'strategy' => 'affirmative',

 /*
  |--------------------------------------------------------------------------
  | Role Hierarchy
  |--------------------------------------------------------------------------
  |
  | Set which roles inherit from other roles. Example:
  | array(
  |     'ROLE_ADMIN' => array('ROLE_USER'),
  |     'ROLE_SUPER_ADMIN' => array('ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH')
  | )
  |
  */
  'role_hierarchy' => array(),

  /*
  |--------------------------------------------------------------------------
  | Voters
  |--------------------------------------------------------------------------
  |
  | The voters listed here will be automatically added.
  |
  */
  'voters' => [
    'Barryvdh\Security\Authorization\Voter\AuthVoter',
    'Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter',
  ],
);
