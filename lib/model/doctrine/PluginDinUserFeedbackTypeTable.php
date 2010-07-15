<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class for performing query and update operations for DinUserFeedbackType model table
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class PluginDinUserFeedbackTypeTable extends dinDoctrineTable
{

    /**
     * Returns an instance of PluginDinUserFeedbackTypeTable
     * 
     * @return  PluginDinUserFeedbackTypeTable
     */
    public static function getInstance()
    {

        return Doctrine_Core::getTable( 'PluginDinUserFeedbackType' );

    } // PluginDinUserFeedbackTypeTable::getInstance()

} // PluginDinUserFeedbackTypeTable

//EOF