<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class that represents a record of DinUserPm model
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
abstract class PluginDinUserPm extends BaseDinUserPm
{

    protected
        $status = array(
            0 => 'deleted',
            1 => 'exist_new',
            2 => 'exist_viewed',
            3 => 'exist_deleted',
            4 => 'exist_new_deleted',
            5 => 'deleted_new',
            6 => 'deleted_viewed'
        );

    protected
        $statusMatrix = array(
            'senderDelete' => array( 0 => 0, 1 => 5, 2 => 6, 3 => 0, 4 => 0, 5 => 5, 6 => 6 ),
            'recipientDelete' => array( 0 => 0, 1 => 4, 2 => 3, 3 => 3, 4 => 4, 5 => 0, 6 => 0 ),
            'recipientShow' => array( 0 => 0, 1 => 2, 2 => 2, 3 => 3, 4 => 3, 5 => 6, 6 => 6 ),
            'recipientShowDelete' => array( 0 => 0, 1 => 3, 2 => 3, 3 => 3, 4 => 3, 5 => 0, 6 => 0 )
        );

    const STATUS_DELETED = 0;
    const STATUS_EXIST_NEW = 1;
    const STATUS_EXIST_VIEWED = 2;
    const STATUS_EXIST_DELETED = 3;
    const STATUS_EXIST_NEW_DELETED = 4;
    const STATUS_DELETED_NEW = 5;
    const STATUS_DELETED_VIEWED = 6;


    /**
     * Delete message by sender
     *
     * @return  void
     */
    public function deleteBySender()
    {

        $this->setStateId( $this->statusMatrix['senderDelete'][$this->getStateId()] );
        if ( $this->getStateId() == 0 )
        {
            $this->delete();
            return;
        }
        $this->save();

    } // PluginDinUserPm::deleteBySender()


    /**
     * Delete message by recipient
     *
     * @return  void
     */
    public function deleteByRecipient()
    {

        $this->setStateId( $this->statusMatrix['recipientDelete'][$this->getStateId()] );
        if ( $this->getStateId() == 0 )
        {
            $this->delete();
            return;
        }
        $this->save();

    } // PluginDinUserPm::deleteByRecipient()


    /**
     * Show message by recipient
     *
     * @return  void
     */
    public function showByRecipient()
    {

        if ( $this->isUnread() )
        {
            $this->setReadAt( date( 'Y-m-d H:i:s', time() ) );
        }
        $this->setStateId( $this->statusMatrix['recipientShow'][$this->getStateId()] );
        $this->save();

    } // PluginDinUserPm::showByRecipient()


    /**
     * Check unread status
     *
     * @return  boolean
     */
    public function isUnread()
    {

        return in_array( $this->getStateId(), array( 1, 4, 5 ) );

    } // PluginDinUserPm::isUnread()

} // PluginDinUserPm

//EOF