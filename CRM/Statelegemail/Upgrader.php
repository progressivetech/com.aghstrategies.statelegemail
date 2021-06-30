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

  private $valueRecords = [
    [
      'label' => 'State Legislators',
      'value' => 'Statelegemail',
      'name' => 'State_Legislators',
    ],
    [
      'label' => 'State Legislators - Upper House',
      'value' => 'Statelegemailupper',
      'name' => 'State_Legislators_Upper',
    ],
    [
      'label' => 'State Legislators - Lower House',
      'value' => 'Statelegemaillower',
      'name' => 'State_Legislators_lower',
    ],
  ];

  /**
   * Create the custom field and interface option on install.
   *
   * Can't make these managed entities because they depend upon an option group
   * and custom data set from com.aghstrategies.petitionemail.
   */
  public function install() {
    try {
      $this->addRecipientSystemValues();
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
      $valueNames = array_column($this->valueRecords, 'name');
      \Civi\Api4\OptionValue::delete(FALSE)
        ->addWhere('option_group_id:name', '=', 'letter_to_recipient_system')
        ->addWhere('name', 'IN', $valueNames)
        ->execute();
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
      $valueNames = array_column($this->valueRecords, 'name');
      \Civi\Api4\OptionValue::update(FALSE)
        ->addWhere('option_group_id:name', '=', 'letter_to_recipient_system')
        ->addWhere('name', 'IN', $valueNames)
        ->addValue('is_active', TRUE)
        ->execute();
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
      $valueNames = array_column($this->valueRecords, 'name');
      \Civi\Api4\OptionValue::update(FALSE)
        ->addWhere('option_group_id:name', '=', 'letter_to_recipient_system')
        ->addWhere('name', 'IN', $valueNames)
        ->addValue('is_active', FALSE)
        ->execute();
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.statelegemail')));
      // TODO: display error.
    }
  }

  /**
   * Add the ability to target only the upper or lower house.
   */
  public function upgrade_1000() {
    $this->ctx->log->info('State Legislative Email: Add ability to target upper/lower house.');
    // Make sure they don't already exist.
    $this->addRecipientSystemValues();
    return TRUE;
  }

  /**
   * (Re-)generate the complete list of recipient values.
   */
  public function addRecipientSystemValues() {
    $valueNames = array_column($this->valueRecords, 'name');
    $existingValues = \Civi\Api4\OptionValue::get(FALSE)
      ->addSelect('name')
      ->addWhere('option_group_id:name', '=', 'letter_to_recipient_system')
      ->addWhere('name', 'IN', $valueNames)
      ->execute()
      ->column('name');
    foreach ($this->valueRecords as $valueRecord) {
      if (!in_array($valueRecord['name'], $existingValues)) {
        \Civi\Api4\OptionValue::create(FALSE)
          ->addValue('option_group_id:name', 'letter_to_recipient_system')
          ->addValue('label', $valueRecord['label'])
          ->addValue('value', $valueRecord['value'])
          ->addValue('name', $valueRecord['name'])
          ->addValue('is_active', TRUE)
          ->execute();
      }
    }
  }

}
