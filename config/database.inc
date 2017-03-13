<?php
/**
 * Database config array
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
 * The user who is going to connect to the database
 * @name $db['username']
 * @global string
 */		
$db['username'] = "sample";
/**
 * The password of the user how is going to connect to the database
 * @name $db['password']
 * @global string
 */	
$db['password'] = "sample";
/**
 * The database to connect too
 * @name $db['database']
 * @global string
 */		
$db['database'] = "JustReason";
/**
 * The host server specs
 * @name $db['hostspec']
 * @global string
 */		
$db['hostspec'] = "localhost";
/**
 * The type of database to be accessed
 * @name $db['phptype']
 * @global string
 */		
$db['phptype']  = "mysql";

# data base configeration for DSS engine
/**
 * The database global prefix
 * @name $db['globalPrefix']
 * @global string
 */
$db['globalPrefix']  = "";

/**
 * names for the DSS engine tabes
 * @name $db['tbName']
 * @global array
 */
 $db['tbname'] = array();

 # Athentication tables
 $db['tbName']['users']                = "users";
 $db['tbName']['auth']                 = "auth"; // defined by PEAR and should not be changed or overridden

 # JustReanons Engine Tables 
 $db['tbName']['rootNodes']            = "rootnodes";
 $db['tbName']['claimValues']          = "claimvalues";
 $db['tbName']['genericArguments']     = "genericarguments";
 # name for table where session data is stored
 $db['tbName']['sessionData']          = "sessionData";

   
?>
