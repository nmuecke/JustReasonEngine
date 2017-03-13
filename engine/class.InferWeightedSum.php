<?php
/* vim: set expandtab tabstop=3 shiftwidth=3 softtabstop=3 foldmethod=marker: */
// {{{ copyright and disclaimer 
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
// {{{ Include Files
/** 
 * Include Files
 */
require_once( "class.InferMethod.php" );
// }}}
/**
 * class InferWeightedSum/var/www/html/web_dev/JustReason/JustReason-3.0/docs/JustReason_Installer/_installer---justReasoneInstaller.php.html
 *  Infers Nodes by using a system of weighs and thresholds.
 *  The weights of the set claims from a node's arguments are
 *  sumed and then the total is compared to the node's own claim
 *  to see which of the claim's threshold best fit the total weight.
 * @package JustReason_Engine
 * @subpackage Inference
 */
class InferWeightedSum extends InferMethod{

   // {{{ __CONSTRUCT( & $node )
   /**
    * Constructor for InferWeightedSum class
    *
    * @access public
    *
    * @param ArgumentNode
    * @return void
    */
   public function __CONSTRUCT( & $node ){
      $this->node =& $node;
      }


   // }}}
   // {{{ calculateWeight( )
   /**
    * Calculates the total weight from the set claim valuse
    *
    * @access private
    *
    * @param void
    * @return double
    */
   private function calculateWeight( ){
      $weight = 0;
      for( $xx = 0; $xx < $this->node->numArguments(); $xx++ ){
           $argument =& $this->node->getArgumentAt( $xx );

           //if( isset($argument->getSelectedChoice()) && !is_null( $argument->getSelectedChoice() )){
           $id = parent::getSetClaimId( $argument );
           if(  $id  != 'unset' ){

              //$choice =& $argument->getClaimAt( $argument->getClaimId() );
              //echo "Curent Weight: ".$weight." + Weight of Choice: ".$choice->getWeight()."<br>\n";
              //$weight +=  $choice->getWeight();
              $weight +=  $argument->getClaimAt( $id )->getWeight();
              }
           }
        return $weight;
        }


   // }}}
   // {{{ infer()
   /**
    * Infers the node 
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function infer(){
      self::inferNode( self::calculateWeight( ) );
      }
    

   // }}}
   // {{{ inferNode( $weight )
   /**
    * Uses the total weight to infer which claim fits the thresholds the best
    *
    * @access private
    *
    * @param double
    * @return void
    */
   private function inferNode( $weight ){
      $threshold = 'unset';
      $order = array( );

      for( $xx = 0; $xx < $this->node->numClaims(); $xx++ ){
           $orderThreshold[$this->node->getClaimAt( $xx )->getThreshold()] = $xx;
           }

      krsort( $orderThreshold );
      $this->debuggingData .= "<b>Node ID:</b> ".$this->node->getId()."\n".
                           "<table>\n".
                           "   <th><td>Claim Id:</td><td>Threshold:</td><td>Weight:</td><td>selected</td></th>\n";
      foreach( $orderThreshold as $xx ){
         $claim = & $this->node->getClaimAt( $xx );
          $this->debuggingData .= "   <tr><td>".$xx."</td><td>".$claim->getId()."</td><td>".$claim->getThreshold()."</td><td>".$weight."</td>";
          if( $weight <= $claim->getThreshold() || $threshold == 'unset'   ){
             $threshold = $claim->getThreshold();
             $this->inferedValue =  $xx;
             $this->debuggingData .= "<td>TRUE</td></tr>\n";
             }
          else  $this->debuggingData .= "<td>FALSE</td></tr>\n";
          }
       $this->debuggingData .= "</table>";

       }


   // }}}
   }
?>
