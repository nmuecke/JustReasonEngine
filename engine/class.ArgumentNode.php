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
require_once( "class.Node.php" );
require_once( "class.LinkedException.php" );
// }}}
/**
 * class ArgumentNode
 *  Argument nodes are intented to be uses for the creation of 
 *  argument trees that are used to infer the claim of the parent
 *  node
 * @package JustReason_Engine
 * @subpackage Nodes
 */
class ArgumentNode extends Node {
   // {{{ Class Variables
   /**
    * Array of sub arguments nodes
    * @var    array
    * @access private
    */
   private $subArguments = array();

   /**
    * Flag to indicat if  the node is a leaf node or not
    * @var    boulian
    * @access private
    */
   private $isLeaf;

   /**
    * Flag to indicat if the node as sub arguments
    * @var    boulian
    * @access private
    */
   private $isNotsure;

   // }}}
   // {{{ __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $relevance = null, $claims = null )
   /**
    * Constructor for creating an argument node
    * Note: nose are created as non-leaf
    * @access  public
    *
    * @param Node        the nodes parent 
    * @param string      id of the node
    * @param string      title of the node
    * @param string      prefix for the node argument
    * @param string      suffix for the node argument
    * @param string      relevance of the node's argument
    * @param array an array of claim ojbects or juat a claim object.
    * @return void
    */
   public function __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $relevance = null, array $claims = null ){
      parent::setId( $id );
      parent::setTitle( $title );
      parent::setPrefix( $prefix );
      parent::setSuffix( $suffix );
      parent::setClaimId( NOT_SET );
      parent::setParentNode( $parent );
      parent::setRelevance( $relevance );
      if( $claims != null ){
         self::setClaims( $claims );
         }
      self::setLeaf( false );
      }
   

   // }}}
   // {{{ addArgument( & $arg )
   /**
    * Adds an sub argument to the node
    *
    * @access protected
    *
    * @param ArgumentNode 
    * @return void Throws LinkedException on error
    */

   final protected function addArgument( & $arg ){
      if( is_object( $arg ) && ( get_class( $arg ) == 'ArgumentNode' || is_subclass_of( $arg, 'ArgumentNode' )) ){
         array_push( $this->subArguments, $arg );
         }
      else{
         throw new LinkedException( "Unable to add sub-AdgumentNode to Claim (id: ".$this->getId().")!" );
         }
      }


   // }}}
   // {{{ addClaim( & $claim )
   /**
    * Adds a claim to the claims array 
    *
    * @access protected
    *
    * @param Claim
    * @return void Throws LinkedException on error
    */
   final private function addClaim( & $claim ){
      if( is_object( $claim ) && ( get_class( $claim ) == 'Claim' || is_subclass_of( $claim, 'Claim' ) )){
         array_push( $this->claims, $claim ); 
         }
      else{
         throw new LinkedException( "Unable to add claim to ArgumentNode (id: ".$this->getId().")!" );
         }
      } 


   // }}}
   // {{{ getArguments()
   /**
    * Returns the subargumetns array
    *
    * @access  public
    *
    * @param void
    * @return array
    */
   public function & getArguments(){
      return $this->subArguments;
      }


   // }}}
   // {{{ getArgument( $argId )
   /**
    * Returns the argument which mached the given id else false
    *
    * @access  public
    *
    * @param string argument id
    * @return ArgumentNode|false
    */
   public function & getArgument( $argId ){
      for( $xx = 0; ( $arg =& self::getArgumentAt($xx) ) != false; $xx++ ){
         if( $arg->getId() == $argId )
             return $arg;
        }
      $false = false;
      return $false;
      }


   // }}}
   // {{{ getArgumentAt( $pos )
   /**
    * Returns the argument at a given array address
    *
    * @access  public
    *
    * @param int
    * @return ArgumentNode 
    */
   public function & getArgumentAt( $pos ){
      if( $pos >= 0 && $pos < self::numArguments() ){
         return $this->subArguments[$pos];
        }
      }


   // }}}
   // {{{ getClaimAt( $index )
   /**
    * Returns a claim at a given array index
    *
    * @access  public
    *
    * @param int
    * @return Claim or false if there are no claims 
    */
   public function getClaimAt( $index ){
      if( isset( $this->claims[$index] ) ){
         return $this->claims[$index];
         }
      else{
         return false;
         }
      }

   // }}}
   // {{{ numArguments()
   /**
    * Returns the number of arguments in list
    *
    * @access  public
    *
    * @param void
    * @return int
    */
   public function numArguments(){
      if( !isset( $this->subArguments )){
         return NOT_SET;
         }
      return sizeof( $this->subArguments );
      }


   // }}}
   // {{{ numClaims()
   /**
    * Returns the number of claims assigned to the node
    *
    * @access  public
    *
    * @param void
    * @return int
    */
   public function numClaims(){
      if( !isset( $this->claims ) ){
         return 0;
         }
      return sizeof( $this->claims );
      } 

   // }}}
   // {{{ isLeaf()
   /**
    * Returns true if the node is a leaf node
    *
    * @access  public
    *
    * @param void
    * @return boulian
    */
   public function isLeaf(){
      return $this->isLeaf;
      } 

   // }}}
   // {{{ setArguments( & $args )
   /**
    * Adds an argument (sub argument) to the node
    *
    * @access  public
    *
    * @param ArgumentNode:array of/or argumentNode 
    * @return void Throws LinkedException on error
    */
   public function setArguments( & $args ){
      if( is_array( $args )){
          foreach( $args as $arg ){
            self::addArgument( $arg );
            }
         }
      else{
         self::addArgument( $args );
         }
      }


   // }}}
   // {{{ setClaims( & $claims )
   /**
    * Adds a claim or array of claim to the node
    *
    * @access  public
    *
    * @param Claim|array of claims
    * @return void
    */
   public function setClaims( & $claims ){

      if( is_array( $claims ) ){
         foreach( $claims as $claim ){
            self::addClaim( $claim );
            } 
         }
      self::addClaim( $claims );
      }

   // }}}
   // {{{ setLeaf()
   /**
    * set the leaf statuse of the node
    *
    * @access  public
    *
    * @param boulian
    * @return void
    */
   public function setLeaf( $status = true ){
      $this->isLeaf = $status;
      } 

   // }}}
   }


?>
