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


/**
 * class RenderNode
 *  Defines the basic properties of how a node should be rendered
 * @package JustReason_Engine
 * @subpackage Display
 */
abstract class RenderNode {
   
   // {{{ class veriables
   /**
    * Handle to the node to be rendered
    * @var Node
    * @access protected
    */
   protected $node;

   // }}}
   // {{{ __CONSTRUCT( & $node ) [Abstract]
   /**
    * Defines the properties of the constructing of node rendere classes 
    *
    * @access public
    *
    * @param Node
    * @return void
    */
   abstract public function __CONSTRUCT( & $node );

   // }}}
   // {{{ __DESTRUCT() [Abstract]
   /**
    * Defines the properties of the class destructor
    *
    * @access public
    *
    * @param void
    * @return void
    */
   abstract public function __DESTRUCT();

   // }}}
   // {{{ getClaims() [Abstract]
   /**
    * Gets and formats the claim proterties 
    * 
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   abstract public function getClaims();

   // }}}
   // {{{ getNotSure() [Abstract]
   /**
    * Gets and formats the notsure link
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   abstract public function getNotSure();

   // }}}
   // {{{ getOther() [Abstract]
   /**
    * Gets and formats other properties that the class might have
    *   
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   abstract public function getOther();

   // }}}
   // {{{ getPrefix() [Abstract]
   /**
    * Gets and formats the node Prefix
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   abstract public function getPrefix();

   // }}}
   // {{{ getRelivance() [Abstract]
   /**
    * Gets and formats the relivance node value 
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   abstract public function getRelivance();

   // }}}
   // {{{ getSuffix() [Abstract]
   /**
    * Gets and formats the suffix of a node
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   abstract public function getSuffix();

   // }}}
   // {{{ toArray() [Abstract]
   /**
    * Creats an array from the node properties  
    *
    * @access public
    *
    * @param void
    * @return array of HTML strings
    */
   abstract public function toArray();

   // }}}
   // {{{ toHtml() [Abstract]
   /**
    * Returns a html string of the node properties 
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   abstract public function toHtml();

   // }}}
   }

?>
