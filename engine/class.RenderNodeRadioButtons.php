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
require_once( "class.RenderNodeText.php" );
// }}}
/**
 * class RenderNodeRadioButtons
 *  Renders a node using radio buttons to display the claims
 * @package JustReason_Engine
 * @subpackage Display
 */
class RenderNodeRadioButtons extends RenderNodeText {
   // {{{ __CONSTRUCT( & $node )
   /**
    * Constructor for RenderNodeRadioButtons
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
         if( $this->node->getClaimId() !== NOT_SET ){
            $html .= "<b class = 'claimvalue'>".$this->node->getClaimAt( $this->node->getClaimId() )->getClaim()."</b>";
            }
         else{
            $html .= "<ul  class='claimvalue' style='list-style-type:none;'> ";
            for( $xx = 0; $xx < $this->node->numClaims(); $xx++  ){
     
         
               if( $this->node->getClaimId() == $xx ){
                  $html .= "<li><input type     = 'radio' ".
                           "           name     = 'SELECT_".$this->node->getId()."' ".
                           "           value    = '".$xx."'".
                           "           id       = '".$this->node->getId()."_".$xx."'".
                           "           selected = 'true'".
                           "       >".
                           "<label for = '".$this->node->getId()."_".$xx."' >".
                           $this->node->getClaimAt( $xx )->getClaim().
                           "</label>".
                           "</li>";
                  }
               else{
                  $html .= "<li><input type     = 'radio' ".
                           "           name     = 'SELECT_".$this->node->getId()."' ".
                           "           value    = '".$xx."'".
                           "           id       = '".$this->node->getId()."_".$xx."'".
                           "       >".
                           "<label for = '".$this->node->getId()."_".$xx."' >".
                           $this->node->getClaimAt( $xx )->getClaim().
                           "</label>".
                           "</li>";
                  }
               }

            $html .= "</ul>";
            }
         }
      return $html;
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
      return array( "prefix"    => parent::getPrefix(),
                    "claims"    => self::getClaims(),
                    "suffix"    => parent::getSuffix(),
                    "notsure"   => parent::getNotSure(),
                    "relivance" => parent::getRelivance(),
                    "other"     => parent::getOther()
                   );
        
      }


   // }}}
   }

?>
