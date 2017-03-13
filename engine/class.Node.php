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

// {{{ SQL need to store nodes
// {{{ SQL for rootnodes table
/*

-- 
-- Table structure for table `rootnodes`
-- 

CREATE TABLE IF NOT EXISTS `rootnodes` (
  `id` varchar(30) NOT NULL default '',
  `order` int(50) NOT NULL,
  `tablePrefix` varchar(50) NOT NULL,
  `desctiption` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/
// }}}
// {{{ SQL for generic arguments tables
/*

-- 
-- Table structure for table `genericarguments`
-- 

CREATE TABLE IF NOT EXISTS `genericarguments` (
  `id` varchar(36) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `prefix` mediumtext,
  `suffix` mediumtext,
  `parent` varchar(36) NOT NULL default '',
  `relevance` mediumtext,
  `not_sure` varchar(36) default NULL,
  `type` varchar(30) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/
// }}}
// {{{ SQL for claimsvalue tables
/*

-- 
-- Table structure for table `claimvalues`
-- 

CREATE TABLE IF NOT EXISTS `claimvalues` (
  `id` tinyint(4) NOT NULL default '0',
  `argument_id` varchar(36) NOT NULL default '0',
  `claim` mediumtext,
  `threshold` tinyint(4) default NULL,
  `weight` varchar(36) default NULL,
  `order` tinyint(4) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/
// }}}
// }}}
// {{{ class constants
/**
 * This defines what an unset claim id should be
 */
define( "NOT_SET", "<°)))><" );
// }}}
/**
 * class Node
 *  Defines the basic properties of a node
 * @package JustReason_Engine
 * @subpackage Nodes
 */
abstract class Node {
   // {{{ Class Veriables
   /**
    * Id of the node
    * @var string   
    * @access protected
    */
   protected $id;

   /**
    * Title of the node
    * @var string   
    * @access protected
    */
   protected $title;

   /**
    * Prefix of the node
    * @var string   
    * @access protected
    */
   protected $prefix;

   /**
    * Suffix of the string
    * @var string   
    * @access protected
    */
   protected $suffix;

   /**
    * The defult claim's Id
    * @var int   
    * @access protected
    */
   protected $claimDefault;

   /**
   /**
    * The active claim's Id
    * @var int   
    * @access protected
    */
   protected $claimId;

   /**
    * An array of claims
    * @var array   
    * @access protected
    */
   protected $claims = array();

   /**
    * Handle to the node's parent node
    * @var Node   
    * @access protected
    */
   protected $parent;

   /**
    * An aditionlal action to perform when a node is loaded
    * @var mixed   
    * @access protected
    * @todo yet to be implermented/may be dropped
    */
   protected $action;

   /**
    * Why a particulare node is relivant to an argument
    * @var string   
    * @access protected
    */
   protected $relevance;


   // }}}
   // {{{ __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $relevance = null, $claims = null ) [Abstract]
   /**
    * Definition for how nodes should be constructed
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
   abstract function __CONSTRUCT( & $parent, $id = null, $title = null, $prefix = null, $suffix = null, $relevance = null, $claims = null );

   // }}}
   // {{{ getClaimId( )
   /**
    * Returns the set calim's Id
    *
    * @access public
    *
    * @param void
    * @return string
    */
   public function getClaimId( ){
      return $this->claimId;
      }


   // }}}
   // {{{ getClaimDefault( )
   /**
    * Returns the defult calim's Id
    *
    * @access public
    *
    * @param void
    * @return int
    */
   public function getClaimDefault( ){
      
      return $this->claimDefault;
      }


   // }}}
   // {{{ getClaims()
   /**
    * Returns an array of claims
    *
    * @access public
    *
    * @param void
    * @return array
    */
   public function & getClaims(){
      return $this->claims;
      }


   // }}}
   // {{{ getId( )
   /**
    * Returns the nodes ID
    *
    * @access public
    *
    * @param void
    * @return string
    */
   public function getId( ){
      return $this->id;
      }


   // }}}
   // {{{ getParent()
   /**
    * Returns the nodes parent node 
    *
    * @access public
    *
    * @param void
    * @return Node
    */
   public function & getParent(){
      return $this->parent;
      }


   // }}}
   // {{{ getPrefix( )
   /**
    * Returns the nodes prefix 
    *
    * @access public
    *
    * @param void
    * @return string
    */
   public function getPrefix( ){
      return $this->prefix;
      }


   // }}}
   // {{{ getRelevance( )
   /**
    * Returns the nodes relivance value
    *
    * @access public
    *
    * @param void
    * @return string
    */
   public function getRelevance( ){
      return $this->relevance;
      }


   // }}}
   // {{{ getSuffix( )
   /**
    * Returns the nodes suffix
    *
    * @access public
    *
    * @param void
    * @return string
    */
   public function getSuffix( ){
      return $this->suffix;
      }


   // }}}
   // {{{ getTitle( )
   /**
    * Returns the nodes title
    *
    * @access public
    *
    * @param void
    * @return string
    */
   public function getTitle( ){
      return $this->title;
      }


   // }}}
   // {{{ setClaimId( $id )
   /**
    * Sets the active claim Id
    *
    * @access public
    *
    * @param int
    * @return void
    */
   public function setClaimId( $id ){
      $this->claimId = $id;
      }


   // }}}
   // {{{ setClaims( & $claims ) [Abstract]
   /**
    * Adds claims to the node
    *
    * @access public
    *
    * @param Claim|array
    * @return void
    */
   abstract public function setClaims( & $claims );


   // }}}
   // {{{ setClaimDefault( $id )
   /**
    * Sets the default claim to display
    *
    * @access public
    *
    * @param int
    * @return void
    */
   public function setClaimDefault( $id ){
      $this->claimDefault = $id;
      }


   // }}}
   // {{{ setId( $id )
   /**
    * Sets the node's Id
    *
    * @access public
    *
    * @param string
    * @return void
    */
   public function setId( $id ){
      $this->id = $id;
      }


   // }}}
   // {{{ setParentNode( & $node )
   /**
    * Sets the node's parent node
    *
    * @access public
    *
    * @param Node
    * @return void
    */
   public function setParentNode( & $node ){
      $this->parent = $node;
      }


   // }}}
   // {{{ setPrefix( $prefix )
   /**
    * Sets the node's prefix value
    *
    * @access public
    *
    * @param string
    * @return void
    */
   public function setPrefix( $prefix ){
      $this->prefix = $prefix;
      }


   // }}}
   // {{{ setRelevance( $relevance )
   /**
    * Sets the node's relivance value
    *
    * @access public
    *
    * @param string
    * @return void
    */
   public function setRelevance( $relevance ){
      $this->relevance = $relevance;
      }


   // }}}
   // {{{ setSuffix( $suffix )
   /**
    * Sets the node's suffix value
    *
    * @access public
    *
    * @param string
    * @return void
    */
   public function setSuffix( $suffix ){
      $this->suffix = $suffix;
      }


   // }}}
   // {{{ setTitle( $title )
   /**
    * Sets the node's title value
    *
    * @access public
    *
    * @param string
    * @return void
    */
   public function setTitle( $title ){
      $this->title = $title;
      }


   // }}}
   }
?>
