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
require_once( "class.SessionIO.php" );
require_once( "MDB2.php"            );
// }}}
/**
 * class SessionIO_DB
 *  Handles database Input/Output for saving/loading data
 * @package JustReason_Engine
 * @subpackage IO
 */
class SessionIO_DB extends SessionIO{
   
   // {{{ Class Variables
    /**
     * Name of the database to use
     *
     * @var     string
     * @access  private
     */
   private $db;

    /**
     * Name of the table to save and load data from
     *
     * @var     string
     * @access  private
     */
   private $tableName;

   // }}}
   // {{{ __CONSTRUCT( array $resourceVars, $userID )

   /**
    * Constructor for saving data from a session to a database 
    *
    * @access  public
    *
    * @param array  Containg the database with key for: username, password, database, phptype, hostspec, [globalPrefix, [tbName]]. 
    * @param string An ID to link the user with the stored data
    * @return void Throws LinkedException on error
    */
   public function __CONSTRUCT( array $resourceVars, $userID ){
      if( !isset( $resourceVars['username'] ) 
        && !isset( $resourceVars['password']) 
        && !isset( $resourceVars['database']) 
        && !isset( $resourceVars['phptype']) 
        && !isset( $resourceVars['hostspec']) ){
          throw new LinkedException( "SessionIO_DB: Some or all of database variable were not provided!" );
          }
      if( !isset( $resourceVar['tbName'] )){
         $this->tableName = $resourceVars['globalPrefix'].'sessionData';
         }
      else if( isset( $resourceVar['tbName']['SessionData'] ) ){
         $this->table =  $resourceVars['globalPrefix'].$resourceVar['tbName']['sessionData'];
         }
      else if( !is_array( $resourceVar['tbName'] )){
         $this->table =  $resourceVars['globalPrefix'].$resourceVar['tbName'];
         }
      else{
         throw new LinkedException( "SessionIO_DB: A table name was supply but it is not valid!" );
         }


      $this->db =& MDB2::connect( $resourceVars );

      if( PEAR::isError($this->db) ){
         throw new LinkedException( "SessionIO_DB: Faild to establis DB conection: ".$this->db->getMessage() );
         }

      $this->db->setFetchMode(MDB2_FETCHMODE_OBJECT);

      if( $userID == "" ){
         throw new LinkedException( "SessionIO_DB: User ID not valid!" );
         }
      $this->userID = $userID;
      }


   // }}}
   // {{{ __DESTRUCT()
   /**
    * class Destructor
    *
    * @access  public
    *
    */
   public function __DESTRUCT(){
      $this->db->disconect();      
      }

   // }}}
   // {{{ load( )

   /**
    * Loads session data from a table and returns it;
    *
    * @access  public
    *
    * @return mixed Session data if succsess else throws a LinkedException 
    */

   public function load( ){
      $res = $this->db->query( "SELECT * FROM ".$this->tableName." WHERE userID = '".$this->userID."' " );

       if (PEAR::isError($res)) {
          throw new LinkedException( "SessionIO_DB: Unable to recover data: ".$res->getMessage() );
          }

      if( $res->numRows() != 1 ){
         return false;
         }

      $row = $res->fetchRow();
      if( PEAR::isError( $row ) ){
         throw new LinkedException( "SessionIO_DB: Unable to recover data: ".$row->getMessage() );
         }      

      return unserialize( $row->sessiondata );
      }

   // }}}
   // {{{ save( $sessionVar )

   /**
    * Saves session data to database table
    *
    * @access  public
    *
    * @param mixed The session data to be stored
    *
    * @return boolian True if succsessful else throws an LinkedException;
    */
   public function save( $sessionVar ){
      $data = serialize( $sessionVar );

      $res = $this->db->query( "SELECT * FROM ".$this->tableName." WHERE userID = '".$this->userID."' " );

      if (PEAR::isError($res)) {
         throw new LinkedException( "SessionIO_DB: Error establising Session Data table exists: ".$res->getMessage() );
         }
      else if( $res->numRows() == 0 ){
         $res = $this->db->query( "INSERT INTO ".$this->tableName." ( sessionData, userID ) VALUES ( ".$this->db->quote($data).", '".$this->userID."' )" );

         if (PEAR::isError($res)) {
            throw new LinkedException( "SessionIO_DB: Error inserting data in to new table entery: ".$res->getMessage() );
            }
         }
      else if( $res->numRows() == 1 ){
         $res = $this->db->query( "UPDATE ".$this->tableName." SET sessionData = ".$this->db->quote($data)." WHERE userID = '".$this->userID."' LIMIT 1 " );
         if (PEAR::isError($res)) {
            throw new LinkedException( "SessionIO_DB: Error updating data: ".$res->getMessage() );
            }
         }
      else{
         throw new LinkedException( "SessionIO_DB: Supplyed userID exists more than once in table! Table may have been corupted or compromised! " );
         }

      return true;
      }


   // }}}

   }
?>
