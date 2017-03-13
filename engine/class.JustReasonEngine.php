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
 * @package JustReason_Engine 
 * @version 4.0
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2007, Nial Muecke
 *
 */

// }}}
// {{{ Include Files
/** 
 * Include Files
 */
require_once( "class.LinkedException.php" );
require_once( "class.Tree.php"            );
require_once( "class.TreeAssembler.php"   );
require_once( "class.Infer.php"           );
require_once( "class.UpdateTree.php"      );
require_once( "class.SessionIO_DB.php"    );
require_once( "class.Display.php"         );
// }}}
/**
 * class JustReasonEngine
 *  The JustResonEngine is a decision support system based on
 *  the combined uses of Decision Trees and Argument Trees
 * @package JustReason_Engine
 * @subpackage Engine
 */
class JustReasonEngine{
   
   // {{{ Class Variables
   // {{{ private $conf = array( "sessionID"=>"_JustReasonEngine" );
   /**
    * An array of varios configuration options
    * @var array   
    * @access private
    * @todo Is yet to be fully implermented
    */
   private $conf = array( "sessionID"=>"_JustReasonEngine" );
   // }}}
   // {{{ private $dbVars
   /**
    * An array of database variables
    * @var array   
    * @access private
    */
   private $dbVars;
   // }}}
   // {{{ private $suppressIds
   /**
    * Ids of HTML element that should not be displayd
    * @var array   
    * @access private
    */
   private $suppressIds;

   // }}}
   // {{{ private $tree;
   /**
    * Handle to the Tree object
    * @var Tree   
    * @access private
    */
   private $tree;
   // }}}
   // {{{ private $user

   /**
    * Who shold data be saved and loaded for 
    * @var string   
    * @access private
    */
   private $user;

   // }}}
   // {{{ protected $display
   /**
    * A handle to the Display class
    * @var Display   
    * @access protected
    */
   protected $display;

   // }}}
   // {{{ protected $renderType
   /**
    * How the nodes should be rendered
    * @var string   
    * @access protected
    */
   protected $renderType;

   // }}}
   // {{{ protected $sessionIO
   /**
   /**
    * Handle to the Session Input/Output class
    * @var SessionIO   
    * @access protected
    */
   protected $sessionIO;

   // }}}
   // {{{ protected $treeMode
   /**
    * Defines the mode to use to display the tree
    * @var const   
    * @access protected
    */
   protected $treeMode;

   // }}}
   // }}}
   // {{{ __CONSTRUCT( $dbVars, $conf = null , $displatType = "defulte", $inferMethod = "WeightedSum", $io_type = "SessionIO_DB" )
   /**
    * Constructor for JustReasonEngine class 
    *
    * @access public
    *
    * @param array of database variables
    * @param array of config variables
    *   - string how the nodes should be displayed
    *   - string how the nodes should be infered
    *   - string how data should be saves and loadesd
    *   - const  how the tree should be created
    * @param string the name of the user who should save abd write data
    * @return void
    */
   public function __CONSTRUCT( array $dbVars        , 
                                array $conf   = null , 
                                      $user   = ""  
                               ){
      try{

         array_add( $conf,
                    array(
                           "renderType"  => "RenderNodeDropBox" , 
                           "inferMethod" => "InferWeightedSum"       , 
                           "io_type"     => "SessionIO_DB"      , 
                           "treeMode"    => CREATE_AT_START      
                           )
                      );

         $this->dbVars   = $dbVars;
         $this->user     = $user;
         $this->conf     = $conf;

         $this->treeMode   = $conf['treeMode'];
         $this->io_type    = $conf['io_type'];
         $this->renderType = $conf["renderType"];

         $this->suppressIds = array( "NextNode", "PreviosNode" );

         if( !session_name() ){
            session_name( $this->conf['sessionID'] );
            session_start();
            }

         self::initiateTree();

         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }
      }



   // }}}
   // {{{ __DESTRUCT()
   /**
    * Destructor for JustReasonEngine class 
    *   Updated the $_SESSION['tree'] var before the class is closed
    * @access public
    *
    * @param void
    * @return void
    */
   public function __DESTRUCT(){
      $_SESSION['tree'] = $this->tree; //update the seesion once the object is done with
      }


   // }}}
   // {{{ addButton( $name, $lable, $value = "", $position = 'right', $row = null )
   /**
    * Wrapper function for the adding a Button to the Display  
    * @see Display::addButton()
    * @access public
    *
    * @param string 
    * @param string
    * @param string
    * @param int|string
    * @param int
    * @return void
    */
   public function addButton( $name, $lable, $value = "", $position = 'right', $row = null ){
      try{
         self::createDisplay();

         $this->display->addButton($name, $lable, $value, $position, $row );
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }

      }



   // }}}
   // {{{ addNode( & $node = null )
   /**
    * Adds a node to the display 
    *   If no node is passed in to the function then the current
    *   Node will be used.
    * @access public
    *
    * @param Node
    * @return void
    */
   public function addNode( & $node = null ){
      try{
         self::createDisplay();

         if( $node != null ){
            $this->display->addNode( $node );
            }
         else if( $this->tree->inArgument() ){
            $this->display->addArgumentNodes();
            }
         else{
            $this->display->addDecisionNode();
            }

         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }

      }



   // }}}
   // {{{ addRow( array $contents = NULL, $attributes = NULL, $type='TD' )
   /**
    * Wrapper function for adding a row to the display 
    * @see Display::addRow();
    * @access public
    *
    * @param array where each element value is used as td content
    * @param string|array of HTML tr properties
    * @param string ether 'TD' or 'TH'
    * @return void
    */
   public function addRow( array $contents = NULL, $attributes = NULL, $type='TD' ){
      try{
         self::createDisplay();

         $this->display->addRow( $contents, $attributes, $type );
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }

      } 



   // }}}
   // {{{ createDisplay()
   /**
    * Creates a display unless one already exists
    *   Also test to see if an argument is the current node, if so
    *   it suppresses some elements
    * @see Display::__CONSTRUCT
    * @access protected
    *
    * @param void
    * @return void
    */
   protected function createDisplay( ){
      if( !isset($this->display) ){
         $this->display = new display( $this->tree, $this->renderType );
         }
      if( $this->tree->inArgument() ){
         $this->display->suppress( $this->suppressIds );
         } 
      }



   // }}}
   // {{{ creatSessionIO( $userID )
   /**
    * Creates an handle for Input/Output operations unless one already exists 
    *
    * @access protected
    *
    * @param string the users id who the IO is for
    * @return SessionIO
    */
   protected function & creatSessionIO( $userID ){
       if( !isset( $this->sessionIO )) {
          $this->sessionIO =& new $this->io_type( $this->dbVars, $userID ); /// this will need to be changed if a different io type is uses
          }
       return $this->sessionIO;
       }



   // }}}
   // {{{ display()
   /**
    * Displays the Output 
    *   Will display what ever has been added to the display or
    *   If any errors have been caught they will be displayed instead
    * @access public
    *
    * @param void
    * @return void
    * @throws LinkedException "JustReasonEngine: Cannot display, there is set nothing to diplay!"
    */
   public function display(){
      try{
         if( isset($this->display) ){
            echo $this->display->getHtml();
            } 
         else{
            throw new LinkedException( "JustReasonEngine: Cannot display, there is set nothing to diplay!" );
            }
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }

      Logger::displayLog( 'Exc' );
      }


   // }}}
   // {{{ infer( $action = null )
   /**
    * Test to see if any inferences are reqested and initiates them if they are
    *   There are two way that an inference can be requested one is to suply the
    *   required param, the other is via the $_POST array
    *
    *   The Valid requests are:
    *   <pre>
    *       Infer         : this will infer the current node
    *       Infer&Refresh : this will infer the current node from it's leafs
    *       InferAll      : this will infere all node from the Argument RootNode
    *   </pre>
    * @access public
    *
    * @param array 
    * @return void
    */
   public function infer( $action = null ){
      try{
         if( $action == null ){
            $action = $_POST;
            }

         $infer = new Infer( $this->tree, $this->conf['inferMethod'] );

         switch( true ){
            case isset( $action['Infer'] ):
               $infer->inferClaim( $this->tree->getCurrentNode(), $this->conf['inferDebug'] );
               break;
            case isset( $action['Infer&Refresh'] ):
               $infer->inferFromLeaf( $this->tree->getCurrentNode() );
               break;
            case isset( $action['InferAll'] ):
               $infer->inferTree(); 
               break;
            default:
            }
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }
      }



   // }}}
   // {{{ initiateTree()
   /**
    * Initias the tree 
    *   If the tree exists in the $_SESSION array then the
    *   Tree be loaded from the session array else a new
    *   Tree will be created.
    * @access protected
    *
    * @param void
    * @return void
    * @throws LinkedException "JustReasonEngine: Unable to find a tree for the engine to work with!"
    */
   protected function initiateTree(){
      try{
         if( !isset( $_SESSION['tree'] ) ){
         
            $ta =& new TreeAssembler( $this->dbVars, $this->treeMode );
            $ta->newTree();
            $trees = $ta->getTrees();
            $_SESSION['tree'] =& $trees[0];
            // $this->tree =& $trees[0];

            }
        if( isset( $_SESSION['tree'] ) && ( is_subclass_of( $_SESSION['tree'], "Tree" ) || get_class( $_SESSION['tree'] ) == "Tree" )){
            $this->tree =& $_SESSION['tree'];
            }
         else{
            throw new LinkedException( "JustReasonEngine: Unable to find a tree for the engine to work with!" );
            } 
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }
      }



   // }}}
   // {{{ load( $userID )
   /**
    * Retreves saved Tree data from the Input/OutPut source 
    *
    * @access public
    *
    * @param string that identfys the user
    * @return void
    */
   public function load( $userID ){
      try{
         $sIO =& self::creatSessionIO( $userID );
         $this->tree = $sIO->load();
         $_SESSION['tree'] =& $this->tree;
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }
      }



   // }}}
   //{{{ performAction( array $action = null )
   /**
    * invoces the engine to perform varios action on the tree
    *   If the actions array is not pased in to the function
    *   then the $_POST array will be used insted.
    *   *Note: Only one action will be performed each call to the function.
    *
    *   The valid action are:
    *   <pre>
    *       NextNode      : move the decision tree to the next node.
    *       PreviosNode   : move the decision tree to the previos node.
    *       NotSure       : invoces the nodes argument tree.
    *       Infer         : moves the argument node to it's parent node.
    *       Infer&Refresh : moves the argument node to it's parent node.
    *       InferAll      : moves the argument node to it's parent node.
    *       ChangeClam    : unsets a set claim.
    *       Save          : saves the session data.
    *       Load          : loads the session data.
    *       Reset         : resets the Tree.
    *   </pre>     
    * @access public
    *
    * @param array of node ids
    * @return void
    * @throws LinkedException "JustReasonEngine: Unable to fined notsure node!"
    */
   public function performAction( array $action = null ){
      if( $action == null ){
         $action = $_POST;
         } 
      try{ 
         if( isset( $action['NextNode'] )){
            $this->tree->NextNode();
            }
         else if( isset( $action['PreviosNode'] )){
            $this->tree->previosNode();
            }
         else if( isset( $action['NotSure'] )){
            if( $this->tree->subArgument( $action['NotSure'] ) == false ){
               throw new LinkedException( "JustReasonEngine: Unable to fined notsure node!" );
               }
            }         
         else if( isset( $action['Infer']) || isset($action['Infer&Refresh']) ){
            $this->tree->argumentParent();
            }
         else if( isset( $action['InferAll'] )){
            // will probale do nothing
            }
         else if( isset( $action['ChangeClaim'] ) ){
            $this->tree->changeClaim( $action['ChangeClaim'] );
            }
         else if( isset( $action['Save'] )){
            self::Save( $this->user );
            }
         else if( isset( $action['Load'] )){
            self::Load( $this->user );
            }
         else if( isset( $action['Reset'] )){
            self::reset();
            }
         else{
            //echo "No Action Tacken";
            }
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }
      }



   //}}}
   // {{{ reset()
   /**
    * Resets the Tree forcing a new Tree to be created 
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function reset(){
      try{   
         unset( $this->tree );
         unset( $_SESSION['tree'] );
         
         self::initiateTree();
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }
 
      }



   // }}}
   // {{{ save( $userID )
   /**
    * Saves the users data to the Input/Output source
    *
    * @access public
    *
    * @param string that ids the user
    * @return void
    */
   public function save( $userID ){
      try{
         $sIO =& self::creatSessionIO( $userID );
         $sIO->save( $this->tree );
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }

      }



   // }}}
   // {{{ setRenderType( $renderType )
   /**
    * sets how the nodes should be rendered 
    *
    * @access public
    *
    * @param string
    * @return void
    */
   public function setRenderType( $renderType ){
         $this->renderType = $renderType;
         }



   // }}}
   // {{{ terminate()
   /**
    * Terminats the object
    *
    * @access public
    *
    * @param void
    * @return void
    */
   public function terminate(){
      self::__DESTRUCT();
      unset( $this );
      }


   // }}}
   // {{{ updateChanges( array $updates = null )
   /**
    * Invoces the system to update any nodes tha have changes 
    *
    * @access public
    *
    * @param array of Nodes
    * @return void
    */
   public function updateChanges( array $updates = null ){
      try{
         $update = new UpdateTree( $this->tree );
         if( $updates == null ){ 
            $update->updateFromPostData();
            }
         else{
            $update->updateNodes( $updates );
            }
         }
      catch( LinkedException $e ){
         Logger::log( $e, 1, "Exc" );
         }
      catch( Exception $e ){
         Logger::log( $e, 1, "Exc" );
         }
      }

    // }}}
   }
?>
