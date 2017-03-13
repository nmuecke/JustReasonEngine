<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// {{{ copyright & disclaimer
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
/// }}}
// {{{ include files
/**
 * Include files
 */
require_once( "class.ArgumentNode.php" );
// }}}
/**
 * class Tree
 *  A tree is a colection of node.
 *  The tree class handles the navigation in the tree as well.
 * @package JustReason_Engine
 * @subpackage Tree
 */
class Tree{
   // {{{ Class Variables   
   /**
    * If set to true then a nodes value will be kept when the back button is used 
    * @var boulian   
    * @access private
    */
   private $browsable;

   /**
    * the number of level in to an argument tree 
    * @var int   
    * @access private
    */
   private $argumentLevel;


   /**
    * A handle to a tree assembles object if set
    * @var TreeAssembler   
    * @access private
    */
   private $treeAssembler;
   /**
    * A handle for the tree's root
    * @var Node   
    * @access private
    */
   private $rootNode;

   /**
    * A handle to the root of the current argument
    * @var Node   
    * @access private
    */
   private $argumentRoot;

   /**
    * A handle to the current node
    * @var Node   
    * @access private
    */
   private $currentNode;


   // }}}
   // {{{ __CONSTRUCT( & $rootNode )
   /**
    * Constructor for Tree class
    *
    * @access public
    *
    * @param Node the which is to be the root of the tree
    * @param boulian whether the tree should be browsable or not
    * @return void
    */
   public function __CONSTRUCT( & $rootNode, $browsable = 'true' ){
      self::setArgumentRoot( $rootNode );
      self::setCurrentNode( $rootNode );
      $this->rootNode =& $rootNode;            
      $this->argumentLevel = 0;
      $this->browsable = $browsable;
      }

   // }}}
   // {{{ argumentParent()
   /**
    * Navigate an argument node to it's perant node
    *
    * @access public
    *
    * @param void
    * @return boulian false if cannot be navigated
    */
   public function argumentParent(){
      if( $this->argumentLevel > 0 ){
         if( $this->currentNode->getParent() != "" && $this->argumentLevel > 1 ){
            self::setCurrentNode( $this->currentNode->getParent() );
            }
         $this->argumentLevel--;
         return true;
         } 
      return false;
      }


   // }}}
   // {{{ findChildNode( & $node, $nodeId )
   /**
    * Searches the decision tree for a node
    * 
    * @access public
    *
    * @param Node the node to start the search from
    * @param string the id of the node to find
    * @return Node|false if not found
    */
   public function findChildNode( & $node, $nodeId ){
      if( get_class( $node ) == "DecisionNode" || is_subclass_of( $node, "DecisionNode" ) ){
         for( $xx = 0; $xx < $node->numChildren(); $xx++ ){
            if( $node->getChildAt( $xx )->getId() == $nodeId ){
               return $node->getChildAt( $xx );
               }
            }
         }
      return false;
      }


   // }}}
   // {{{ findNode( $nodeId, & $startNode = null )
   /**
    * Searches a tree for a given node's id
    *
    * @access public
    *
    * @param string the Id of the node to find
    * @param Node the node to start looking from
    *   If no node is give the the search will start from the rootnode
    * @return Node|false if not found
    */
   public function & findNode( $nodeId, & $startNode = null ){
      if( $startNode == null ){
         $startNode =& $this->rootNode;
         }
      if( !is_subclass_of( $startNode, "Node" )){
         throw new LinkedException( "Tree: Trying to find node, but not searching a node!" );
         }

      // test if current node is the right node
      if( $startNode->getId() == $nodeId ){
         return $startNode;
         }
      // test to see if the childrent node contain the node
      else if( ( $node =& self::findChildNode( $startNode, $nodeId )) != false ){
         return $node;
         }
      // test to see if the subArguments contain the right node
      else if( ($node =& self::findSubNode( $startNode, $nodeId )) != false ){
         return $node;
         } 

      // chech the subArguments of the subArguments down to the leaf nodes
      if( method_exists( $startNode, "numArguments") && $startNode->numArguments() > 0 ){
         for( $xx = 0; $xx < $starteNode->numArgumetns(); $xx++ ){
            if( ( $node =& self::findNode( $nodeId, $startNode->getArgumentAt( $xx ) ) ) != false ){
               return $node;
               }
            }
         }
      // chech the children
      if( method_exists( $startNode, "numChildren" ) && $startNode->numChildren() > 0 ){
         for( $xx = 0; $xx < $startNode->numChildren(); $xx++ ){
            if( ( $node =& self::findNode( $nodeId, $startNode->getChildAt( $xx ) ) ) != false ){
               return $node;
               }
            }         
         }
       
      // faild to find the node
      $false = false;
      return $false;
      }


   // }}}
   // {{{ findSubNode( & $node, $nodeId )
   /**
    * Searched an argument tree for a node
    *
    * @access public
    *
    * @param Node the node to start searching from
    * @param the Id of the node to find
    * @return Node|false if node found
    */
   public function findSubNode( & $node, $nodeId ){
      if( get_class( $node ) == "ArgumentNode" || is_subclass_of( $node, "ArgumentNode" ) ){
         for( $xx = 0; $xx < $node->numArguments(); $xx++ ){
            if( $node->getArgumentAt( $xx )->getId() == $nodeId ){
               return $node->getArgumentAt( $xx );
               }
            }
         }
      return false;
      }


   // }}}
   // {{{ getArgumentRoot()
   /**
    * Returns the current active argument root node
    *
    * @access public
    *
    * @param void
    * @return Node
    */
   public function & getArgumentRoot(){
      return $this->argumentRoot;
      }

   // }}}
   // {{{ getCurrentNode()
   /**
    * Returns the current active node
    *
    * @access public
    *
    * @param void
    * @return Node
    */
   public function & getCurrentNode(){
      return $this->currentNode;
      }

   // }}}
   // {{{ getRootNode()
   /**
    * Return the root node of the tree
    *
    * @access public
    *
    * @param void
    * @return Node
    */
   public function & getRootNode(){
      return $thid->rootNode;
      }

   // }}}
   // {{{ inArgument()
   /**
    * Test to see if the tree is currently in an argument tree or not
    *
    * @access public
    *
    * @param void
    * @return boulian true if in an argument tree
    */
   public function inArgument(){
      if( $this->argumentLevel == 0 ){
         return false;
         }
      else{
         return true;
         }
      }

   // }}}
   // {{{ nextNode()
   /**
    * Moves to the next node in the decison tree
    *
    * @access public
    *
    * @param void
    * @return boulian false if error
    */
   public function nextNode(){
      //if( $this->currentNode->getClaimDefault() == null && $this->currentNode->getClaimId() !== NOT_SET  &&
      if( $this->currentNode->getClaimDefault() != null  &&
        ( is_subclass_of( $this->currentNode, "DecisionNode" ) ||
          is_object( $this->currentNode ) == "DecisionNode" ) ){

         if( isset( $this->treeAssembler ) && method_exists($this->treeAssembler, "addChildren") ){
            $this->treeAssembler->addChildren( $this->currentNode );
            }
         //self::setCurrentNode ( $this->currentNode->getChild( $this->currentNode->getClaimId() ));
         $this->currentNode->setClaimId( $this->currentNode->getClaimDefault() );
         self::setCurrentNode ( $this->currentNode->getChildAt( $this->currentNode->getClaimId() ));
         self::setArgumentRoot( $this->currentNode );
         return true;
         }
      return false;
      }


   // }}}
   // {{{ previosNode()
   /**
    * Move to the previos node in a Decison tree
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function previosNode(){
      if( $this->browsable != true ){
         $this->currentNode->setClaimId( NOT_SET );
         }
      if( $this->rootNode->getId() != $this->currentNode->getId() ){
         self::setCurrentNode( $this->currentNode->getParent() );
         }
      self::setArgumentRoot( $this->currentNode );
      }


   // }}}
   // {{{ subArgument( $argId )
   /**
    * Moved in to an node argument tree
    *
    * @access public
    *
    * @param string the nodes subargument node id
    * @return boulian false if not successfull
    */
   public function subArgument( $argId ){

      if( $argId == $this->currentNode->getId() ){
         if( isset( $this->treeAssembler ) && method_exists($this->treeAssembler, "addArguments") ) {
            $this->treeAssembler->addArguments( $this->currentNode );
            }
         $this->argumentLevel++;
         return true;
         } 
      else if( $this->currentNode->numArguments() > 0 ){ 
         self::setCurrentNode( $this->currentNode->getArgument( $argId ) );
         $this->argumentLevel++;
         return true;
         }
      else if( isset( $this->treeAssembler ) && method_exists($this->treeAssembler, "addArguments") ){
         $this->treeAssembler->addArguments( $this->currentNode );
         if( $this->currentNode->numArguments() >= 0 ){ 
            self::setCurrentNode( $this->currentNode->getArgument( $argId ) );
            $this->argumentLevel++;
            return true;
            }
         }
      return false;
      }


   // }}}
   // {{{ setCurrentNode( & $node )
   /**
    * Sets the current node to point to the node given
    *
    * @access protected
    *
    * @param Node to be the current node
    * @return void Throes LinkedException on error
    */
   protected function setCurrentNode( & $node ){
       if( is_object( $node ) && is_subclass_of( $node, 'node' )){
          $this->currentNode =& $node; 
          }
       else{
          throw new LinkedException( "Tree: Node is not a valid object (Current node Id: ".$this->currentNode->getId().")." );
          }
       }

   // }}}
   // {{{ setArgumentRoot( & $node )
   /**
    * Sets the current argument root node to point to the node given
    *
    * @access protected
    *
    * @param Node to be the current argument root node
    * @return void Throes LinkedException on error
    */
   protected function setArgumentRoot( & $node ){
       if( is_object( $node ) && is_subclass_of( $node, 'node' )){
          $this->argumentRoot =& $node; 
          }
       else{
          throw new LinkedException( "Tree: Node is not a valid object (Current node Id: ".$this->currentNode->getId().")." );
          }
       }

   // }}}
   // {{{ setTreeAssembler( & $ta )
   /**
    * Sets a handel to a TreeAssembler object
    *
    * @access public
    *
    * @param TreeAssembler
    * @return void
    */
   public function setTreeAssembler( & $ta ){
      if( is_object( $ta ) && get_class( $ta ) == "TreeAssembler" || is_subclass_of( $ta, "TreeAssembler" ) ){
         $this->treeAssembler =& $ta;
         }
      else{
          throw new LinkedException( "Tree: TreeAssembler passed to Tree is not a valid object" );
          }
      }
 

   // }}}
   // {{{ changeClaim( $nodeId ){
   /**
    * Unsets a set claimID of a node
    *
    * @access public
    *
    * @param string id of the node to unset the claim of
    * @return void
    */
   public function changeClaim( $nodeId ){
         if( $this->currentNode->getId() == $nodeId ){
            $this->currentNode->setClaimId( NOT_SET );
            }
         else{
            if(( $node =& self::findSubNode( $this->currentNode, $nodeId )) != false ){
               $node->setClaimId( NOT_SET );
               }
            }
         }
   // }}}
   }
?>
