<?php
/****************************************************************************
 * phonedirectory.php
 *
 * The Initial Developer of the Original Code is
 *   Louise Berthilson <louise@it46.se>
 *
 *
 ***************************************************************************/

class PhoneDirectory extends AppModel{

    var $name = 'PhoneDirectory';

        // use is discouraged - but it's the only option so.. yeah use it
        function execute_query($query_string, $table) {
                $this->useTable = $table;
                $data = $this->query($query_string);

                return $data;

        }  

}

?>

