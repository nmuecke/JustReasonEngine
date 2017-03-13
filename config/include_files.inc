<?php
/**
 * Header file for inclusion of generic files
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
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2007, Nial Muecke
 */

/**
 * sets the search path.
 * * note: unless you have problesm with the local PEAR files it would
 *         be best to leave it as it even if they are out of date.
 */
 $base = realpath( dirname(__FILE__)."/.." );
 set_include_path( $base.'/PEAR'       . PATH_SEPARATOR . // path to pear files
                   $base.'/config'     . PATH_SEPARATOR . // path to config files
                   $base.'/functions'  . PATH_SEPARATOR . // path to various suport function
                   $base.'/engine'     . PATH_SEPARATOR . // path to JustReason engine class files
                   $base.'/extentions' . PATH_SEPARATOR . // path to support classes
                   get_include_path()
                  );
/**
 * PEAR class files ( PEAR dir )
 */
 require_once( "MDB2.php" );
 require_once( "HTML/QuickForm.php" );
 require_once( "Auth.php" );
/**
 * Log class for logging and debugging
 */
 require_once( "class.Logger.php" );
/**
 * setting files ( config dir )
 */
 require_once( "messages.inc" );
 require_once( "database.inc" );
 require_once( "pages.inc" );
/**
 * general suplemental function 
 */
 require_once( "functions.inc" );
/**
 * suplemental function for pear ( config dir )
 */
 require_once( "pear_functions.inc" );
/**
 * main class file for the JustReason Engine ( engine dir )errors.html#class.Claim.php
 */
 require_once( 'class.JustReasonEngine.php' );


