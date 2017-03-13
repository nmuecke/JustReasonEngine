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
require_once( "class.ArgumentNode.php" );
// }}}
/**
 * class DecisionNode
 *  Decision nodes form the base of argument trees and are the 
 *  Main type of node found in a decision tree. They differ from
 *  Argument Nodes as their claims take the user to a Decision
 *  Node or Conclusion node.
 * @package JustReason_Engine
 * @subpackage Nodes
 */
class DecisionNode extends ArgumentNode{
   // {{{ Class Veriables
   /**
    * An array of Claim node objects that form the branched
    * /arcs of an decision tree
    * @var array
    * @access protected
    */
   protected $children = array();   

   // }}}
   // {{{ __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $relevance = null, $claims = null )
   /**
    * Constructor for creating an DecisionNode
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
   function __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $relevance = null, $claims = null ){
      parent::setId( $id );
      parent::setTitle( $title );
      parent::setPrefix( $prefix );
      parent::setSuffix( $suffix );
      parent::setClaimId( NOT_SET );
      parent::setParentNode( $parent );
      parent::setRelevance( $relevance );
      if( $claims != null ){
         parent::setClaims( $claims );
         }
      }
    

   // }}}
   // {{{ addChild( & $node, $pos = 0 )
   /**
    * Adds a node as a child of the current node
    *   Adds a DecisionNode or ConclusionNode as children of the 
    *   current node to for a decision tree
    * @access public
    *
    * @param Node
    * @param int the array index to start searching from 
    * @return boulian Throws LinkedException on error
    */
   public function addChild( & $node, $pos = 0 ){
      if( is_object( $node ) && is_subclass_of( $node, 'Node' ) ){
         for( $xx = $pos; $xx < parent::numClaims(); $xx++ ){
            if( $node->getId() == parent::getClaimAt( $xx )->getWeight() && !isset( $this->children[$xx] )){
               $this->children[$xx] =& $node; 
               return true;
               }
            }
         throw new LinkedException( "Unable to add ChildNode (id:".$node->getId()."), as it matches not claim in DecisionNode (id: ".$this->getId().")!" );
         return false;
         }
      else{
         throw new LinkedException( "Unable to add ChildNode to DecisionNode (id: ".$this->getId().")!" );
         return true;
         }
      return true;
      } 


   // }}}
   // {{{ function getChildren()
   /**
    * Returns the children of the node 
    *   
    * @access public
    *
    * @param void
    * @return array of Nodes
    */
   public function & getChildren(){
      return $this->children;
      }


   // }}}
   // {{{ getChildAt( $index )
   /**
    * Returns a Node at a given array index 
    *
    * @access public
    *
    * @param int array index
    * @return Node|false on error
    */
   public function & getChildAt( $index ){
      if( isset( $this->children[$index] ) ){
         return $this->children[$index];
         }
      else{
         $false = false;
         return $false;
         }
      }


   // }}}
   // {{{ getChild( $claimId )
   /**
    * Returns a Node at a given with the given claim id
    *
    * @access public
    *
    * @param int claim id
    * @return Node
    * @throws LinkedException node not found!
    */
   public function & getChild( $id ){
      // just incase the array index and $id are the same
      if( isset( $this->children[$id] ) && $this->children[$id]->getId() == $id ){
         return $this->children[$id];
         }
      // have to search each of the claims and compare the ids
      else{
         for( $xx = 0; ($node =& self::getChildAt( $xx )) != false; $xx++ ){
            if( $node->getId() == $id ){
                return $node;
                }
            }
         }
      // else id is not found
      throw new LinkedException( "DecissionNode: Child node id (".$id.") was not found!" );
      }


   // }}}
   // {{{ numChildren()
   /**
    * Returns the Number of children nodes in the node  
    *
    * @access public
    *
    * @param void
    * @return int >= 0;
    */
   public function numChildren(){
      if( isset( $this->children ) ){
         return sizeof( $this->children );
         }
      else{
         return 0;
         }
      }

   // }}}
   }


?>
