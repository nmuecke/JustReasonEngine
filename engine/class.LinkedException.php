<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

/**
 * Java-like exception with a cause
 *
 * @author  Romain Boisnard
 * @license Under the LGPL GNU Lesser General Public Liscence, report the actual liscence for details.
 * @author  Nial Muecke - Improved readerbility of output 2007-11-13 and commenting 2007-12-27 
 * @package Exceptions
 */

/**
 * class LinkedExeption
 *  Improved html display for ecxption handeling
 * @package Exceptions
 * @subpackage LinkedException
 */
class LinkedException extends Exception {
    // {{{ Class Variables
   /**
    * Handle to php Exception 
    * @var Exception   
    * @access private
    */
    private $cause;
    // }}}
    // {{{ __construct( $_message = null, $_code = 0, Exception $_cause = null)
   /**
    * Constructro for LinkedException class
    *
    * @access public
    *
    * @param string message to be displayed
    * @param int error code
    * @param Exception
    * @return void
    */
    public function __construct( $_message = null, $_code = 0, Exception $_cause = null) {
        parent::__construct($_message, $_code);
        $this->cause = $_cause;
        }
   
   
    // }}}
    // {{{ __toString(
   /**
    * Returns a  string of the stack trace
    *
    * @access public
    *
    * @param void
    * @return string
    */
    public function __toString() {
        return self::showStackTrace();
        }
   
   
   
    // }}}
    // {{{ getCause()
   /**
    * returns the exception that throw the error 
    *
    * @access public
    *
    * @param void
    * @return Exception
    */
    public function getCause() {
        return $this->cause;
        }
   
   
   
    // }}}
    // {{{ getStackTrace()
   /**
    * Returns the stack trace array 
    *
    * @access public
    *
    * @param void
    * @return array
    */
    public function getStackTrace() {
        if ($this->cause !== null) {
            $arr = array();
            $trace = $this->getTrace();
            array_push($arr, $trace[0]);
            unset($trace);
            if (get_class($this->cause) == "LinkedException") {
                foreach ($this->cause->getStackTrace() as $key => $trace) {
                    array_push($arr, $trace);
                    }
                }
            else {
                foreach ($this->cause->getTrace() as $key => $trace) {
                    array_push($arr, $trace);
                    }
                }
            return $arr;
            }
        else {
            return $this->getTrace();
            }  
        }
   
   
   
    // }}}
    // {{{ showStackTrace()
   /**
    * create a html formated exception output 
    *
    * @access public
    *
    * @param void
    * @return string of HTML code
    */
    public function showStackTrace() {
        $htmldoc = '';

        $htmldoc.= "<div id='exception'>\n";
        $htmldoc.= "<h3>An exception was thrown!</h3>\n";
        $htmldoc.= "<div style=\"font-size: 0.9em;\">\n";
        $htmldoc.= "<b>Thrown by              :</b> ". __CLASS__  ."<br>\n";
        $htmldoc.= "<b>Exception code         :</b> $this->code <br/>\n";
        $htmldoc.= "<b>Exception message      :</b> $this->message<br/>\n";
        $htmldoc.= "<span style=\"color: #0000FF;\">\n";
        $i = 0;
        foreach ($this->getStackTrace() as $key => $trace) {
            $htmldoc.= $this->showTrace($trace, $i);
            $i++;
           }
        //$htmldoc.= "#$i {main}<br/>";
        unset($i);
        $htmldoc.= "</span>\n</div></div>";
        return $htmldoc;
      }
   
   
   
    // }}}
    // {{{ showTrace($_trace, $_i)
   /**
    * Create a HTMl string of the stack trace
    *
    * @access private
    *
    * @param string 
    * @param int
    * @return string of HTML code
    */
    private function showTrace($_trace, $_i) {
        $htmldoc = "#$_i ";
        if (array_key_exists("file",$_trace)) {
            $htmldoc.= $_trace["file"];
            }
        if (array_key_exists("line",$_trace)) {
            $htmldoc.= "(".$_trace["line"]."): ";//<br>\n &nbsp; &nbsp; &nbsp; &nbsp;";
            }
        if (array_key_exists("class",$_trace) && array_key_exists("type",$_trace)) {
            $htmldoc.= $_trace["class"].$_trace["type"];
          }
        if (array_key_exists("function",$_trace)) {
            $htmldoc.= $_trace["function"]."( ";
            if (array_key_exists("args",$_trace)) {
                for ( $xx = 0; $xx < count($_trace["args"]); $xx++) {
                // {{{ for loop contents
                    $args = $_trace["args"];
                    $type = gettype($args[$xx]);
                    $value = $args[$xx];
                    unset($args);
                    if ($type == "boolean") {
                        if ($value) {
                            $htmldoc.= "true";
                            }
                        else {
                            $htmldoc.= "false";
                            }
                        }
                    else if ($type == "integer" || $type == "double") {
                        if (settype($value, "string")) {
                            if (strlen($value) <= 20) {
                                $htmldoc.= $value;
                               }
                            else {
                                $htmldoc.= substr($value,0,17)."...";
                                }
                          }
                        else {
                            if ($type == "integer" ) {
                                $htmldoc.= "? integer ?";
                                }
                            else {
                                $htmldoc.= "? double or float ?";
                                }
                            }
                        }
                    else if ($type == "string") {
                        if (strlen($value) <= 18) {
                            $htmldoc.= "'$value'";
                            }
                        else {
                            $htmldoc.= "'".substr($value,0,15)."...'";
                            }
                        }
                    else if ($type == "array") {
                        $htmldoc.= "Array";
                        }
                    elseif ($type == "object") {
                        $htmldoc.= "Object";
                        }
                    elseif ($type == "resource") {
                        $htmldoc.= "Resource";
                        }
                    elseif ($type == "NULL") {
                        $htmldoc.= "null";
                        }
                    elseif ($type == "unknown type") {
                        $htmldoc.= "? unknown type ?";
                        }
                    unset($type);
                    unset($value);

                    if ( $xx < count($_trace["args"]) -1 ) {
                        $htmldoc.= ", ";
                        }
                    // }}}
                    }
                }           
            $htmldoc.= " )<br/>";
            }
        return $htmldoc;
        }

   
   
    // }}}
}




?>
