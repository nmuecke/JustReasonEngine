<?php
/* vim: set expandtab tabstop=3 shiftwidth=3 softtabstop=3 foldmethod=marker: */
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
// }}}
// {{{ Include Files
/** 
 * Include Files
 */
require_once( "class.Node.php" );
require_once( "class.ArgumentNode.php" );
require_once( "class.DecisionNode.php" );
require_once( "class.ConclusionNode.php" );
require_once( "class.Claim.php" );

require_once( "MDB2.php" ); // PEAR DB abstration

require_once( "class.LinkedException.php" );
// }}}
/**
 * class NodeFactory
 *  A Factory for createing nodes
 * @package JustReason_Engine
 * @subpackage Nodes
 */
class NodeFactory{
   // {{{ Class Variables
   /**
    * A handle for a database linke
    * @var MDB2 a PEAR class  
    * @access private
    */
   private $db;

   /**
    * An array of database variables
    * @var array   
    * @access private
    */
   private $dbVars;

   /**
    * The table prefix to use 
    * @var string   
    * @access private
    */
   private $tablePrefix;

   // }}}
   // {{{  __CONSTRUCT( array $dbVars )
   /**
    * Constructor for NodeFactory Class 
    *
    * @access public
    *
    * @param array of database variables
    * @return void 
    * @throws LinkedException( "SessionIO_DB: Some or all of database variable were not provided!" )
    * @throws LinkedException( "NodeFactory:".$this->db->getMessage() );
    */
   public function __CONSTRUCT( array $dbVars ){
      if(  !isset( $dbVars['username'] )
        && !isset( $dbVars['password'])
        && !isset( $dbVars['database'])
        && !isset( $dbVars['tbName'])
        && !isset( $dbVars['phptype'])
        && !isset( $dbVars['hostspec'])
        && !isset( $dbVars['globalPrefix']) ){
          throw new LinkedException( "SessionIO_DB: Some or all of database variable were not provided!" );
          }

      $this->dbVars = $dbVars;
      $this->db =& MDB2::connect( $dbVars );

      if( PEAR::isError($this->db) ){
         throw new LinkedException( "NodeFactory:".$this->db->getMessage() );
         }
      else{
         //$this->db->setFetchMode(DB_FETCHMODE_ASSOC);
         $this->db->setFetchMode(MDB2_FETCHMODE_OBJECT);
         }
      }


   // }}}
   // {{{ __destruct()
   /**
    * Destructor for class NodeFactory 
    *   Disconnects from any activce db conection
    * @access public
    *
    * @param void
    * @return void
    */
   public function __destruct(){
      $this->db->disconnect();
      }


   // }}}
   // {{{ addArgumentsTo( & $node )
   /**
    * Adds arguments to an Argument or Decision Node 
    *
    * @access public
    *
    * @param ArgumentNode
    * @return boulian falce on error
    */
   public function addArgumentsTo( & $node ){
      if( is_subclass_of( $node, "ArgumentNode" ) || get_class( $node ) == "ArgumentNode"  && method_exists( $node, "addArgument" ) ){

         $res =& $this->db->query( "SELECT * FROM ".$this->tablePrefix.$this->dbVars['tbName'] ['genericArguments']." ".
                                   "WHERE parent = '".$node->getId()."'" );
         if( $res->numRows() < 1 ){
           return false;
           } 

         while( $row = $res->fetchRow() ){
            $node->setArguments( self::make_node( $row->id, $node ) );
            //$node->addArgument( self::make_node( $row->id, $node ) );
            }
         return true;
         }
      else{
         return false;
         }
      }


   // }}}
   // {{{ addChildrenTo( & $node )
   /**
    * adds A Child node to a Decison Node 
    *
    * @access public
    *
    * @param Node
    * @return boulian false if no node was added
    */
   public function addChildrenTo( & $node ){
      if( is_subclass_of( $node, "DecisionNode" ) || get_class( $node ) == "DecisionNode"  && method_exists( $node, "addChild" ) ){

         for( $xx = 0; $xx < $node->numClaims(); $xx++ ){
            $node->addChild( self::make_node( $node->getClaimAt( $xx )->getWeight(), $node ));
            }
         return true;
         } 
      else{
         return false; 
         }
      }

   // }}}
   // {{{ hasSubArguments( $id );
   /**
    * tests to see i a node has sub arguments
    *
    * access public
    * 
    * @param string node id
    * @param boulian true if it has
    */
   private function hasSubArguments( $id ){
      $res =& $this->db->query( "SELECT * FROM ".$this->tablePrefix.$this->dbVars['tbName']['genericArguments']." ".
                                "WHERE parent = '".$id."'" );
      if( $res->numRows() < 1 ){
        return false;
        }  
      return true;
      }


   // }}}
   // {{{ make_claim( & $node )
   /**
    * Makes a claim from the database  
    *
    * @access protected
    *
    * @param Node
    * @return boulian on error Throws LinkedException
    */
   protected function make_claim( & $node ){

      $claims = array();

      $res =& $this->db->query( "SELECT * FROM ".$this->tablePrefix.$this->dbVars['tbName']['claimValues']." \n".
                                "WHERE argument_id = '".$node->getId()."' \n ". 
                                "ORDER by ".$this->tablePrefix.$this->dbVars['tbName']['claimValues'].".order ASC" );

   
      if (PEAR::isError($res)) {
         throw new LinkedException( "NodeFactory: ".$res->getMessage() );
         return false;
         } 
      if( $res->numRows() != 0 ){
         while( $row = $res->fetchRow() ){
            if( isset( $row->type ) && is_subclass_of( (string)$row->type, "Claim" ) ){
               $claim = new $row->type($row->id, $row->claim, $row->weight, $row->threshold );
               $node->setClaims( $claim );
               }
            else{
               $claim = new Claim( $row->id, $row->claim, $row->weight, $row->threshold );
               $node->setClaims( $claim );
               }
            }
         return true;
         }
      return false;
      }


   // }}}
   // {{{ make_node( $id, & $parent = null )
   /**
    * Makes a node from the database 
    *
    * @access public
    *
    * @param  string id of the node to make
    * @param  Node that is the new node parent node
    * @return boulian Throws Linked Exception on error
    */
   public function & make_node( $id, & $parent = null ){

      if( $parent == null ){
         self::setTablePrefix( $id );
         }

      $res =& $this->db->query( "SELECT * FROM ".$this->tablePrefix.$this->dbVars['tbName'] ['genericArguments']." WHERE id = '".$id."'" );   

      if (PEAR::isError($res)) { 
         throw new LinkedException( "NodeFactory: ".$res->getMessage() );
         return false;
         }
      else if( $res->numRows() != 1 ){
         throw new LinkedException( "NodeFactory: Inconsystancy in table ".$this->tablePrefix.$this->dbVars['tbName'] ['genericArguments']."! \n". 
                              "             Number of row that mach node ID (".$id.") is ".$res->numRows().". \n".
                              "             It should be only 1!"  );
         return false;
         }      

      $row = $res->fetchRow();

      if( ($row->type = "" || $row->type == "AUTO" ) && $row->parent != "" ){
         $node = new ArgumentNode( $parent, $row->id, $row->title, $row->prefix, $row->suffix, $row->relevance );         
         self::make_claim( $node );
         }
      else if( $row->type == "" || $row->type == "AUTO" ){

         $node = new DecisionNode( $parent, $row->id, $row->title, $row->prefix, $row->suffix, $row->relevance );

         if( self::make_claim( $node ) == false ){
            unset( $node );
            $node = new ConclusionNode( $parent, $row->id, $row->title, $row->prefix, $row->suffix, $row->relevance );
            }
         }
      else if( is_subclass_of( (string)$row->type, "Node" ) ){

         $node = new $row->type( $parent, $row->id, $row->title, $row->prefix, $row->suffix, $row->relevance );

         self::make_claim( $node );
         }
      else{
         throw new LinkedException( "NodeFactory: Node Type (".$row->type.") is not a valid." );

         $false = false;
         return $false;
         }

      if( method_exists( $node, "setLeaf" ) ){
         if( self::hasSubArguments( $node->getId() ) ){
            $node->setLeaf( false );
            }
         else{
            $node->setLeaf( true );
            }
         }
      return $node; 
      }


   // }}}
   // {{{ setTablePrefix( $id )
   /**
    * Sets the table prefix to use 
    *   The Table prefix is retreved from the rootnodes table
    * @access protected
    *
    * @param string the id of the rootnode
    * @return boulian Throws LinkedException on error
    */
   protected function setTablePrefix( $id ){
      $res =& $this->db->query( "SELECT tablePrefix ".
                                "FROM ".$this->dbVars['globalPrefix'].$this->dbVars['tbName']['rootNodes']." ".
                                "WHERE id = '".$id."' " );
      if (PEAR::isError($res)) {
         throw new LinkedException( "NodeFactory: Query: ". $res->getMessage() );
         $this->tablePrefix = $this->dbVars['globalPrefix'];
         return false;
         }
      if( $res->numRows() != 1 ){
         throw new LinkedException( "NodeFactory: There are no nodeIds in the root nodse table thar mach the provided nodeId (id: ".$id.")." );
         return false;
         }
      $row = $res->fetchRow();

      $this->tablePrefix = $this->dbVars['globalPrefix'].$row->tableprefix;
      return true;
      }


   // }}}


   }

?>
