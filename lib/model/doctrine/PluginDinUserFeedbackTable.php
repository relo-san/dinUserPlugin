<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class for performing query and update operations for DinUserFeedback model table
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class PluginDinUserFeedbackTable extends dinDoctrineTable
{

    /**
     * Returns an instance of PluginDinUserFeedbackTable
     * 
     * @return  PluginDinUserFeedbackTable
     */
    public static function getInstance()
    {

        return Doctrine_Core::getTable( 'PluginDinUserFeedback' );

    } // PluginDinUserFeedbackTable::getInstance()

} // PluginDinUserFeedbackTable

//EOF