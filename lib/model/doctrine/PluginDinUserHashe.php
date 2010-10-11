<?php

/*
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Plugin class that represents a record of DinUserHashe model
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.model.doctrine
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
abstract class PluginDinUserHashe extends BaseDinUserHashe
{

    /**
     * Generate and set hashe key
     * 
     * @return  void
     */
    public function generateUkey()
    {

        return $this->_set( 'ukey', md5( microtime() ) );

    } // PluginDinUserHashe::generateUkey()


    /**
     * Get uri for hashe
     * 
     * @return  string  Uri hashe
     */
    public function getUriHashe()
    {

        return $this->getUserId() . '-' . $this->getDestinationType() . '-' . $this->getUkey();

    } // PluginDinUserHashe::getUriHashe()

} // PluginDinUserHashe

//EOF