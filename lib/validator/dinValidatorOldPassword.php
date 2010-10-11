<?php

/**
 * This file is part of the dinUserPlugin package.
 * (c) DineCat, 2010 http://dinecat.com/
 * 
 * For the full copyright and license information, please view the LICENSE file,
 * that was distributed with this package, or see http://www.dinecat.com/din/license.html
 */

/**
 * Old password validation class
 * 
 * @package     dinUserPlugin
 * @subpackage  lib.validator
 * @author      Nicolay N. Zyk <relo.san@gmail.com>
 */
class dinValidatorOldPassword extends sfValidatorBase
{

    /**
     * Configure validator
     * 
     * @param   array   Validator options [optional]
     * @param   array   Validator messages [optional]
     * @return  void
     */
    protected function configure( $options = array(), $messages = array() )
    {

        $this->addMessage( 'invalid', 'Old password is invalid.' );

    } // dinValidatorOldPassword::configure()


    /**
     * Clean input value
     * 
     * @param   mixed   $value  Input value
     * @return  mixed   Cleaned value
     */
    protected function doClean( $value )
    {

        $clean = (string) $value;

        if ( $user = Doctrine::getTable( 'DinUser' )
            ->find( sfContext::getInstance()->getUser()->getUserId() ) )
        {
            if ( $user->checkPassword( $value ) )
            {
                return $clean;
            }
        }
        throw new sfValidatorError( $this, 'invalid', array( 'value' => $value ) );

    } // dinValidatorOldPassword::doClean()

} // dinValidatorOldPassword

//EOF