<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
/**
 * An insterlation file for the JustReason Engine
 * This file can be run through a browser to install the JustReason Engine 
 * And configure the database to use the engine.
 *
 * @package JustReason_Installer
 * @version 1.0
 *
 * @author Nial Muecke <nmuecke@justsys.com.au>
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Copyright (c) 2007-2008, Nial Muecke
 *
 */

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
/**
 * sets the path to the local PEAR insterlation
 */
require_once( '../config/include_files.inc' );
?>
<html>
<head>
<title>JustReason Engine Setup</title>
<?php // {{{ css ?>
<style type="text/css" media="screen,projection">
body {
   padding: 0px;
   margin: 0px;

   width: 70%;
   padding: 15%;
   padding-top: 50px;
   padding-bottom: 200px;
   font: x-small sans-serif;
   font-size: 0.85em;
   }
#body {
   padding: 0px;
   margin: 0px;
   border: 1px solid #7B9DB7; 
   }
table {
   width: 100%;
   }
td{ 
  width: 50%;
  }
i{
 font-size: 0.8em;
 }
h3 {
   text-indent: 1em;
   padding-top: 0px;
   margin: 0px;
   background-color: #7B9DB7;
   width: 100%;
   color: #EEEEEE;
   font-size: 2em;
   }

</style>
<?php // }}} ?>
</head>

<body>
<div id='body'>
<h3>JustReason Engine Installer</h3>
<?php
  // {{{ new HTML_QuickForm( "", "POST", "justReasoneInstaller.php" )
  $form = new HTML_QuickForm( "", "POST", "justReasoneInstaller.php" );
  // {{{ $form->setDefaults
  if( !isset($_POST['setup'] )){
     $form->setDefaults( array(
                            "page_name" => "index",
                            //"page_save" => "",
                            //"page_reset"  => "",
                            //"page_display_type" => "",
                            "db_name" => "JustReason",
                            "db_host" => "localhost",
                            "db_type" => "MySQL",
                            "db_user_name" => "JustReason_user",
                            //"db_p1_user" => "",
                            //"db_p2_user" => "",
                            //"db_create_user" => "",
                            //"db_use_root" => "",
                            "db_u_root" => "root",
                            //"db_p_root" => "",
                            "db_ga_name" => "genericarguments",
                            "db_cv_name" => "claimvalues",
                            "db_sd_name" => "sessiondata",
                            "db_rn_name" => "rootnodes",
                            //"db_gperfix" => "",
                            //"checkbox" => "",
                                  ) );
     }
     // }}}
  // {{{ page setup
  $form->addElement( "header",    "",                  "&nbsp Page Setup"                   );
  $form->addElement( "text",      "page_name",         "JustReason Engine page name"        );
  $form->addElement( "static",    "",                  "<i>Note! you should not include the". 
                                                       " extention, it will be .php</i>"    );
  $form->addElement( "checkbox",  "page_save",         "Include save/load functionality?"   );
  $form->addElement( "checkbox",  "page_reset",        "Incluce reset Button?"              );
  $form->addElement( "select",    "page_display_type", "Display Type", array( 
                                                 "RenderNodeText"         => "Text", 
                                                 "RenderNodeRadioButtons" => "Radio Button", 
                                                 "RenderNodeDropBox"      => "Drop Box" 
                                                                          )                 );
  // }}}
  // {{{ database config
  $form->addElement( "header",    "",                  "&nbsp Database Setup"               );
  $form->addElement( "text",      "db_name",           "Database name"                      );
  $form->addElement( "text",      "db_host",           "Database host Server"               );
  $form->addElement( "select",    "db_type",           "Type", array( 
                                                                  "mysql" => "mysql", 
                                                                  "sqlite" => "sqlite", 
                                                                  "odbc"  => "odbc",
                                                                  "pgsql" => "pgsql" 
                                                                  )   );
  $form->addElement( "text",      "db_user_name",      "JustReason database user"           );
  $form->addElement( "password",  "db_p1_user",        "Password"                           );
  $form->addElement( "password",  "db_p2_user",        "Re enter password"                  );
  $form->addElement( "checkbox",  "db_create_user",    "Create new user?"                   );
  $form->addElement( "checkbox",  "db_use_root",       "Install using root?"                );
  $form->addElement( "text",      "db_u_root",         "Root user or othe privlaged user"   );
  $form->addElement( "password",  "db_p_root",         "Password"                           );
  // }}}
  // {{{ table config
  $form->addElement( "header",    "",                  "&nbsp Database Table Configuration" );
  $form->addElement( "text",      "db_gprefix",        "Global Prefix"                      );
  $form->addElement( "text",      "db_rn_name",        "Root Nodes tabe name"        );
  $form->addElement( "text",      "db_ga_name",        "Generic Arguments tabe name"        );
  $form->addElement( "text",      "db_cv_name",        "Claim Values table name"            );
  $form->addElement( "text",      "db_sd_name",        "Session Data storage table name"    );
  $form->addElement( "static",    "",                  "<i>Note! The Session Data table is ". 
                                                       "only used if you tick the ".
                                                       "save/load option.</i>"              );
  // }}}
  // {{{ install sample databalse 
  $form->addElement( "header",    "",                  "&nbsp Install Sample Databases"     );
  $form->addElement( "checkbox",  "db_install_sample", "Icecream"                           );
  // }}}
  // {{{ install 
  $form->addElement( "header",    "",                  "&nbsp Install"                      );
  $form->addElement( "submit",    "setup",             "Install"                            );
  // }}}
  // {{{ form->addRule
  $required = "Please Fill out this field.";
  $punctuation = "Field contains special charactor(s).";
  $form->addRule( "db_name",           $required,    'required' );
  $form->addRule( "db_host",           $required,    'required' );
  $form->addRule( "db_type",           $required,    'required' );
  $form->addRule( "db_rn_name",        $required,    'required' );
  $form->addRule( "db_ga_name",        $required,    'required' );
  $form->addRule( "db_cv_name",        $required,    'required' );
  $form->addRule( "db_user_name",      $required,    'required' );
  $form->addRule( "db_p1_user",        $required,    'required' );
  $form->addRule( "db_p2_user",        $required,    'required' );
  $form->addRule( "page_display_type", $required,    'required' );
  $form->addRule( "page_name",         $required,    'required' );
  $form->addRule( "pear_path",         $required,    'required' );
  $form->registerRule( "special_char","callback","specialChars" );
  $extras = array ( ":", "/", "\\" );
  $form->addRule( "page_name",         $punctuation, "special_char", $extras);
  $form->addRule( "db_name",           $punctuation, "special_char", $extras );
  $form->addRule( "db_ga_name",        $punctuation, "special_char", $extras );
  $form->addRule( "db_ga_prefix",      $punctuation, "special_char", $extras );
  $form->addRule( "db_cv_name",        $punctuation, "special_char", $extras );
  $form->addRule( "db_cv_prefix",      $punctuation, "special_char", $extras );
  $form->addRule( "db_sd_name",        $punctuation, "special_char", $extras );
  $form->addRule( "db_sd_prefix",      $punctuation, "special_char", $extras );
  $form->addRule( "db_user_name",      $punctuation, "special_char", $extras );
  $form->addRule( "db_u_root",         $punctuation, "special_char", $extras );
//  $form->addRule( "db_host",           $punctuation, "special_char" );
  $form->addRule(array('db_p1_user', 'db_p2_user'), 'The passwords do not match', 'compare' );


  // }}}
  // {{{ has been posted
  if( isset($_POST['setup'] )){
     if( $form->validate( ) ){
        creatPage();
        creatLocalSettings();
        $db =& creatDB();
        setupDB( $db );
        loadSampledb( $db );
        echo "<h3> Done </h3>";
        echo "Now move the <b>LocalSettings.inc</b> file and the <b>".$_POST['page_name'].".php</b> into the ".realpath("..")." directory.";
        }
     else{
        echo "<font color='red'>Somthing is not quite right, check to see where the problem is.</font>";
        $form->display();
        }
     }
  else{
     $form->display();
     }
    // }}}
   // }}}
?>
</div>
</body>
</html>

<?php
// {{{ funcions
// {{{ creatPage()
   /**
    * Creats the main page for the JustReason engine
    *
    * @access public
    *
    * @name $_POST
    * @global array
    * @param  void
    * @return void
    */
function creatPage(){
   echo "<h4>attempting to create page ./".$_POST['page_name'].".php</h4>";
   echo "<ul>";
   if( !is_writable( "./" ) ){
      die("<li><b>Cannot write to directory!<b><li>" );
      }
   if( file_exists( "./".$_POST['page_name'].".php" ) ){
      "<li><b>File already exists ./".$_POST['page_name'].".php, Removing File<b><li>";
      }
   if(($fd =& fopen( "./".$_POST['page_name'].".php", "w" ) == false )){
      die("<li><b>Error opening file stream!<b><li>");
      } 
   echo "<li>File stream open</li>";
   $content = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n".
              "<html>\n".
              "<head>\n".
              "<title>JustReason</title>\n".
              "<style type=\"text/css\" media=\"screen,projection\">\n".
              "body {\n".
              "padding: 0px;\n".
              "   margin: 0px;\n".
              "   width: 70%;\n".
              "   padding: 15%;\n".
              "   padding-top: 50px;\n".
              "   padding-bottom: 200px;\n".
              "   font: x-small sans-serif;\n".
              "   font-size: 0.85em;\n".
              "   }\n".
              "#body {\n".
              "   padding: 0px;\n".
              "   margin: 0px;\n".
              "   border: 1px solid #7B9DB7;\n".
              "   }\n".
              "table {\n".
              "   width: 100%;\n".
              "   }\n".
              "i{\n".
              "   font-size: 0.8em;\n".
              "   }\n".
              "h3 {\n".
              "   text-indent: 1em;\n".
              "   padding-top: 0px;\n".
              "   margin: 0px;\n".
              "   background-color: #7B9DB7;\n".
              "   width: 100%;\n".
              "   color: #EEEEEE;\n".
              "   font-size: 2em;\n".
              "   }\n".
              "</style>\n".

              "</head>\n".

              "<body>\n".
              "<div id='body'>\n".
              "<h3>JustReason Engine</h3>\n".

              "<?php\n".
              "// includes the LocalSettings file that holds many of the config settings\n".
              "require_once( './LocalSettings.inc' ); \n".
              '$user = "";'."\n";

   if( fwrite( $fd, $content ) === FALSE ){
      die("<li><b>Error writing to file stream!<b><li>" );
      }
   echo "<li>Writing[...";
   $content = 'session_name( $CONF[\'sessionID\'] );'."\n".
              'session_start();'."\n".
              '/*'."\n".
              ' * pare login function'."\n".
              ' */'."\n".
              '$options = array(\'dsn\' => $db );  '."\n".
              '$login = new Auth("MDB2", $options, "loginUser" );'."\n\n".
              'if( isset( $_POST[\'logout\'] ) ){ '."\n".
              '   $login->logout();'."\n".
              '   //$login = new Auth("MDB2", $options, "loginUser" );'."\n".
              '   }'."\n".
              '$login->start();'."\n".
              'if( $login->checkAuth() ){'."\n".
              '   $user = $login->getUsername();'."\n";

   if( isset( $_POST['page_save']) && $_POST['page_save'] == "1" && fwrite( $fd, $content ) === FALSE ){
      die( "</li><li><b>Error writing to file stream!<b><li>" );
      }
   echo "...";
 
   $content = "   // creates new engine opject \n". 
              '   $jre = new JustReasonEngine( $db, $CONF, $user );'."\n".
              "   // checks to see if any data was posted that changes the state of the tree \n".
              '   $jre->updateChanges( );'."\n".
              "   // check to see if an inference need to be made and if so make it\n".
              '   $jre->infer( );'."\n".
              "   // this chech to see what acction the user has made and how that effects the state of the tree \n".
              '   $jre->performAction( );'."\n".
              "   // add a heading to the display \n".
              '   $jre->addRow( array( "Prefix", "Claims", "Suffix", "NotSure", "Relivance", "Other" ), "", "TH" );'."\n".
              "   // adds the node(s) to the display \n".
              '   $jre->addNode();'."\n".
              "   // add buttons to the display \n".
              '   $jre->addButton( "NextNode",     "Next",         "NextNode" );'."\n".
              '   $jre->addButton( "PreviosNode",  "Last",         "PreviosNode", "left", -1 );'."\n";

   if ( fwrite( $fd, $content ) === FALSE ){
      die("</li><li><b>Error writing to file stream!<b><li>");
      }
   echo "...";

   $content = '   $jre->addButton( "Reset",        "Reset",        "Rest",        "center", -1 );'."\n";
   if( isset( $_POST['page_reset']) &&  $_POST['page_reset'] == "1" && fwrite( $fd, $content ) === FALSE ){
      die("</li><li><b>Error writing to file stream!<b><li>");
      }
   echo "...";

   $content = '   $jre->addButton( "Load",         "Load",         "Load",        "right"  );'."\n".
              '   $jre->addButton( "logout",       "logout",       "Log Out",     "center", -1 );'."\n".
              '   $jre->addButton( "save",         "Save",         "Save",        "left", -1 );'."\n";

   if( isset( $_POST['page_save']) &&  $_POST['page_save'] == "1" && fwrite( $fd, $content ) === FALSE ){
      die( "</li><li><b>Error writing to file stream!<b><li>" );
      }
   echo "...";

   $content = "   // displays the content that was added above \n".
              '   $jre->display();'."\n".
              '   unset( $jre );'."\n";
   if( fwrite( $fd, $content ) === FALSE ){
      die("</li><li><b>Error writing to file stream!<b><li>" );
      }
   echo "...";

   $content = "   }\n"; 
   if( isset( $_POST['page_save']) &&  $_POST['page_save'] == "1" && fwrite( $fd, $content ) === FALSE ){
      die( "</li><li><b>Error writing to file stream!<b><li>" );
      }
   echo "...";
 
   $content = '?>'."\n".
              "</body>\n".
              "</html>\n";
   if( fwrite( $fd, $content ) === FALSE ){
      die("</li><li><b>Error writing to file stream!<b><li>" );
      }
   echo "...]</li>";
   echo "<li><b>Page succsefuly created!</b></li>";
   echo "</ul>";
   }
   // }}}
// {{{ creatLocalSettings()
   /**
    * Creats the Local Settings file
    *
    * @access public
    *
    * @name $_POST
    * @global array
    * @param void
    * @return void
    */
function creatLocalSettings(){
   echo "<h4>attempting to create page ./LocalSettings.inc.</h4>";
   echo "<ul>";
   if( !is_writable( "./" ) ){
      die("<li><b>Cannot write to directory!<b><li>" );
      }
   if( file_exists( "./LocalSettings.inc" ) ){
      "<li><b>File already exists ./LocalSettings.inc, Removing File<b><li>";
      }
   if(($fd =& fopen( "./LocalSettings.inc", "w" ) == false )){
      die("<li><b>Error opening file stream!<b><li>");
      }
   echo "<li>File stream open</li>";
   
   $content = "<?php\n";
   if( fwrite( $fd, $content ) === FALSE ){
      die("<li><b>Error writing to file stream!<b><li>" );
      }
   echo "<li>Writing[......";

   $content = "".
              "\n".
              "// includes the varios needed files \n".
              "require_once( './config/include_files.inc' ); \n\n".
              '$CONF[\'sessionID\'] = "'."SSID_JustReason\"; \n".
              "\n".
              "\n".
	      "# Engine config vars\n".
              '$CONF[\'renderType\'] = "'.$_POST['page_display_type'].'";'."\n".
	      '$CONF[\'inferMethod\'] = "InferWeightedSum";'."\n".
	      '$CONF[\'io_type\'] = "SessionIO_DB";'."\n".
              "\n".
              "\n".
              "# name of the data base\n".
              '$db[\'phptype\']                      = "'.$_POST['db_type']."\";\n".
              '$db[\'username\']                     = "'.$_POST['db_user_name']."\";\n".
              '$db[\'password\']                     = "'.$_POST['db_p1_user']."\";\n".
              '$db[\'hostspec\']                     = "'.$_POST['db_host']."\";\n".
              '$db[\'database\']                     = "'.$_POST['db_name']."\";\n".
              '$db[\'globalPrefix\']                 = "'.$_POST['db_gprefix']."\";\n".
              "\n".
              "# pear table for athenticating users \n".
              '$db[\'tbName\'][\'auth\']             = "auth'."\";\n".
              "\n".
              "# data base configeration for DSS engine \n".
              "# names for the DSS engine tabes \n".
              '$db[\'tbName\'][\'rootNodes\']        = "'.$_POST['db_rn_name']."\";\n".
              '$db[\'tbName\'][\'claimValues\']      = "'.$_POST['db_cv_name']."\";\n".
              '$db[\'tbName\'][\'genericArguments\'] = "'.$_POST['db_ga_name']."\";\n".
              "# name for table where session data is stored\n".
              '$db[\'tbName\'][\'sessionData\']      = "'.$_POST['db_sd_name']."\";\n\n".
	      'Logger::set( array( \'toScreen\'=>false ) );';

   if( fwrite( $fd, $content ) === FALSE ){
      die("<li><b>Error writing to file stream!<b><li>" );
      }
   echo "............";

   $content = "?>\n";
   if( fwrite( $fd, $content ) === FALSE ){
      die("<li><b>Error writing to file stream!<b><li>" );
      }
   echo "......]</li>";
   echo "<li><b>Page succsefuly created!</b></li>";
   echo "</ul>";
   }
   // }}}
// {{{ $db & creatDB()
   /**
    * Creats a MBD2 database link
    *
    * @access public
    * 
    * @name $_POST
    * @global array
    * @param void
    * @return MBD2 database link
    */
function & creatDB( ){
   echo "<h4>Connecting to Database</h4>";
   echo "<ul>";
     
   $dns = array( 
             "phptype" => $_POST['db_type'],
             "hostspec" => $_POST['db_host'],
             "database" => $_POST['db_name'],
     );
   if( !isset( $_POST['db_use_root'] )){
      $dns['username'] = $_POST['db_user_name'];
      $dns['password'] = $_POST['db_p2_user'];
      }
   else{
      $dns['username'] = $_POST['db_u_root'];
      $dns['password'] = $_POST['db_p_root'];
      }

   echo "<li>Using ".$dns['username']." to connect to db</li>";
   echo "<li>Attempting to connect to database";
   $db =& MDB2::connect( $dns );
   if( PEAR::isError($db) ){
      echo " ... Database dose not seem to exsit or user is not valid: ".$db->getMessage()."</li>"; 
      unset( $db );
      echo "<li>Attempting to connect to database server";
      unset( $dns['database'] );
      $db =& MDB2::connect( $dns );

      if( PEAR::isError($db) ){
         DIE( " ... <b>ERROR!</b> Unable to establish db conection to server: ".$db->getMessage()."</li></ul>" );
         }
      echo " ... Connection made.</li>";
      echo "<li>Attempting to create new database '".$_POST['db_name']."'";
      if( PEAR::isError( $db->query("CREATE DATABASE ".$_POST['db_name'] ) ) ){
          DIE(" ... Faild creating DB</li></ul>");
          }
      echo " ... Succsess, DB created.</li>";
      $dns['database'] = $_POST['db_name'];
      echo "<li>Attempting to connect to databse";
      $db =& MDB2::connect( $dns );

      if( PEAR::isError($db) ){
         DIE( " ... <b>ERROR!</b> Unable to establish db conection: ".$db->getMessage()."</li></ul>" );
         }

      $db->setFetchMode(MDB2_FETCHMODE_OBJECT);
      }
   echo " ... Connection made.</li>";

   if( isset( $_POST['db_use_root'] ) && isset( $_POST['db_create_user'] ) ){
      echo "<li> Attempting to create new user";
      $res =& $db->query( "CREATE USER '".$_POST['db_user_name']."'@'".$dns['hostspec']."' IDENTIFIED BY '".$_POST['db_p1_user']."' " );
      if( PEAR::isError( $res ) ){
         DIE(" ... Faild creating user user may already exist( ".$res->getMessage()." ).</li></ul>");
         }

      $res =& $db->query( "GRANT SELECT , ".
                          "      INSERT , ".
                          "      UPDATE   ".
                          "ON ".$dns['database']." . *       ".
                          "TO '".$_POST['db_user_name']."'@'".$dns['hostspec']."' ".
                          "IDENTIFIED BY '".$_POST['db_p1_user']."' ".
                          "WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 " );
      if( PEAR::isError( $res ) ){
         DIE(" ... Faild assigning user privlages ( ".$res->getMessage()." ).</li></ul>");
         }

      echo " ... done. </li>";

      }
   echo "</ul>";
   return $db;
   }
   // }}}
// {{{ setupDB( $db )
   /**
    * Creats a database and it properties
    * Will create a new database, set up the user permissions if required
    * and create tables
    * @access public
    *
    * @name $_POST
    * @global array
    * @param MDB2 link
    * @return void
    */
function setupDB( & $db ){
   echo "<h4>Creating Tables</h4>";
   echo "<ul>";
   echo "<li>Creating rootnodes table ( ".$_POST['db_gprefix'].$_POST['db_rn_name']." ) ";
   $res =& $db->query( "CREATE TABLE IF NOT EXISTS `".$_POST['db_gprefix'].$_POST['db_rn_name']."` ( ".
                       "  `id` varchar(30) NOT NULL default '', ".
                       "  `order` int(50) NOT NULL, ".
                       "  `tablePrefix` varchar(50) NOT NULL, ".
                       "  `desctiption` varchar(100) NOT NULL default '', ".
                       "  PRIMARY KEY  (`id`) ".
                       ") ENGINE=MyISAM DEFAULT CHARSET=latin1;" );
   if( PEAR::isError( $res ) ){
      die( "... Faild ( ".$res->getMessage()." )</li> " );
      } 
   echo " ... done.</li>";

   echo "<li>Creating genericarguments table ( ".$_POST['db_gprefix'].$_POST['db_ga_name']." ) ";
   $res =& $db->query( "CREATE TABLE IF NOT EXISTS `".$_POST['db_gprefix'].$_POST['db_ga_name']."` ( ".
                       "  `id` varchar(36) NOT NULL default '0', ".
                       "  `title` varchar(100) NOT NULL default '', ".
                       "  `prefix` mediumtext, ".
                       "  `suffix` mediumtext, ".
                       "  `parent` varchar(36) NOT NULL default '', ".
                       "  `relevance` mediumtext, ". 
                       "  `not_sure` varchar(36) default NULL, ".
                       "  `type` varchar(30) NOT NULL default '' ".
                       ") ENGINE=MyISAM DEFAULT CHARSET=latin1;");

   if( PEAR::isError( $res ) ){
      die( "... Faild ( ".$res->getMessage()." )</li> " );
      } 
   echo " ... done.</li>";

   echo "<li>Creating claimvalues table ( ".$_POST['db_gprefix'].$_POST['db_cv_name']." ) ";
   $res =& $db->query( "CREATE TABLE IF NOT EXISTS `".$_POST['db_gprefix'].$_POST['db_cv_name']."` ( ".
                       "  `id` tinyint(4) NOT NULL default '0', ".
                       "  `argument_id` varchar(36) NOT NULL default '0', ".
                       "  `claim` mediumtext, ". 
                       "  `threshold` tinyint(4) default NULL, ".
                       "  `weight` varchar(36) default NULL, ".
                       "  `order` tinyint(4) default NULL ".
                       ") ENGINE=MyISAM DEFAULT CHARSET=latin1" );
   if( PEAR::isError( $res ) ){
      die( "... Faild ( ".$res->getMessage()." )</li> " );
      } 
   echo " ... done.</li>";
 
   if( isset( $_POST['page_save'] ) && $_POST['page_save'] == 1 ){
      echo "<li>Creating claimvalues table ( auth ) ";
      $res =& $db->query( "CREATE TABLE IF NOT EXISTS `auth` ( ".
                          "  `username` varchar(50) NOT NULL default '', ".
                          "  `password` varchar(32) NOT NULL default '', ".
                          "  PRIMARY KEY  (`username`), ".
                          "  KEY `password` (`password`) ".
                          " ) ENGINE=MyISAM DEFAULT CHARSET=latin1;" );
      if( PEAR::isError( $res ) ){
         die( "... Faild ( ".$res->getMessage()." )</li> " );
         } 
      echo " ... done.</li>";
      echo "<li>Creating claimvalues table ( ".$_POST['db_gprefix'].$_POST['db_sd_name']." ) ";
      $res =& $db->query( "CREATE TABLE IF NOT EXISTS `".$_POST['db_gprefix'].$_POST['db_sd_name']."` ( ".
                          "  `userID` varchar(30) NOT NULL, ".
                          "  `sessionData` blob NOT NULL, ".
                          "  UNIQUE KEY `userID` (`userID`) ".
                          ") ENGINE=MyISAM DEFAULT CHARSET=latin1;" );
      if( PEAR::isError( $res ) ){
         die( "... Faild ( ".$res->getMessage()." )</li> " );
         }
      echo " ... done.</li>";

      }
   echo "<li> tables created</li>";
   echo "</ul>";
   }
   // }}}
// {{{ createSampleDB( $db )
   /**
    * Loads the sample database into the newly created database 
    *
    * @access public
    *
    * @name $_POST
    * @global array
    * @param MDB2
    * @return void
    */
function loadSampledb( & $db ){
   if( isset( $_POST['db_install_sample'] ) ){
      echo "<h4>Uploading sample db</h4>";
      echo "<ul>";
      echo "<li> Inserting data into ".$_POST['db_gprefix'].$_POST['db_rn_name'];
      $res =& $db->query ( "INSERT INTO `".$_POST['db_gprefix'].$_POST['db_rn_name']."` ".
                           "  (`id`, `order`, `tablePrefix`, `desctiption`) ".
                           "VALUES ". 
                           "  ('RootNode', 1, '', 'root node')" 
                         );

      if( PEAR::isError( $res ) ){
         die( " ... Faild ( ".$res->getMessage()." )</li> " );
         } 
      echo " ... done. </li>";

      echo "<li> Inserting data into ".$_POST['db_gprefix'].$_POST['db_ga_name'];
      $res =& $db->query ( "INSERT INTO `".$_POST['db_gprefix'].$_POST['db_ga_name']."` ".
                           "  (`id`, `title`, `prefix`, `suffix`, `parent`, `relevance`, `not_sure`, `type` ) ".
                           "VALUES ".
                           "  ('RootNode', 'Test root node', 'Do you like icecream?', '', '', 'Every one should like icecream', '1', ''), ".
                           "  ('con_like_icecream', 'Like Icecream', 'Horay! You like icecream!!', '', '', '', '0', ''), ".
                           "  ('con_dont_like)_icecream', 'Dont like icecream', 'Well some people are strange.', '', '', '', '0', ''), ".
                           "  ('AutoId_0000002', 'Cold Food', 'Cold food is', '.', 'RootNode', '', '0', ''), ".
                           "  ('AutoId_0000001', 'Deary Food', 'Deary food is', '.', 'RootNode', '', '0', ''), ".
                           "  ('AutoId_0000003', 'Sweet Food', 'Do you like sweet foods?', '', 'RootNode', '', '0', '')" 
                         );
      if( PEAR::isError( $res ) ){
         die( " ... Faild ( ".$res->getMessage()." )</li> " );
         } 
      echo " ... done. </li>";

      echo "<li> Inserting data into ".$_POST['db_gprefix'].$_POST['db_cv_name'];
      $res =& $db->query ( "INSERT INTO `".$_POST['db_gprefix'].$_POST['db_cv_name']."` ".
                           "  (`id`, `argument_id`, `claim`, `threshold`, `weight`, `order` ) ".
                           "VALUES ".
                           "  (0, 'RootNode',       'no',                      6, 'con_dont_like)_icecream', 1), ".
                           "  (1, 'RootNode',       'Yes',                     20, 'con_like_icecream', 1), ".
                           "  (0, 'AutoId_0000002', 'Yummy',                   0, '5.0', 0), ".
                           "  (1, 'AutoId_0000002', 'Ok',                      0, '0.0', 1), ".
                           "  (2, 'AutoId_0000002', 'Discusting',              0, '0.0', 2), ".
                           "  (0, 'AutoId_0000001', 'Yummy',                   0, '5.0', 0), ".
                           "  (1, 'AutoId_0000001', 'nice every now and then', 0,  '2.0', 1), ".
                           "  (2, 'AutoId_0000001', 'cant stand it.',          0,  '0.0', 2), ".
                           "  (0, 'AutoId_0000003', 'Yes',                     0,  '3.0', 0), ".
                           "  (1, 'AutoId_0000003', 'No',                      0,  '0.0', 1)" ); 
      if( PEAR::isError( $res ) ){
         die( " ... Faild ( ".$res->getMessage()." )</li> " );
         } 
      echo " ... done. </li>";
      echo "</ul>";
      }
   }
   // }}}
// }}}
?>

