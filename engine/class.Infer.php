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
require_once( 'class.InferWeightedSum.php' );
require_once( 'class.LinkedException.php'  );
// }}}
/**
 * class Infer
 *  Handles the inference of Tree's ArgumentNodes
 * @package JustReason_Engine
 * @subpackage Inference
 */
class Infer{
   // {{{ Class Variables
   /**
    * The method to be used for the infering
    * @var string   
    * @access private
    */
   private $inferenceMethod;
   /**
    * A reference to the tree contation the nodes to be infered
    * @var Tree
    * @access private
    */
   private $tree; 


   // }}}
   // {{{ __CONSTRUCT( & $tree, $inferenceMethod = 'InferWeightedSum' )
   /**
    * Constructor for the Infer class
    *
    * @access public
    *
    * @param Tree
    * @param string the method to use to infer the valuse
    * @return void Throws exception on error
    */
   public function __CONSTRUCT( & $tree, $inferenceMethod = 'InferWeightedSum' ){
      if( is_subclass_of( $inferenceMethod, 'InferMethod' )){
         $this->inferenceMethod = $inferenceMethod;
         }
      else{
         throw new LinkedException( "Infer: Inference method is not a valid type" );
         }
      $this->tree = $tree;
      }
   

   // }}}
   // {{{ inferClaim( & $argNode, $debug = false )
   /**
    * Infers a node from it's argument nodes
    *
    * @access public
    *
    * @param ArgumentNode
    * @param boolian display debuggin data
    * @return void
    */
   public function inferClaim( & $argNode, $debug = false ){
      if( self::nodeCanBeInfered( $argNode ) ){
         $inf = new $this->inferenceMethod( $argNode );
         $inf->infer();
         $argNode->setClaimId( $inf->getInferedValue() ); 
         $argNode->setClaimDefault( $inf->getInferedValue() ); 
         if( $debug == true ){
            echo $inf->getDebuggingData();
            }
         }
      }
   // }}}
   // {{{ inferFromLeaf( & $node )
   /**
    * Infers the suplyed node starting at the leaf nodes 
    *
    * @access public
    *
    * @param Node
    * @return void
    */
   public function inferFromLeaf( & $node ){
      if( !is_subclass_of( $node, "Node" ) ){
         throw new LinkedException( "Infer: node pased to be infered is not a node!" );
         }
      if( $node->numArguments() > 0 ){
         for( $xx = 0; $xx < $node->numArguments(); $xx++ ){
            self::inferFromLeaf( $node->getArgumentAt( $xx ) );
            }
         self::inferClaim( $node );
         }
      }


   // }}}
   // {{{ inferTree( )
   /**
    * Infers all of an argument tree from the leaf nodes to the root node 
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function inferTree( ){
      self::inferFromLeaf( $this->tree->getArgumentRoot() );
      }


   // }}}
   // {{{ nodeCanBeInfered( & $node ) [Final]
   /**
    * Test that a node can be infered
    *
    * @access protected
    *
    * @param ArgumentNode
    * @return boulian false on if cannot be infered
    */ 
   protected function nodeCanBeInfered( & $node ){
      if( $node->numArguments() > 0 ){
         foreach( $node->getArguments() as $arg ){
            if( $arg->getClaimId() === NOT_SET && $arg->getClaimDefault() == null  ){
               return false;
               }
            }
         return true;
         }
      return false;
      }

   // }}}

   }
?>
