<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
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

// {{{ Include Files
/**
 * Include files
 */
require_once( "class.Node.php" );
// }}}
/**
 * class ConclusionNode
 *  Conclusion nodes represent the end point in a decision tree.
 *  They should be used to give the user some sort of resolution
 *  to the advice they have been given.
 * @package JustReason_Engine
 * @subpackage Nodes
 */
class ConclusionNode extends Node {
   // {{{  __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $claims = null, $relevance = null )
   /**
    * Constructor for ConclusionNode 
    *
    * @access  public
    *
    * @param Node        the nodes parent 
    * @param string      id of the node
    * @param string      title of the node
    * @param string      prefix for the node argument
    * @param string      suffix for the node argument
    * @param string      relevance of the node's argument
    * @param Claim|array an array of claim ojbects or juat a claim object.
    * @return void
    */
   public function __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $claims = null, $relevance = null ){
      parent::setId( $id );
      parent::setTitle( $title );
      parent::setPrefix( $prefix );
      parent::setSuffix( $suffix );
      parent::setClaimId( NOT_SET );
      parent::setParentNode( $parent );
      self::setClaims( $claims );
      parent::setRelevance( $relevance );
      }

   // }}}
   // {{{  setClaims( & $c ) 
   /**
    * Accepts a claim to add to the Claims array
    * Conclusion Nodes dont have Claims, so the Claim is
    * discarded.
    *
    * @access public
    *
    * @param Claim
    * @return void
    */
   public function setClaims( & $c ){
      $this->claims = "";
      }
    
   // }}}
   // {{{  setClaimId( $c ) 
   /**
    * Accepts a claimId to set as the active claim and then discards it.
    * As Conclusion Nodes dont have Claims, this function is redefinded to
    * make sure that the claim id can not be set to any thing but the NOT_SET
    * value.
    * discarded.
    *
    * @access public
    *
    * @param int
    * @return void
    */
   public function setClaimId( & $c ){
      $this->claimId = NOT_SET;
      }
    
   // }}}
   }
?>
