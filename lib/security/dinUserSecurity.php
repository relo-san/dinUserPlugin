<?php

/**
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * User security system class
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.security
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinUserSecurity extends sfBasicSecurityUser
{

    protected
        $sis,
        $states = array(
            0 => 'deleted',
            1 => 'active',
            2 => 'new',
            3 => 'suspended',
            4 => 'disabled',
            5 => 'service'
        );
    


    /**
     * getSIS
     * 
     * @return  
     */
    public function getSIS()
    {

        if ( !$this->sis )
        {
            $this->sis = new dinUserServiceInfoStorage;
        }
        return $this->sis;

    } // dinUserSecurity::getSIS()


    /**
     * User authentication method
     * 
     * @param   string  $uniqid     Unique user identifier
     * @param   string  $password   User password
     * @param   array   $groups     Permitted user groups
     * @return  boolean Authentication result
     * @throws  sfSecurityException     Not permitted group or bad auth data
     */
    public function login( $uniqid, $password, $groups )
    {

        // get user
        $user = Doctrine::getTable( 'DinUser' )->getUserForAuth(
            sfConfig::get( 'app_auth_authField', 'email' ), $uniqid
        );

        // check password, status and permissions
        // TODO: add status check
        if ( $user && $user->checkPassword( $password ) )
        {
            $perms = $user->getPermissions();
            foreach ( $perms as $perm )
            {
                if ( in_array( $perm, $groups ) )
                {
                    return $this->doLogin( $user );
                }
            }
            throw new sfSecurityException( 'formMessages.groupNotPermitted' );
        }
        throw new sfSecurityException( 'formMessages.badAuthData' );

    } // dinUserSecurity::login()


    /**
     * Shadow authentication method
     * 
     * @param   integer $userId User identifier
     * @param   array   $groups Permitted groups
     * @return  boolean Authentication result
     */
    public function shadowLogin( $userId, $groups )
    {

        $user = Doctrine::getTable( 'DinUser' )->find( $userId );
        if ( !$user )
        {
            return false;
        }

        if ( $user->getState() != 1 )
        {
            return false;
        }

        $perms = $user->getPermissions();
        foreach ( $perms as $perm )
        {
            if ( in_array( $perm, $groups ) )
            {
                return $this->doLogin( $user );
            }
        }
        return false;

    } // dinUserSecurity::shadowLogin()


    /**
     * Authenticate user
     * 
     * @param   string  $user       User object
     * @param   boolean $remember   Remember user
     * @return  boolean true
     */
    public function doLogin( $user, $remember = false )
    {

        // TODO: add remember functionality
        $namespace = sfConfig::get( 'app_auth_namespace', 'dinUserSecurity' );
        $this->setAttribute( 'user_id', $user->getId(), $namespace );
        if ( class_exists( 'DinUserInfo' ) )
        {
            $this->setAttribute( 'firstname', $user->getInfo()->getFirstname(), $namespace );
            $this->setAttribute( 'lastname', $user->getInfo()->getLastname(), $namespace );
        }
        $this->setAttribute( 'nickname', $user->getNickname(), $namespace );
        $this->setAttribute( 'email', $user->getEmail(), $namespace );
        $this->setAuthenticated( true );
        $this->clearCredentials();
        $this->addCredentials( $user->getPermissions() );
        return true;

    } // dinUserSecurity::doLogin()


    /**
     * User logout method
     * 
     * @return  boolean true
     */
    public function logout()
    {

        $this->getAttributeHolder()->removeNamespace(
            sfConfig::get( 'app_auth_namespace', 'dinUserSecurity' )
        );
        $this->clearCredentials();
        $this->setAuthenticated( false );
        return true;

    } // dinUserSecurity::logout()


    /**
     * Get firstname
     * 
     * @return  string  Firstname
     */
    public function getFirstname()
    {

        return $this->getAttribute(
            'firstname', '', sfConfig::get( 'app_auth_namespace', 'dinUserSecurity' )
        );

    } // dinUserSecurity::getFirstname()


    /**
     * Get username
     * 
     * @return  string  Username
     */
    public function getUsername()
    {

        return $this->getFirstname();

    } // dinUserSecurity::getUsername()


    /**
     * Get lastname
     * 
     * @return  string  Lastname
     */
    public function getLastname()
    {

        return $this->getAttribute(
            'lastname', '', sfConfig::get( 'app_auth_namespace', 'dinUserSecurity' )
        );

    } // dinUserSecurity::getLastname()


    /**
     * Get nickname
     * 
     * @return  string  Nickname
     */
    public function getNickname()
    {

        return $this->getAttribute(
            'nickname', '', sfConfig::get( 'app_auth_namespace', 'dinUserSecurity' )
        );

    } // dinUserSecurity::getNickname()


    /**
     * Get email
     * 
     * @return  string  Email
     */
    public function getEmail()
    {

        return $this->getAttribute(
            'email', '', sfConfig::get( 'app_auth_namespace', 'dinUserSecurity' )
        );

    } // dinUserSecurity::getEmail()


    /**
     * Get user identifier
     * 
     * @return  integer User identifier
     */
    public function getUserId()
    {

        return $this->getAttribute(
            'user_id', null, sfConfig::get( 'app_auth_namespace', 'dinUserSecurity' )
        );

    } // dinUserSecurity::getUserId()


    /**
     * setMessage
     * 
     * @return  
     */
    public function addMessage( $type, $message, $dest = 'default' )
    {

        $messages = $this->getAttribute( $type, array(), 'symfony/user/dinUser/message/' . $dest );
        $messages[] = $message;
        $this->setAttribute( $type, $messages, 'symfony/user/dinUser/message/' . $dest );

    } // dinUserSecurity::setMessage()


    public function getMessages( $type = null, $dest = 'default' )
    {

        if ( $type )
        {
            return $this->attributeHolder->remove(
                $type, array(), 'symfony/user/dinUser/message/' . $dest
            );
        }

        $messages = array();
        $types = $this->attributeHolder->getNames( 'symfony/user/dinUser/message/' . $dest );
        foreach ( $types as $type )
        {
            $messages[$type] = $this->attributeHolder->remove(
                $type, array(), 'symfony/user/dinUser/message/' . $dest
            );
        }
        return $messages;

    } // dinUserSecurity::getMessages()


    /**
     * hasMessages
     * 
     * @return  
     */
    public function hasMessages( $type, $dest = 'default' )
    {

        return $this->hasAttribute( $type, 'symfony/user/dinUser/message/' . $dest );

    } // dinUserSecurity::hasMessages()


    /**
     * Get flash message
     * 
     * @param   string  $name       Name of flash variable
     * @param   string  $default    Default value [optional]
     * @return  string  Flash message
     */
    public function getFlash( $name, $default = null )
    {

        if ( !$this->options['use_flash'] )
        {
            return $default;
        }

        $flash = $this->getAttribute( $name, $default, 'symfony/user/sfUser/flash' );
        if ( $this->options['remove_flash'] )
        {
            $this->setAttribute( $name, null, 'symfony/user/sfUser/flash' );
        }
        return $flash;

    } // dinUserSecurity::getFlash()

} // dinUserSecurity

//EOF