<?php
/**
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
 * Paths to diferent files array
 *
 * array items should be added using the following
 * standars:
 *
 * array_name['group']['sub_group']['identerfyie']
 * where group and sub group are mandantory
 *
 * NOTE!! 
 * Because of the wiki style page naming these paths need k
 * to be absolute or relative to the base url and not relative to the 
 * current directory.
 *
 * eg: to reference the main css file you would do something like:
 * <?=$CONF['url']['base'].$CONF['url']['skins'].$CONF['url']['css']?>
 *
 */
 $CONF['url']['base'] = "./";
  # css files
 $CONF['url']['skins']  = "style/";
 $CONF['url']['css']    = "main.css";
  # java script files
 $CONF['url']['js']     = "js/";
 $CONF['url']['hide']   = "hide.js";
  # page content files
 $CONF['url']['page']   = "index.php/"; // only change this if you plane to
                                        // change the name of the index.php file
?>
