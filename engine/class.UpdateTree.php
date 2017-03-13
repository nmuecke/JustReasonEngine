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
require_once( "class.LinkedException.php" );
require_once( "class.Tree.php"            );
// }}}
/**
 * class UpdateTree
 *  Uses input to update a tree
 * @package JustReason_Engine
 * @subpackage Tree
 */
class UpdateTree{
   // {{{ Class Veriables
   /**
    * Handle to a Tree object
    * @var Tree   
    * @access private
    */
   private $tree;

   /**
    * A prefix to veriables passed in by the post to identify veriable that require updating
    * @var string   
    * @access private
    */
   private $postDataPrefix;

   // }}}
   // {{{ __CONSTRUCT( & $tree, $postDataPrefix = "SELECT_" )
   /**
    * Constructor for UpdateTree class
    *
    * @access public
    *
    * @param Tree 
    * @param string
    * @return void
    */
   public function __CONSTRUCT( & $tree, $postDataPrefix = "SELECT_" ){
      $this->tree =& $tree;
      $this->postDataPrefix = $postDataPrefix;
      }


   // }}}
   // {{{ updateFromPostData()
   /**
    * Updates the tree by examing the $_POST array
    *
    * @access public
    *
    * @param void
    * @return void Throws LinkedException on error
    */
   public function updateFromPostData(){
      foreach( $_POST as $key => $post ){
         if( substr( $key, 0, strlen( $this->postDataPrefix ) ) == $this->postDataPrefix ){
            if( self::updateNode( substr( $key, strlen( $this->postDataPrefix ) ), $post ) == false ){
               throw new LinkedException( "UpdateTree: PostData indicated that there is a node to update, \n".
                                           "            however the node was not found in the tree ( PostVar: ".
                                            substr( $key, strlen( $this->postDataPrefix ) )." id: ".$post." ).\n" );
               }
            }
         }
      }



   // }}}
   // {{{ updateNode( $nodeId, $value )
   /**
    * Updates a node to a given value
    *
    * @access public
    *
    * @param string the identifyes the node to by updated
    * @param mixed the value to update the node to
    * @return boulian returns false on in update id not take place
    */
   public function updateNode( $nodeId, $value ){
      if( ( $node =& $this->tree->findNode( $nodeId, $this->tree->getCurrentNode() )) != false ){
            $node->setClaimDefault( $value ); 
            return true;
            }
      return false;
      }
   // }}}
   // {{{ updateNodes( array $nodes )
   /**
    * Updates a number of node 
    *
    * @access public
    *
    * @param array of nodes
    * @return void Throws LinkedException on error
    */
   public function updateNodes( array $nodes ){
      foreach( $nodes as $nodeId => $value ){
         if( self::updateNode( $nodeId, $value ) == false ){
            throw new LinkedException( "UpdateTree: Updating of nodes indicated that there is a node to update, \n".
                                        "            however the node was not found in the tree ( id: ".$nodeId." ).\n" );
            }
         }
      }


   // }}}


   }
?>
