<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class that represents a record of DinUser model
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
abstract class PluginDinUser extends BaseDinUser
{

    /**
     * Magic gives a string with the user nickname
     * 
     * @return  string  User nickname
     */
    public function __toString()
    {

        return $this->getNickname();

    } // PluginDinUser::__toString()


    /**
     * Check password
     * 
     * @param   string  $password   User password
     * @return  boolean Check result
     */
    public function checkPassword( $password )
    {

        return $this->getPasshashe() === md5( $password . $this->getSalt() );

    } // PluginDinUser::checkPassword()


    /**
     * Set password
     * 
     * @param   string  $password   User password
     * @return  void
     */
    public function setPassword( $password )
    {

        if ( empty( $password ) )
        {
            return;
        }

        $salt = $this->generateSalt();
        $this->_set( 'salt', $salt );
        $this->_set( 'passhashe', md5( $password . $salt ) );

    } // PluginDinUser::setPassword()


    /**
     * Generate salt
     * 
     * @param   integer $length Salt length [optional]
     * @return  string  Generated salt
     */
    public function generateSalt( $length = 6 )
    {

        $list = '0123456789ABCDEF';
        mt_srand( (double) microtime() * 1000000 );
        $string = '';
        if ( $length > 0 )
        {
            while ( strlen( $string ) < $length )
            {
                $string .= $list[ mt_rand( 0, strlen( $list ) - 1 ) ];
            }
        }
        return $string;

    } // PluginDinUser::generateSalt()


    /**
     * Get permissions
     * 
     * @return  array   Permitted groups
     */
    public function getPermissions()
    {

        //TODO: refactor method and add personal permission functionality
        $perms = array();
        foreach ( $this->getGroups() as $group )
        {
            $perms[$group->getName()] = null;
        }
        return array_keys( $perms );

    } // PluginDinUser::getPermissions()


    /**
     * Get representation of state
     * 
     * @return  string  State representation
     */
    public function getState()
    {

        //TODO: change this!
        $status = dinConfig::getList( 'DinUser', 'state', $this->_get( 'state_id' ), false );
        return $status ? $status['title'] : '-';

    } // PluginDinUser::getState()

} // PluginDinUser

//EOF