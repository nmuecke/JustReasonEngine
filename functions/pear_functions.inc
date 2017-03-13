<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
/**
 * Custon PEAR support function
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
 * @version 1.0
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2007, Nial Muecke
 */

// {{{ loginUser()
/**
 * Loggs in a user
 * Permit a user to login or for a new user to create an account
 *
 * @param void
 * @return void
 */

function loginUser(){
   global $options;
   global $MSG;
   global $login;
   $form = new HTML_QuickForm( "", "POST", "" );
   $form->setDefaults( array( ""
                             ) 
                      );

   $username = "unset";
   if( isset( $_POST['username'] ) ){
      $username = $_POST['username'];
      }

   $form->addElement( "header",    "",            "&nbsp Login:"           );
   $form->addElement( "text",      "username",    "User Name:"             );
   $form->addElement( "password",  "password",    "Password:"              );
   $form->addElement( "submit",    "login",       "Log In"                 );
   $form->addElement( "header",    "",            "&nbsp Create new user:" );
   $form->addElement( "password",  "password2",   "Password:"              );
   $form->addElement( "submit",    "register",    "Register"               );

   $form->addRule('username', $MSG[0105], 'required' );
   $form->addRule('password', $MSG[0106], 'required' );
   if( isset( $_POST['register'] ) ){
      Logger::log( "Attempting to make new user: usernam = ".$username, 1, "Secure" );
      $form->registerRule( "userExists",    "callback", "userExist"   );
      $form->addRule('username', $MSG[0113], 'userExists',    $options['dsn'] );
      $form->addRule('password2', $MSG[0111], 'required' );
      $form->addRule(array('password','password2'), $MSG[0114], 'required' );

      }
   else if ( isset( $_POST['login'] ) ){
      Logger::log( "Attempting to log in user: usernam = ".$username, 1, "Secure" );
      $form->registerRule( "checkPassword", "callback", "badPassword" );
      $form->addRule('username', $MSG[0104], 'checkPassword', $login->getStatus() );
      }

   if( $form->validate() ){
      if( $_POST['register'] ){
         $login->addUser( $_POST['username'], $_POST['password'] );
         Logger::log( "New user created: usernam = ".$login->getUsername(), 1, "Secure" );
         }
      else{
         }
      }
   else{
      $form->display();
      }
   }
// }}}
// {{{ userExist()
/**
 * Test to see if the username exists in the system
 *
 * @param string    $username   not used for any thing
 * @return bollean  true if username is found is correct 
 */

function userExist( $username, array $dsn ){
   global $MSG;

   $db =& MDB2::connect( $dsn );

   if( PEAR::isERROR( $db )){
      Logger::log( "userExists: Faild to connect to database! ".$MSG[0201].": ".$db->getMessage() , 3 );
      die( $MSG[0201].": ".$db->getMessage() );
      }
   $query =  "SELECT * FROM `".$dsn['database']."`.auth WHERE `username` = '".$username."'";

   $res =& $db->query( $query );
   if( PEAR::isERROR( $res )){
      Logger::log( "userExists: ".$MSG[0202].": ".$res->getMessage() , 3 );
      die( $MSG[0202].": ".$res->getMessage().">> ".$query );
      }
  echo $res->numRows()." ---"; 

   if( $res->numRows() > 0 && 0 != $res->numRows()){
      return true;
      }
   return false;
   }
//}}}
// {{{ badPassword( $username, $status )
/**
 * Validate at the provided password
 *
 * @param string    $username   not used for any thing
 * @param int       $status     the statuse code for the login attampt
 * @return bollean  true if a password is correct 
 */
function badPassword( $username, $status ){

    if( $status == '-3' ){
       return false;
       }
   return true;
   }
// }}}
// {{{ specialChars( $str )
/**
 * Passes a string looking for special charactors 
 *
 * @param  string    $str       the string that is to be tested
 * @param  array     $extrar    the statuse code for the login attampt
 * @return bolian    true if a special char was found else false 
 */

function specialChars( $str, array $extras = null ){
  $specialChar = array( "'", '"', ";", ".", ",", "[", "]", "{", "}", "|",  "<", ">", "+", ")", "(", "*", "&", "^", "%", "$", "#", "!" );
  if( $extras != null ){
     $specialChar = array_merge($specialChar, $extras ); 
     }
   foreach( $specialChar as $char ){
      if( strrchr( $str, $char ) != false ){
         return false;
         }
      }
   return true;
   }
   // }}}
?>
