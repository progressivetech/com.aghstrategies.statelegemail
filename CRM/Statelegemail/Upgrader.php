<?php
/**
 * @file
 * Install/uninstall steps.
 *
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */

/**
 * Collection of upgrade steps.
 */
class CRM_Statelegemail_Upgrader extends CRM_Statelegemail_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Create the custom field and interface option on install.
   *
   * Can't make these managed entities because they depend upon an option group
   * and custom data set from com.aghstrategies.petitionemail.
   */
  public function install() {
    try {
      // Add interface as option for recipient system.
      $result = civicrm_api3('OptionValue', 'create', array(
        'option_group_id' => 'letter_to_recipient_system',
        'label' => 'State Legislators',
        'value' => 'Statelegemail',
        'name' => 'State_Legislators',
        'is_default' => 0,
        'is_active' => 1,
      ));

      // Add address fields, but only if they're not already added.
      $maxweight = civicrm_api3('CustomField', 'getvalue', array(
        'return' => "weight",
        'custom_group_id' => "Letter_To",
        'options' => array('limit' => 1, 'sort' => "weight DESC"),
      ));
      $maxweight = intval($maxweight);
      $addressFields = array(
        'Street_Address_Field' => array(
          'Street Address Field',
          "The ID number of the contact profile field storing the signer's street address.",
          'Int',
        ),
        'City_Field' => array(
          'City Field',
          "The ID number of the contact profile field storing the signer's city.",
          'Int',
        ),
        'State_Province_Field' => array(
          'State/Province Field',
          "The ID number of the contact profile field storing the signer's state.",
          'Int',
        ),
        'Postal_Code_Field' => array(
          'Postal Code Field',
          "The ID number of the contact profile field storing the signer's ZIP code.",
          'Int',
        ),
        'CC_Staff_Address' => array(
          'Send a BCC to',
          'The email address of someone who should receive a copy of the email.',
          'String',
        ),
        'CC_Staff_Text' => array(
          'BCC option label',
          'The label for the checkbox allowing signers to send a BCC.',
          'String',
        ),
      );
      foreach ($addressFields as $addressField => $details) {
        $fieldFound = civicrm_api3('CustomField', 'getcount', array(
          'custom_group_id' => "Letter_To",
          'name' => $addressField,
        ));
        if (!$fieldFound) {
          $maxweight++;
          $result = civicrm_api3('CustomField', 'create', array(
            'custom_group_id' => "Letter_To",
            'label' => ts($details[0], array('domain' => 'com.aghstrategies.statelegemail')),
            'name' => $addressField,
            'data_type' => $details[2],
            'html_type' => "Text",
            'is_active' => 1,
            'help_post' => ts($details[1], array('domain' => 'com.aghstrategies.statelegemail')),
            'column_name' => 'letter_to_' . strtolower($addressField),
            'weight' => $maxweight,
          ));
        }
      }
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(ts('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.statelegemail')));
      // TODO: display error and/or abort install.
    }
  }

  /**
   * Remove the custom field and interface option on uninstall.
   */
  public function uninstall() {
    try {
      $result = civicrm_api3('OptionValue', 'get', array(
        'option_group_id' => "letter_to_recipient_system",
        'value' => "Statelegemail",
      ));
      if (!empty($result['values'])) {
        foreach ($result['values'] as $key => $value) {
          $result = civicrm_api3('OptionValue', 'delete', array(
            'id' => $key,
          ));
        }
      }
      // Don't delete custom fields because they might be used by other delivery
      // extensions.
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.statelegemail')));
      // TODO: display error.
    }
  }

  /**
   * Enable stuff when extension is enabled.
   */
  public function enable() {
    try {
      $result = civicrm_api3('OptionValue', 'get', array(
        'option_group_id' => "letter_to_recipient_system",
        'value' => "Statelegemail",
      ));
      if (!empty($result['values'])) {
        foreach ($result['values'] as $key => $value) {
          $result = civicrm_api3('OptionValue', 'create', array(
            'id' => $key,
            'is_active' => 1,
          ));
        }
      }
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.statelegemail')));
      // TODO: display error.
    }
  }

  /**
   * Disable stuff when extension is disabled.
   */
  public function disable() {
    try {
      $result = civicrm_api3('OptionValue', 'get', array(
        'option_group_id' => "letter_to_recipient_system",
        'value' => "Statelegemail",
      ));
      if (!empty($result['values'])) {
        foreach ($result['values'] as $key => $value) {
          $result = civicrm_api3('OptionValue', 'create', array(
            'id' => $key,
            'is_active' => 0,
          ));
        }
      }
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.statelegemail')));
      // TODO: display error.
    }
  }

}
