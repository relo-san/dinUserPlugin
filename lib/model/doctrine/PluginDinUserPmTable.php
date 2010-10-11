<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class for performing query and update operations for DinUserPm model table
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class PluginDinUserPmTable extends dinDoctrineTable
{

    /**
     * Returns an instance of PluginDinUserPmTable
     * 
     * @return  PluginDinUserPmTable
     */
    public static function getInstance()
    {

        return Doctrine_Core::getTable( 'PluginDinUserPm' );

    } // PluginDinUserPmTable::getInstance()


    /**
     * Get messages inbox list query for specific user
     * 
     * @param   integer $userId User identifier
     * @param   integer $status Messages status filter [optional]
     * @return  Doctrine_Query
     */
    public function getUserInboxListQuery( $userId, $status = null )
    {

        return $this->getListQuery( 'pm' )->addSelect( 'pms.nickname' )->leftJoin( 'pm.Sender pms' )
            ->whereIn( 'pm.status', array( 1, 2, 5, 6 ) )->andWhere( 'pm.recipient_id = ?', $userId );

    } // PluginDinUserPmTable::getUserInboxListQuery()


    /**
     * Get messages outbox list query for specific user
     * 
     * @param   integer $userId User identifier
     * @param   integer $status Messages status filter [optional]
     * @return  Doctrine_Query
     */
    public function getUserOutboxListQuery( $userId, $status = null )
    {

        return $this->getListQuery( 'pm' )->addSelect( 'pmr.nickname' )->leftJoin( 'pm.Recipient pmr' )
            ->whereIn( 'pm.status', array( 1, 2, 3, 4 ) )->addWhere( 'pm.sender_id = ?', $userId );

    } // PluginDinUserPmTable::getUserOutboxListQuery()


    /**
     * Get private messages list query
     * 
     * @return  Doctrine_Query
     */
    public function getListQuery()
    {

        return $this->createQuery( 'pm' )->addSelect( 'pm.id, pm.sender_id, pm.recipient_id' )
            ->addSelect( 'pm.parent_id, pm.status, pm.created_at, pm.read_at, pmc.subject' )
            ->leftJoin( 'pm.Content pmc' );

    } // PluginDinUserPmTable::getListQuery()


    /**
     * Get full message query
     * 
     * @param  integer $id  Message identifier
     * @return Doctrine_Query
     */
    public function getFullMessageQuery( $id )
    {

        return $this->createQuery( 'pm' )->where( 'pm.id = ?', $id )->leftJoin( 'pm.Sender as pms' )
            ->leftJoin( 'pm.Recipient as pmr' )->leftJoin( 'pm.Content as pmc' );

    } // PluginDinUserPmTable::getFullMessageQuery()


    /**
     * Get full message
     * 
     * @param   integer $id     Message identifier
     * @return  DinUserPm
     */
    public function getFullMessage( $id )
    {

        return $this->getFullMessageQuery( $id )->fetchOne();

    } // PluginDinUserPmTable::getFullMessage()


    /**
     * Gives full message with the parent message
     * 
     * @param  integer  $id Message identifier
     * @return DinUserPm
     */
    public function getFullMessageWithParent( $id )
    {

        return $this->getFullMessageQuery( $id )->leftJoin( 'pm.Parent as ppm' )
            ->leftJoin( 'ppm.Content as ppmc' )->leftJoin( 'ppm.Sender as ppms' )
            ->leftJoin( 'ppm.Recipient as ppmr' )->fetchOne();

    } // PluginDinUserPmTable::getFullMessageWithParent()


    /**
     * Get new messages count
     * 
     * @param   integer $userId User identifier
     * @return  integer Count of new messages
     */
    public function getNewMessagesCount( $userId )
    {

        return Doctrine_Query::create()->select( 'COUNT(pm.id)' )->from( 'DinUserPm pm' )
            ->where( 'pm.recipient_id = ?', $userId )->andWhereIn( 'pm.state_id', array( 1, 5 ) )
            ->execute( array(), Doctrine::HYDRATE_SINGLE_SCALAR );

    } // PluginDinUserPmTable::getNewMessagesCount()


    /**
     * Get recipient
     * 
     * @param   integer $replyId    Reply message identifier ( 0 if not have parent )
     * @param   integer $userId     User identifier [optional]
     * @return  DinUser
     */
    public function getRecipient( $replyId, $userId = null )
    {

        if ( $replyId )
        {
            if ( $user = Doctrine_Query::create()->from( 'DinUser u' )
                ->leftJoin( 'u.PMSenders pms' )->where( 'pms.id = ?', $replyId )->fetchOne() )
            {
                return $user;
            }
        }

        if ( $userId )
        {
            if ( $user = Doctrine::getTable( 'DinUser' )->find( $userId ) )
            {
                return $user;
            }
        }

        return false;

    } // PluginDinUserPmTable::getRecipient()


    /**
     * Get list outbox messages query for current user
     * 
     * @return Doctrine_Query
     */
    public function getUserOutboxQuery()
    {

        return $this->getUserOutboxListQuery( sfContext::getInstance()->getUser()->getUserId() );

    } // PluginDinUserPmTable::getUserOutboxQuery()


    /**
     * Get list inbox messages query for current user
     * 
     * @return Doctrine_Query
     */
    public function getUserInboxQuery()
    {

        return $this->getUserInboxListQuery( sfContext::getInstance()->getUser()->getUserId() );

    } // PluginDinUserPmTable::getUserInboxQuery()


    /**
     * Get PM via web request
     * 
     * @param  array    $webRequest
     * @return DinUserPm
     */
    public function getMessageForRoute( $webRequest )
    {

        return $this->getFullMessageWithParent( $webRequest['id'] );

    } // PluginDinUserPmTable::getMessageForRoute()

} // PluginDinUserPmTable

//EOF