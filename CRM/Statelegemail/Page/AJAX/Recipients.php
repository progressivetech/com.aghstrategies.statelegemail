<?php

require_once 'CRM/Core/Page.php';

/**
 * AJAX callback for newspaper listing.
 */
class CRM_Statelegemail_Page_AJAX_Recipients extends CRM_Core_Page {
  /**
   * Provide the newspapers for the given postal code.
   *
   * Echos JSON object of the names, photo URLs, and emails.
   */
  public function run() {
    $addressValues = array(
      'Postal_Code_Field' => CRM_Utils_Request::retrieve('zip', 'Int'),
      'State_Province_Field' => CRM_Utils_Request::retrieve('state', 'Int'),
      'City_Field' => CRM_Utils_Request::retrieve('city', 'Int'),
      'Street_Address_Field' => CRM_Utils_Request::retrieve('address', 'Int'),
    );

    foreach ($addressValues as $val) {
      if (empty($val)) {
        return;
      }
    }

    $recipients = CRM_Petitionemail_Interface_Statelegemail::findRecipients($addressValues);
    CRM_Utils_JSON::output($recipients);
  }

}
