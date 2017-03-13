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
require_once( "class.LinkedException.php" );
// }}}
/**
 * class Claim
 *  Claims are used by both Argument Nodes and Decision Nodes 
 *  as the posible assertion that can be made about a given 
 *  argument or decision.
 * @package JustReason_Engine
 * @subpackage Nodes
 */
class Claim {
   // {{{ Class Varibles
   /**
    * Id for a given claim
    * @var string
    * @access protected
    */
   protected $id;

   /**
    * The claim that is to be made
    * @var string
    * @access protected
    */
   protected $claim;

   /**
    * The strength of the claim for a given argument or the Node object that is to be the nexed node 
    * @var double|Node
    * @access protected
    */
   protected $weight;

   /**
    * The point at which the claim should be infered 
    * @var double
    * @access protected
    */
   protected $threshold;



   // }}}
   // {{{ __CONSTRUCT( $id, $claim, $weight = null , $threshold = null )
   /**
    * Creates a new claim object 
    *
    * @access  public
    *
    * @param string
    * @param string
    * @param doouble|Node
    * @param double
    * @return void
    */
   public function __CONSTRUCT( $id, $claim, $weight = null , $threshold = null ){
      self::setId( $id );
      self::setClaim( $claim );
      self::setWeight( $weight );
      self::setThreshold( $threshold );
      } 


   // }}}
   // {{{ setId( $id )
   /**
    * Sets the id for the claim 
    *
    * @access  public
    *
    * @param string
    * @return void
    */

   public function setId( $id ){
      $this->id = $id;
      } 


   // }}}
   // {{{ setClaim( $claim )
   /**
    * Sets the claim value for a claim 
    *
    * @access  public
    *
    * @param string
    * @return void
    */
   public function setClaim( $claim ){
      $this->claim = $claim;
      } 


   // }}}
   // {{{ setWeight( $weight )
   /**
    * Sets the weight for a node 
    *
    * @access  public
    *
    * @param double|Node
    * @return void
    */
   public function setWeight( $weight ){
      $this->weight = $weight;
      } 


   // }}}
   // {{{ setThreshold( $threshold )
   /**
    * Sets the threshold for a claim 
    *
    * @access  public
    *
    * @param double
    * @return void
    */
   public function setThreshold( $threshold ){
      $this->threshold = $threshold;
      } 


   // }}}
   // {{{ getId()
   /**
    * Returns the claim's id 
    *
    * @access  public
    *
    * @param void 
    * @return string
    */
   public function getId(){
      return $this->id;
      }


   // }}}
   // {{{ getClaim()
   /**
    * Returns the claim value 
    *
    * @access  public
    *
    * @param void
    * @return string
    */
   public function getClaim(){
      return $this->claim;
      }


   // }}}
   // {{{ getWeight()
   /**
    * Returns the weight of the claim or the next node in a decision tree
    *
    * @access  public
    *
    * @param void
    * @return double|Node
    */
   public function & getWeight(){
      return $this->weight;
      }


   // }}}
   // {{{ getThreshold()
   /**
    * Returns the threshold for a claim
    *
    * @access  public
    *
    * @param void
    * @return double
    */
   public function getThreshold(){
      return $this->threshold;
      }
  
   // }}}

  }

?>
