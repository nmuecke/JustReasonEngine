<?php
// vim: set expandtab tabstop=3 shiftwidth=3 softtabstop=3 foldmethod=marker: 
// {{{ copyright & disclaimer
/**
 *   JustReason - A decision support program and associated tools.         
 *   Copyright (C) 2005 by Nial Muecke and JustSys Pty Ltd                 
 *   nmuecke@justsys.com.au                                                
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
 * @package Logger
 * @version 1.0
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
// }}}
/**
 * Class Log
 *    A class that is designed to keeping track of events within a system
 *
 * @package Logger
 * @subpackage Log
 */
class Logger{
   // {{{ class variables
   // {{{ static private false|string  $file
   /**
    * Flag to set if the out put should be writen to a file
    * @access private
    * @var string|false the path to a file or false
    *
    * Default is false
    */
   static private $file = false;
   // }}}
   // {{{ static private Logger        $instance;
   /**
    * Holds an instance of the class 
    * @access private
    * @var Log
    */
   static private $instance;
   // }}}
   // {{{ static private int           $level
   /**
    * The runtime debuglevel, only event that are less the debuglevel will be displayed
    * Debug level -1 is off, 0 is display all, 2 would display level 2,3,4, ect but not level 1
    * @access private
    * @var int
    *
    * Default is 0;
    */
   static private $level = 0;
   // }}}
   // {{{ static private array         $lines
   /**
    * An 3d array of the messages and their level 
    * @access private
    * @var array
    */
   static private $lines;
   // }}}
   // {{{ static private int           $numLines
   /**
    * The number of line to be keep 
    * @access private
    * @var int
    *
    * Default is 500;
    */
   static private $numLines = 500;
   // }}}
   // {{{ static private boulian       $toScreen
   /**
    * Flag to set if the output should be sent to the display
    * @access private
    * @var boulian
    *
    * Default is true
    */
   static private $toScreen = true;
   // }}}
   // }}}
   // {{{ __constructor()
   /**
    * A blank constructor for the Log class
    *
    * @access private
    * @param void
    * @return void
    */
   private function __CONSTRUCT(){
      //$this->lines = array();
      self::$lines = array();
      self::$instance =& $this;
      self::log( "New Logger created on ".date(DATE_RFC822), 0, "Log" );
      }
   // }}}
   //{{{ displayLine( $lineNum )
   /**
    * uses a the line number to return a formatted string 
    *
    * @access private
    * @param int array index
    * @return string
    */
   private function getLine( $lineNum ){
      return sprintf( "%'04d (%'03d)[%s]: %s <br />\n",  $lineNum, 
                                                            self::$lines[$lineNum]['lev'], 
                                                            self::$lines[$lineNum]['flag'], 
                                                            self::$lines[$lineNum]['msg'] );
      }


   // }}}
   // {{{ displayLog( $flag = null,  )
   /**
    * Recurces through the log array and displays its content
    * This function is designed to allow you to control where the log messages are displayed
    *
    * @access public
    * @param null|string if the flag is supplyed then only log entries that mach that flag will be displayed
    * @return string
    */
   static public function displayLog( $flag = null ){
      while( $line = current( self::$lines ) ){
         if( $flag == null || $flag == $line['flag'] ){
            echo self::getLine( key( self::$lines ) );
            }
         next( self::$lines );
         }

      }


   // }}}
   // {{{ log( $msg, $debugLevel = 0, $flag = null )
  /**
    * Adds a log entry to the log. 
    * How the log entry is handled will depend on the settings, if the file variable is set then 
    * log entrys will be writen to the file ad if the toScreen variable is set then the log entry
    * will be output to the display.
    *
    * @access public
    * @param string 
    * @param int
    * @param null|string 
    * @return void
    */
   static public function log( $msg, $displayLevel = 0, $flag = null ){
      static $count;
      if( ( self::$level == 0 || $displayLevel <= self::$level ) && self::$level >= -1 ){
         if( !isset( self::$instance ) ){
            self::$instance =& new Logger();
            $count = 0;
            }
         self::$lines[$count++ ] = array( 'msg'=>$msg, 'lev'=>$displayLevel, 'flag'=>$flag );

         //if( sizeof( self::$instance->getLines() ) > self::$numLines ){
         //   array_shift( self::$instance->getLines() );
         //   }

         if( isset( self::$file ) && self::$file != false ){
            self::toFile( key( self::$lines ) );

            }

         if( self::$toScreen == true ){
            echo self::getLine( key( self::$lines ) );
            }  
         }
      } 


   // }}}
   // {{{ set( array $vars )
   /**
    * Set permits the configuration of how the logs should be keept
    *
    * @access public
    * @param array valid array values and their keys are:
    * <pre>
    * Type:    Index Lable:   Description:
    * boulian  toScreen       is the output should be displayed on the screen
    * int      level          what level of out put should be displayed
    * string   file           if the output shoud be writen to a file if so the the path to the file
    * int      numLines       the number of line to store int the local buffer
    * </pre>
    * @return void
    */
   static public function set( array $vars ){
      foreach( $vars as $key=>$var ){
         self::${$key} = $var;
         }
     // self::$codes    = $var['codes'];

      if( !isset( self::$instance ) ){
         $c = __CLASS__;
         self::$instance = new $c;
         }
      } 

   // }}}
   // {{{ toFile( $lineNum )
   /**
    * Appends a line to a file
    *
    * @access private
    * @param int the number for the line to append
    * @return void
    */
   private function toFile( $lineNum ){
      if( !file_exists( self::$file ) ){
         touch( self::$file );
         }
      if( sizeof( file( self::$file ))> self::$numLines ){
         if( copy(  self::$file, self::$file."_".date( DATE_ATOM ) ) == true ){
            unlink( self::$file );
            touch( self::$file );
            }
         }
      if( ($fd = fopen( self::$file, 'a' )) == false ){
         echo  "<b>Warning</b>: Unable to write log entery to file!";
         return 1;
         }
      fwrite( $fd, strip_tags( self::getLine( $lineNum ) ) );
      fclose( $fd );
      }         
   }
   
   
?>
