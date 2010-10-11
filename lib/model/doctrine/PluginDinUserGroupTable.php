<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class for performing query and update operations for DinUserGroup model table
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class PluginDinUserGroupTable extends dinDoctrineTable
{

    /**
     * Returns an instance of PluginDinUserGroupTable
     * 
     * @return  PluginDinUserGroupTable
     */
    public static function getInstance()
    {

        return Doctrine_Core::getTable( 'PluginDinUserGroup' );

    } // PluginDinUserGroupTable::getInstance()


    /**
     * Get groups by titles
     * 
     * @param   array   $titles Titles array
     * @return  array   DinUserGroup
     */
    public function getGroupsByTitles( $titles )
    {

        return $this->createQuery( 'g' )->whereIn( 'g.name', $titles )->execute();

    } // PluginDinUserGroupTable::getGroupsByTitles()


    /**
     * Get choices query
     * 
     * @param   array   $params Query parameters [optional]
     * @return  Doctrine_Query
     */
    public function getChoicesQuery( $params = array() )
    {

        $q = $this->createQuery();
        $this->addQuery( $q )->joinI18n()->addSelect( array( 'id', 'title' ) )
            ->addWhere( 'is_active', true )->addOrderBy( array( 'title' ) )->free();
        return $q;

    } // PluginDinUserGroupTable::getChoicesQuery()

} // PluginDinUserGroupTable

//EOF