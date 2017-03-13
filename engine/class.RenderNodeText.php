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
 * Include Files
 */
require_once( "class.RenderNode.php" );
// }}}
/**
 * class RenderNodeText
 *  Renders a node using text only
 *  *note that you can use this node to set claims it is only for displaying a node
 * @package JustReason_Engine
 * @subpackage Display
 */
class RenderNodeText extends RenderNode {
   // {{{ __CONSTRUCT( & $node )
   /**
    * Constructor for RenderNodeText class
    *
    * @access public
    *
    * @param Node
    * @return void
    */
   public function __CONSTRUCT( & $node ){
      $this->node = $node;
      }


   // }}}
   // {{{ __DESTRUCT()
   /**
    * Destructor for RenderNodeText
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function __DESTRUCT(){}


   // }}}
   // {{{ getClaims()
   /**
    * Gets and formats the claim proterties 
    * 
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   public function getClaims(){
      $html = "";
      if(method_exists( $this->node, "numClaims") ){
         $html .= "<ul>";
         for( $xx = 0; $xx < $this->node->numClaims(); $xx++  ){
            if( $this->node->getClaimId() == $xx ){
               $html .= "<li><b>".$this->node->getClaimAt( $xx )->getClaim()."</b></li>";
               }
            else{
               $html .= "<li>".$this->node->getClaimAt( $xx )->getClaim()."</li>";
               }
            }

         $html .= "</ul>";
         }
      return $html;
      }


   // }}}
   // {{{ getNotSure()
   /**
    * Gets and formats the notsure link
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   public function getNotSure(){
      if( $this->node->getClaimId() !== NOT_SET ){
         return "<button ".
                "  type  = 'submit' ".
                "  value = '".$this->node->getId()."' ".
                "  class = 'notsure' ". 
                "  name  = 'ChangeClaim' >".
                "Change Claim?".
                "</button>";
         }
      else if( method_exists( $this->node, "numArguments" ) && $this->node->isLeaf() != true ){
         return "<button ".
                "  type  = 'submit' ".
                "  value = '".$this->node->getId()."' ".
                "  class = 'notsure' ". 
                "  name  = 'NotSure' >".
                "Not Sure?".
                "</button>";
         } 
      return "";
      }

   // }}} 
   // {{{ getOther()
   /**
    * Gets and formats other properties that the class might have
    *   
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   public function getOther(){
      return "";
      }

   // }}}
   // {{{ getPrefix()
   /**
    * Gets and formats other properties that the class might have
    *   
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   public function getPrefix(){ 
      return $this->node->getPrefix();
      }


   // }}}
   // {{{ getRelivance()
   /**
    * Gets and formats the relivance node value 
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   public function getRelivance(){
      return $this->node->getRelevance();
      }


   // }}}
   // {{{ getSuffix()
   /**
    * Gets and formats the suffix of a node
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   public function getSuffix(){
      return $this->node->getSuffix();
      }


   // }}}
   // {{{ toArray() 
   /**
    * Creats an array from the node properties  
    *
    * @access public
    *
    * @param void
    * @return array of HTML strings
    */
   public function toArray(){
      return array( "prefix"    => self::getPrefix(),
                    "claims"    => self::getClaims(),
                    "suffix"    => self::getSuffix(),
                    "notsure"   => self::getNotSure(),
                    "relivance" => self::getRelivance(),
                    "other"     => self::getOther()
                   );       
      }


   // }}}
   // {{{ toHtml()
   /**
    * Returns a html string of the node properties 
    *
    * @access public
    *
    * @param void
    * @return string of HTML
    */
   public function toHtml(){
      $html = "<table><tr>";
      foreach( self::toArray() as $td ){
         $html .= "<td>".$td."</td>"; 
         }
      $html .="<table></tr>";
      }
    

   // }}}
   }

?>
