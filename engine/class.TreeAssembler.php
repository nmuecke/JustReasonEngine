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
require_once( "class.Tree.php" );
require_once( "class.NodeFactory.php" );
require_once( "class.LinkedException.php" );
// }}}
// {{{ Defined Constants
/** 
 * Constants
 */
define( "CREATE_AT_START"        , 1 ); // this will creat all the trees and their nodes when first run
define( "CREATE_ON_DEMAND"       , 2 ); // this will creat nodes as they are requested
define( "CREATE_TREE_ON_DEMAND"  , 3 ); // this will creat an entire tree of nodes on demand
// }}}
/**
 * class TreeAssembler
 *
 * @package JustReason_Engine
 * @subpackage Tree
 */
class TreeAssembler{
   // {{{ Class Variables
   /**
    * An array of node that form the root of diferent trees
    * @var array   
    * @access private
    */
   private $rootNodes     = array();

   /**
    * An array of Trees
    * @var array   
    * @access private
    */
   private $trees         = array();

   /**
    * A link to a node factory
    * @var NodeFactory   
    * @access private
    */
   private $factory;

   /**
    * The mode that trees will be asembled with
    * @var int    
    * @access private
    */
   private $mode;

   /**
    * The database variables
    * @var array
    * @access private
    */
   private $dbVars;


   // }}}
   // {{{ __CONSTRUCT( array $dbVars, $mode = CREATE_AT_START )
   /**
    * Constructor for TreeAssembler calss
    *
    * @access public
    *
    * @param array of database variables
    * @return int the mode that the trees should be built in
    *   Valid modes are: CREATE_AT_START, CREATE_ON_DEMAND and CREATE_TREE_ON_DEMAND
    */
   public function __CONSTRUCT( array $dbVars, $mode = CREATE_AT_START ){
      if( !isset( $dbVars['username'] )
        && !isset( $dbVars['password'])
        && !isset( $dbVars['database'])
        && !isset( $dbVars['tbName'])
        && !isset( $dbVars['phptype'])
        && !isset( $dbVars['hostspec']) ){
          throw new LinkedException( "TreeAssembler: Some or all of database variable were not provided!" );
          }

      $db =& MDB2::connect( $dbVars );

      if (PEAR::isError($db)) {
          throw new LinkedException( "TreeAssembler: DB conection: ". $db->getMessage() );
          }
      $db->setFetchMode(MDB2_FETCHMODE_OBJECT);

      $res =&  $db->query( "SELECT * ".
                           "FROM ".$dbVars['globalPrefix'].$dbVars['tbName']['rootNodes']." ".
                           "ORDER BY ".$dbVars['globalPrefix'].$dbVars['tbName']['rootNodes'].".order DESC"  );

      if (PEAR::isError($res)) {
          throw new LinkedException( "TreeAssembler: Query: ". $res->getMessage() );
          }

      if( $res->numRows() < 1 ){
         throw new LinckedException( "TreeAssembler: The rootNodes table is empty! Unable to continue." );
         return false;
         } 

      while( $row = $res->fetchRow() ){
         array_push( $this->rootNodes, $row->id );
         }

      $db->disconnect();

      $this->mode   = $mode;
      $this->dbVars = $dbVars;
      }


   // }}}
   // {{{ addArguments( & $node )
   /**
    * Adds ArgumentNodes to a node 
    *
    * @access public
    *
    * @param Node
    * @return void
    */
   public function addArguments( & $node ){
      return self::getFactory()->addArgumentsTo( $node );
      }
   

   // }}}
   // {{{ addArgumentsRecursively( & $node )
   /**
    * Adds ArgumentsNodes to a node all the way to the leaf nodes 
    *
    * @access protected
    *
    * @param Node
    * @return void
    */
   protected function addArgumentsRecursively( & $node ){
      if( self::addArguments( $node ) == true ){
         for( $xx = 0; $xx < $node->numArguments(); $xx++ ){
            $argNode =& $node->getArgumentAt( $xx );
            self::addArgumentsRecursively( $argNode );
            }
         }
      }


   // }}}
   // {{{ addChildren( & $node )
   /**
    * Adds a Nodes child nodes to it 
    *
    * @access public
    *
    * @param Node
    * @return void
    */
   public function addChildren( & $node ){
      return self::getFactory()->addChildrenTo( $node );
      }



   // }}}
   // {{{ addChildrenRecursively( & $node )
   /**
    * Adds the child nodes to a node and add the child childs nodes an so on
    *
    * @access private
    *
    * @param Node
    * @return void
    */
   private function addChildrenRecursively( & $node ){
      if( self::addChildren( $node ) == true ){
         for( $xx = 0; $xx < $node->numChildren(); $xx++ ){
            $argNode =& $node->getChildAt( $xx );
            self::addChildrenRecursively( $argNode );
            }
         }
      }
   // }}}
   // {{{ addNode( $id, & $perant )
   /**
    * Adds a Node to the tree
    *
    * @access public
    *
    * @param string node id
    * @param Node the parent node of the node to be added
    * @return void Thows LinkedException on error
    */
   public function addNode( $id, & $perant ){
      $node =& self::getFactory()->make_node( $id, $perant );

      if( !isset( $this->currentTree ) ){
         if( !self::newTree() ){
            throw new LinkedException( "TreeAssembler: No new tree created!" );
            }
         }
      else{
         }
      }


   // }}}
   // {{{ getFactory()
   /**
    * Return the returns the node factory object an create one
    *
    * @access public
    *
    * @param void
    * @return NodeFactory
    */
   public function & getFactory(){
       $fac =& new NodeFactory( $this->dbVars );
       return $fac;
       }


   // }}}
   // {{{ getTrees()
   /**
    * Return the tree object 
    *
    * @access public
    *
    * @param void
    * @return Tree
    */
   public function & getTrees(){
       return $this->trees; 
       }


   // }}}
   // {{{  newTree( )
   /**
    * Creats a new tree or the next tree if a tree already exists 
    *
    * @access public
    *
    * @param void
    * @return boulian Throws linkedException on error
    # @TODO allow for linking of multiple trees
    */
   public function newTree( ){
       if( ($rootNodeId = array_pop( $this->rootNodes )) != null ){

          if(($rootNode =& self::getFactory()->make_node( $rootNodeId )) == false){
             throw newLinkedException( "TreeAssembler: Unalble to creat rootNode!" );
             return false;
             }
          
          array_push( $this->trees, new Tree( $rootNode ) );

          switch( $this->mode ){
             case CREATE_ON_DEMAND:
                $this->trees[sizeof($this->trees)-1]->setTreeAssembler( $this ); 
                break;
             case CREATE_TREE_ON_DEMAND:
                self::buldTree( $rootNode );
                break;
             default:
             case CREATE_AT_START:
                self::buldTree( $rootNode );
                self::newTree();
                break;
             }
          return true;
          }
       return false;
       }


   // }}}
   // {{{ buldTree( $parentNode )
    private function buldTree( $parentNode ){
      self::addArgumentsRecursively( $parentNode );
      if( self::addChildren( $parentNode ) == true ){
         for( $xx = 0; $xx < $parentNode->numChildren(); $xx++ ){
            $node =& $parentNode->getChildAt( $xx );
            self::buldTree( $node );
            }
         }

        }
   // }}}
   }

?>
