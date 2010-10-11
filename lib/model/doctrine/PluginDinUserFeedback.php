<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class that represents a record of DinUserFeedback model
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
abstract class PluginDinUserFeedback extends BaseDinUserFeedback
{

    //TODO: refactor this shit

    /**
     * Get name
     * 
     * @return  string  User email
     */
    public function getName()
    {

        if ( $this->_get( 'user_id' ) )
        {
            return $this->getUser()->getEmail();
        }
        return $this->_get( 'firstname' )
            ? $this->_get( 'firstname' ) . ' <' . $this->_get( 'email' ) . '>'
            : $this->_get( 'email' );

    } // PluginDinUserFeedback::getName()


    /**
     * Get state representation
     * 
     * @return  string  Feedback state
     */
    public function getStatus()
    {

        // TODO: change this!
        $status = dinConfig::getList( 'DinUserFeedback', 'state', $this->_get( 'state' ), false );
        return $status ? $status['title'] : '-';

    } // PluginDinUserFeedback::getStatus()


    /**
     * Get email
     * 
     * @return  string  Sender email
     */
    public function getEmail()
    {

        if ( $this->_get( 'user_id' ) )
        {
            return $this->getUser()->getEmail();
        }
        return $this->_get( 'email' );

    } // PluginDinUserFeedback::getEmail()


    /**
     * Get firstname (nickname)
     * 
     * @return  string  Sender nickname or firstname
     */
    public function getFirstname()
    {

        if ( $this->_get( 'user_id' ) )
        {
            return $this->getUser()->getNickname();
        }
        return $this->_get( 'firstname' );

    } // PluginDinUserFeedback::getFirstname()

} // PluginDinUserFeedback

//EOF