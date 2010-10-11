<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class for performing query and update operations for DinUserNotification model table
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class PluginDinUserNotificationTable extends dinDoctrineTable
{

    /**
     * Returns an instance of PluginDinUserNotificationTable
     * 
     * @return  PluginDinUserNotificationTable
     */
    public static function getInstance()
    {

        return Doctrine_Core::getTable( 'PluginDinUserNotification' );

    } // PluginDinUserNotificationTable::getInstance()


    /**
     * Query for getting last notifications
     * 
     * @param   array   $params Query params
     * @return  Doctrine_Query
     */
    public function getLastItemsQuery( $params )
    {

        $q = $this->createQuery();
        $this->addQuery( $q )
            ->addSelect( array( 'id', 'user_id', 'type_id', 'created_at', 'text' ) )
            ->addWhere( 'state_id', true );

        if ( isset( $params['user_id'] ) )
        {
            $this->addWhere( 'user_id', $params['user_id'] );
        }
        if ( isset( $params['limit'] ) )
        {
            $q->limit( $params['limit'] );
        }
        return $q;

    } // PluginDinUserNotificationTable::getLastItemsQuery()


    /**
     * Hide user info
     * 
     * @param   integer $id     Info identifier
     * @param   integer $userId User identifier
     * @return  boolean Result
     */
    public function hideInfo( $id, $userId )
    {

        $info = $this->createQuery( 'nt' )->addWhere( 'nt.id = ?', $id )
            ->addWhere( 'nt.user_id = ?', $userId )->fetchOne();

        if ( $info && $info->getStateId() == 1 )
        {
            $info->setReadAt( date( 'Y-m-d H:i:s' ) )->setStateId( 2 )->save();
            return true;
        }
        return false;

    } // PluginDinUserNotificationTable::hideInfo()


    /**
     * Update user notification
     * 
     * @param   integer $userId     User identifier
     * @param   integer $typeId     Notification type identifier
     * @param   string  $text       Notification message
     * @param   integer $creatorId  Creator identifier
     * @return  void
     */
    public function updateUserNotification( $userId, $typeId, $text, $creatorId )
    {

        $info = $this->createQuery( 'nt' )->addWhere( 'nt.type_id = ?', $typeId )
            ->addWhere( 'nt.user_id = ?', $userId )->fetchOne();

        if ( !$info )
        {
            $info = new DinUserNotification;
            $info->setUserId( $userId )->setTypeId( $typeId );
        }
        $info->setStateId( 1 )->setCreatedAt( date( 'Y-m-d H:i:s' ) )->setReadAt( null )
            ->setText( $text )->setCreatorId( $creatorId )->save();

    } // PluginDinUserNotificationTable::updateUserNotification()


    /**
     * Update notifications
     * 
     * @param   integer $typeId     Notification type identifier
     * @param   string  $text       Notification message
     * @param   integer $creatorId  Creator identifier
     * @return  void
     */
    public function updateNotifications( $typeId, $text, $creatorId )
    {

        $this->createQuery( 'nt' )->set( 'nt.text', '?', $text )->set( 'nt.state_id', '?', 1 )
            ->set( 'nt.created_at', '?', date( 'Y-m-d H:i:s' ) )->set( 'nt.read_at', 'NULL' )
            ->set( 'nt.creator_id', '?', $creatorId )->where( 'nt.type_id = ?', $typeId )
            ->update()->execute();

    } // PluginDinUserNotificationTable::updateNotifications()

} // PluginDinUserNotificationTable

//EOF