<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class for performing query and update operations for DinUser model table
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class PluginDinUserTable extends dinDoctrineTable
{

    /**
     * Returns an instance of PluginDinUserTable
     * 
     * @return  PluginDinUserTable
     */
    public static function getInstance()
    {

        return Doctrine_Core::getTable( 'PluginDinUser' );

    } // PluginDinUserTable::getInstance()


    /**
     * Add query for retrieving users w/o services
     * 
     * @param   Doctrine_Query  $q
     * @return  Doctrine_Query
     */
    public function retrieveWithoutServices( $q = null )
    {

        if ( is_null( $q ) )
        {
            $q = $this->createQuery();
        }

        return $q->addWhere( $q->getRootAlias() . '.id > ?', 499 );

    } // PluginDinUserTable::retrieveWithoutServices()


    /**
     * Get choices for services
     * 
     * @param   string  $field  Field name
     * @return  array   Services list
     */
    public function getChoicesForServices( $field = 'nickname' )
    {

        $services = $this->createQuery()->select( 'id, ' . $field )->addWhere( 'id < ?', 500 )
            ->execute( array(), Doctrine::HYDRATE_ARRAY );
        $out = array();
        foreach ( $services as $service )
        {
            $out[$service['id']] = $service[$field];
        }
        return $out;

    } // PluginDinUserTable::getChoicesForServices()


    /**
     * Get user for authentication
     * 
     * @param   string  $field  Field name
     * @param   string  $uniqid Unique identifier
     * @return  DinUser
     */
    public function getUserForAuth( $field, $uniqid )
    {

        return $this->createQuery( 'u' )->where( 'u.' . $field . ' = ?', $uniqid )->fetchOne();

    } // PluginDinUserTable::getUserForAuth()


    /**
     * Get user by email
     * 
     * @param   string  $email  User email
     * @return  DinUser
     */
    public function getUserByEmail( $email )
    {

        return $this->createQuery( 'u' )->where( 'u.email = ?', $email )->fetchOne();

    } // PluginDinUserTable::getUserByEmail()

} // PluginDinUserTable

//EOF