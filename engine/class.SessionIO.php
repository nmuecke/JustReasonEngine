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

// {{{ SQL for talbe creation
/*

-- 
-- Table structure for table `sessionData`
-- 

CREATE TABLE IF NOT EXISTS `sessionData` (
  `userID` varchar(30) NOT NULL,
  `sessionData` blob NOT NULL,
  UNIQUE KEY `userID` (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/
// }}}
/**
 * class SessionIO
 *  Defins how Input/Output should occur
 * @package JustReason_Engine
 * @subpackage IO
 */
abstract class SessionIO{
   // {{{ Class Veriables
   /**
    * Store for the retreved data
    * @var    binry data
    * @access protected
    */
   protected $sessionData;

   /**
    * User ID that corrospondes to tabe where user is stored
    * @var string
    * @access protected
    */
   protected $userID; 

   // }}}
   // {{{ __CONSTRUCT( array $resourceVars, $userID ) [Abstract]

   /*
    * Abstract constructor for saving data from a session 
    *
    * @access  public
    *
    * @param array  Of the resource variables where the session data will be saved to eg the file name and permission or database and table
    * @param string An ID to link the user with the stored data
    *
    * @return void Throws LinkedException on error
    */
   abstract public function __CONSTRUCT( array $resourceVars, $userID );

   // }}}
   // {{{ load( ) [Abstract]

   /*
    * Abstract function to load session data from a resource
    *
    * @access  public
    * @param void
    * @return mixed Session data on succsess else throws a LinkedException 
    */

   abstract public function load( );

   // }}}
   // {{{ save( $sessionVar ) [Abstract]
   /*
    * Abstract function to save session data to a resource
    *
    * @access  public
    *
    * @param mixed The session data to be stored
    *
    * @return boolian True if succsessful else false
    */
   abstract public function save( $sessionVar );

   // }}}

   }
?>
