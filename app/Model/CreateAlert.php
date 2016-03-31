<?php
/****************************************************************************
 * poll.php   - Model for poll. Manages validation of poll creation, and refresh data from spooler.
 * version    - 3.0.1500
 * 
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 *
 * The Initial Developer of the Original Code is
 *   Louise Berthilson <louise@it46.se>
 *
 *
 ***************************************************************************/


class CreateAlert extends AppModel{

//test
      var $name = 'CreateAlert';
    

      // use is discouraged - but it's the only option so.. yeah use it
      function execute_query($query_string, $table) {
              $this->useTable = $table;
              $data = $this->query($query_string);

              return $data;

      }  


}
?>
