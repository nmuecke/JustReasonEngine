<?php
/* vim: set expandtab tabstop=3 shiftwidth=3 softtabstop=3 foldmethod=marker: */
// {{{ copywright & disclaimer
/**
 *   JustReason - A decision support program and associated tools.         
 *                                                                         
 *   This program is free software; you can redistribute it and/or modify  
 *   it under the terms of the GNU General Public License as published by  
 *   the Free Software Foundation; either version 2 of the License, or     
 *   (at your option) any later version.                                   
 *                                                                         
 *   This program is distributed in the hope that it will be useful,       
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
 *   GNU General Public License for more details.                          
 *                                                                         
 *   You should have received a copy of the GNU General Public License     
 *   along with this program; if not, write to the                         
 *   Free Software Foundation, Inc.,                                      
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.   
 *
 *
 * @package JustReason_Engine 
 * @version 4.0
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2007, Nial Muecke
 *
 */

// }}}
/**
 * class InferMethod
 *  Defines the standars function and propertis of 
 *  inference methods.
 * @package JustReason_Engine
 * @subpackage Inference
 */
abstract class InferMethod{

   // {{{ Class Variables
   /**
    * The value that was infered by the inference method
    * @var int   
    * @access protected
    */
   protected $inferedValue;

   /**
    * The node to infer
    * @var ArgumentNode   
    * @access protected
    */
   protected $node;

   /**
    * HTML Data that contain the weight/Threshold debuggin data
    * @var String   
    * @access protected
    */
   protected $debuggingData;


   // }}}
   // {{{ __CONSTRUCT( & $node ) [Abstract]
   /**
    * Definition for Inference methods constructors
    *
    * @access public
    *
    * @param ArgumentNode
    * @return void
    */
   abstract public function __CONSTRUCT( & $node );
   // }}}
   // {{{ getInferedValue() [Final]
   /**
    * Returns the infered value
    *
    * @access public
    *
    * @param void
    * @return int|string on error (unset)
    */
   final public function getInferedValue(){
      if( isset( $this->inferedValue )) {
         return $this->inferedValue;
         }
      else return 'unset';
      }
   // }}}
   // {{{ infer() [Abstract]
   /**
    * Defined the method that initiates the inference procedure
    *
    * @access public
    *
    * @param void
    * @return void 
    */
   abstract public function infer();
   // }}}
   // {{{ getSetClaimId()
   /**
    * Returns the value of the claim id to use as the set claim
    *
    * @access protected
    *
    * @param void
    * @return int|string returns 'unset' if no claim id is set 
    */
   protected function getSetClaimId( $node ){
      if( $node->getClaimDefault() != null ){
         return $node->getClaimDefault(); 
         }
      else if ( $node->getClaimId() !== NOT_SET ){
         return $node->getClaimId();
         }
      return 'unset';
      }

   // }}}
   // {{{ getDebugginData() [Final]
   /**
    * Returns the html debuggin data
    *
    * @access protected
    *
    * @param void
    * @return String of HTML data
    */
   final public function getDebuggingData( ){
      return $this->debuggingData;
      }

   // }}}
   }


?>
