<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
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
 * @package JustReason_Engine 
 * @version 4.0
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

// {{{ Include Files
/** 
 * Include Files
 */
require_once( "class.LinkedException.php"             );
require_once( "class.RenderNodeText.php"              );
require_once( "class.RenderNodeDropBox.php"           );
require_once( "class.RenderNodeRadioButtons.php"      );
require_once( "class.RenderNodeShowingWeights.php"    );
require_once( "HTML/Table.php"                        );

// }}}
/**
 * class Display
 *  Creats a display from the content that has been added to it
 *  and from the Nodes in the tree
 * @package JustReason_Engine
 * @subpackage Display
 */
class Display{

   // {{{ class variables
   /**
    * Reference to the tree that is to be displayed
    * @var Tree
    * @access private
    */
   private $tree;

   /**
    * An array of HTML form elemnt properties
    * @var array   
    * @access private
    */
   private $form;

   /**
    * An array of button element that are not to be displayed
    * @var array   
    * @access private
    */
   private $suppressed = array();

   /**
    * How the nodes should be rendered
    * @var string   
    * @access private
    */
   private $renderType;  

   /**
    * A PEAR HTML_Table that the content to be displayed is added too
    * @var HTML_Table   
    * @access protected
    */
   protected $table;
 
 
   // }}}
   // {{{ __CONSTRUCT( & $tree, array $form = null )
   /**
    * Constructor for the Display class
    *
    * @access public
    *
    * @param Tree reference to the tree 
    * @param string how the nodes should be displayed 
    * @param array of form elemnt properties 
    * @param int the minimum number of colums the display should use;
    * @return void
    */
   public function __CONSTRUCT( & $tree, $renderType = 'RenderNodeDropBox', array $form = null, $cols = 3  ){
      $this->tree = $tree;
      $this->form = $form;
      $this->renderType = $renderType;

      $formVars = array( "method"=>"post", "action"=>"", "id"=>"JREngineForm", "name"=>"JREngineForm" );

      foreach( $formVars as $key=>$var ){
          if( !isset( $this->form[$key] ) ){
             $this->form[$key] = $var;
             }
          }

      $this->table =& new HTML_Table();
      $this->table->setAutoGrow(true);
      self::setMinCols( $cols );

      $this->suppressed = array();
      }



   // }}}
   // {{{ addArgumentNodes()
   /**
    * Adds the current Node's Arguments to the display
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function addArgumentNodes(){
      $node =& $this->tree->getCurrentNode();

      for( $xx = 0; $xx < $node->numArguments(); $xx++ ){
         self::addNode( $node->getArgumentAt( $xx ) );
         }
      self::addButton( "Infer", "Infer", $node->getId(), 1 );
      }



   // }}}
   // {{{ addButton( $name, $lable, $value = "", $position = 'right', $row = null )
   /**
    * Adds a button to the display 
    *
    * @access public
    *
    * @param string HTML button propertiy name 
    * @param string HTML button display lable 
    * @param string HTML button propertiy value
    * @param string|int which colum the button should be posintion in
    *   Preset defined value are: 'left', 'right' and 'center'
    * @param int the row add the button to. 
    *   If value is a negative it will be added to a past row where 
    *   -1 is the previos row added
    * @return false is the button is to be suppres from the display
    */
   public function addButton( $name, $lable, $value = "", $position = 'right', $row = null ){
      $button = "<button ".
                " type   = 'submit' ".
                " name   = '".$name."'".  
                " value  = '".$value."' >".
                $lable.
                "</button>";
      
      foreach( $this->suppressed as $id ){
         if( $name == $id ){
            return false;
            }
         }

      if( $row == null ){
         $row = $this->table->getRowCount();
         }
      else if( !is_int( $row ) ){
         throw new LinkedException( "Display: Row (".$row.") is not valid, unable to add button to display!" );
         }
      else if( $row < 0 ){
         $row = $this->table->getRowCount() + $row;
         }

      if( $position == 'left' ){
         $cell = 0;
         }
      else if( $position == 'right' ){
         $cell = $this->table->getColCount() -1;
         }
      else if( $position == 'center' ){
         $cell = (($this->table->getColCount())/2);
         }
      else{
         $cell = $position;
         }


      $this->table->setCellContents( $row, $cell, $button );
      }



   // }}}
   // {{{ addDecisionNode()
   /**
    * Adds the current DecisionNode to be displayed 
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function addDecisionNode(){
      self::addNode( $this->tree->getCurrentNode() );
      }



   // }}}
   // {{{ addNode( & $node )
   /**
    * Adds the given node to be displayed 
    *
    * @access public
    *
    * @param Node
    * @return void
    */
   public function addNode( & $node ){
      $render = new $this->renderType( $node );
      self::addRow( array_values( $render->toArray() ) );
      }



   // }}}
   // {{{ addRow( array $contents = NULL, $attributes = NULL, $type='TD' )
   /**
    * Adds a row to the display table 
    *
    * @access public
    *
    * @param array where each array element contains the td data for a row
    * @param string|array the forms the HTMl tr element properties
    * @param string the td type can be 'TD' or 'TH'
    * @return void
    */
   public function addRow( array $contents = NULL, $attributes = NULL, $type='TD' ){
      $this->table->addRow( $contents, $attributes, $type );
      }


   // }}}
   // {{{ display()
   /**
    * Displays the rended output
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function display(){
      echo self::getHtml();
      }


   // }}}
   // {{{ getFooter()
   /**
    * Returns the Displays footer content
    *
    * @access protected
    *
    * @param void
    * @return void
    */
   protected function getFooter(){
      return "	   </form>\n";
      }


 
   // }}} 
   // {{{ getHeader()
   /**
    * Returns the displays header content 
    *
    * @access protected
    *
    * @param void
    * @return void
    */
   protected function getHeader(){
      return "<form method = '".$this->form['method']."' 
                    action = '".$this->form['action']."' 
                    id     = '".$this->form['id']."' 
                    name   = '".$this->form['name']."'>\n";
      }


   
   // }}}
   // {{{ getHtml()
   /**
    * Returns the display as a HTML string 
    *
    * @access public
    *
    * @param void
    * @return string
    */
   public function getHtml(){
      return self::getHeader().
             $this->table->toHtml().
             self::getFooter();
      }



   // }}}
   // {{{ setMinCols( $cols )
   /**
    * Sets the Minimum number of colums that should be used in the display 
    *
    * @access public
    *
    * @param int minumum number of columas to ues
    * @return void
    */
   public function setMinCols( $cols ){
      $this->table->setColCount( $cols );
      }


   // }}}
   // {{{ suppress( )
   /**
    * Adds an array of HTML element names that should not be displayed
    *
    * @access public
    *
    * @param string|array
    * @return void
    */
   public function suppress( $ids ){
      if( is_array( $ids ) ){
         array_merge( $this->suppressed, $ids );
         }
      else{
         array_push( $this->suppressed, $ids );
         }
      }



   // }}}
   }

?>
