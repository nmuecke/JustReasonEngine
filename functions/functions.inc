<?php
/* vim: set expandtab tabstop=3 shiftwidth=3 softtabstop=3 foldmethod=marker: */
// {{{ copyright and Disclaimer
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
 * @package Functions 
 * @version 1.0
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2007, Nial Muecke
 *
 */
// }}}
//{{{ __autoload( $class_name )
/**
 * Defines the php magic function for auto loading classes
 *    The function will try to load the using the class name
 *    as the file name with the prefix class and then without
 *    a prefix, ether way it will use the suffix .php.
 *    
 *    Examples:
 *    - class.class_name.php
 *    - class_name.php
 *
 *    If the class is not found at all a message will be displayed
 *    along with the warnings.
 *
 * @param string class name
 * @returns void
 * Note: curently not in use
 */
function __autoload( $class_name ){
   if( $class_name != "" ){
      if( include_once( "class.".$class_name.".php" ) == false ){
         if( include_once( $class_name.".php" ) == false ){
            echo 
                 "<h3>Error loading class definition for ".$class_name."</h3>".
                 "<p> \n".
                 "It seems that you are trying to use a custom class, \n".
                 "but you have not included the class file and it could \n".
                 "not be found automaticly.".
                 "</p>\n";
            }
         }
      }
   }


// }}}
//{{{ array_add( $array1, $array2, $force )
/**
 * Adds the content of the second array to the firest array,
 * If the element at a given key of array one if not set or 
 * is an empty string then the value of array2 is used instead.
 * However if the element is not empty then the value from array1
 * will be used in preference of array 2.
 *
 * If the $force if set to true then when there is a mutual key
 * in array2 and array2 then the element in array2 will be assigned
 * a new key instead of beeing dumped.
 *
 * @param array
 * @param array
 * @param boulian
 * @returns array the new array
 */
function array_add( array & $array1, array $array2, $force = false ){

   foreach( $array2 as $key=>$val ){
      if( !isset( $array1[$key] ) || $array1[$key] == "" ){
         $array1[$key] = $val;
         }
      else if( $force == true ){
         array_push( $array1, $array2[$key] );
         }
      }
   }
// }}}
?>
