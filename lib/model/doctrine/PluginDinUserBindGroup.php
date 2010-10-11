<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class that represents a record of DinUserBindGroup model
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
abstract class PluginDinUserBindGroup extends BaseDinUserBindGroup
{

    /**
     * Pre insert
     * 
     * @param   Doctrine_Event  $event
     * @return  void
     */
    public function preInsert( $event )
    {

        if ( !$this->getCreatedAt() )
        {
            $this->setCreatedAt( date( 'Y-m-d H:i:s', time() ) );
        }
        if ( !$this->getCreatorId() )
        {
            $this->setCreatorId( sfContext::getInstance()->getUser()->getUserId() );
        }

    } // PluginDinUserBindGroup::preInsert()

} // PluginDinUserBindGroup

//EOF