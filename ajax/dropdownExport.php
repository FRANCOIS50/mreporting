<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 Mreporting plugin for GLPI
 Copyright (C) 2003-2011 by the mreporting Development Team.

 https://forge.indepnet.net/projects/mreporting
 -------------------------------------------------------------------------

 LICENSE

 This file is part of mreporting.

 mreporting is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 mreporting is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with mreporting. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (!defined('GLPI_ROOT')){
   die("Can not acces directly to this file");
}

Session::checkLoginUser();

if (isset($_POST['ext'])
      && !empty($_POST['ext'])) {
   if ($_POST['ext'] == "odt") {
      echo "&nbsp;";
      $option[0] = $LANG['plugin_mreporting']["export"][3];
      $option[1] = $LANG['plugin_mreporting']["export"][4];
      Dropdown::showFromArray("withdata", $option, array());
      
      echo "&nbsp;<input type='submit' name='export' value=\"".
      __("Post")."\" class='submit'>";
   } else if ($_POST['ext'] == "svg") {
      
      $randname = $_POST['randname'];
      echo "<form method='post' action='export_svg.php' id='export_svg_form' ".
         "style='margin: 0; padding: 0' target='_blank'>";
      echo "<input type='hidden' name='svg_content' value='none' />";
      echo "<p><a class='submit' id='export_svg_link' target='_blank' href='#' ".
                        "onClick='return false;'>".$LANG['buttons'][2]."</a></p>";
      Html::Closeform();
      echo "<script type='text/javascript'>
            Ext.get('export_svg_link').on('click', function () {
               var svg_content = vis{$randname}.scene[0].canvas.innerHTML;
               var form = document.getElementById('export_svg_form');
               form.svg_content.value = svg_content;
               form.submit();

               //set new crsf token for svg export
               Ext.Ajax.request({
                  url: '../ajax/get_new_crsf_token.php',
                  success: function(response, opts) {
                     var token = response.responseText;
                     Ext.select('#export_svg_form input[name=_glpi_csrf_token]')
                        .set({'value': token});
                     
                  },
                  failure: function(response, opts) {
                     console.log('server-side failure with status code '+response.status);
                  }
               });
               
               //set new crsf token for main form
               Ext.Ajax.request({
                  url: '../ajax/get_new_crsf_token.php',
                  success: function(response, opts) {
                     var token = response.responseText;
                     Ext.select('#mreporting_date_selector input[name=_glpi_csrf_token]')
                        .set({'value': token});
                     
                  },
                  failure: function(response, opts) {
                     console.log('server-side failure with status code '+response.status);
                  }
               });
               
            });
         </script>";
   } else {
      
      echo "&nbsp;<input type='submit' name='export' value=\"".
      __("Post")."\" class='submit'>";
   }
   
}

?>