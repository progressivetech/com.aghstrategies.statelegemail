<?php
/**
 * @file
 * Admin form.
 *
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */

require_once 'CRM/Core/Form.php';

/**
 * Administrative settings for the extension.
 */
class CRM_Statelegemail_Form_Settings extends CRM_Core_Form {

  /**
   * Build the form.
   */
  public function buildQuickForm() {
    $this->addSelect('states', array(
      'entity' => 'address',
      'field' => 'state_province_id',
      'multiple' => TRUE,
      'label' => ts('Enabled states', array('domain' => 'com.aghstrategies.statelegemail')),
      'country_id' => 1228,
      'placeholder' => ts('- any -', array('domain' => 'com.aghstrategies.statelegemail')),
    ));

    $this->add('text', 'key', ts('Sunlight Foundation API key', array('domain' => 'com.aghstrategies.statelegemail')));

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Save', array('domain' => 'com.aghstrategies.statelegemail')),
        'isDefault' => TRUE,
      ),
    ));

    // Send element names to the form.
    $this->assign('elementNames', array('states', 'key'));
    parent::buildQuickForm();
  }

  /**
   * Populate defaults.
   *
   * @return array
   *   The default values.
   */
  public function setDefaultValues() {
    return array(
      'states' => CRM_Petitionemail_Interface_Statelegemail::getValidStates(),
      'key' => CRM_Petitionemail_Interface_Statelegemail::getApiKey(),
    );
  }

  /**
   * Save values.
   */
  public function postProcess() {
    $values = $this->exportValues();

    try {
      $result = civicrm_api3('Setting', 'create', array('statelegemail_key' => $values['key']));
      $success = TRUE;
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.statelegemail')));
      CRM_Core_Session::setStatus(ts('Error saving Sunlight Foundation API key', array('domain' => 'com.aghstrategies.statelegemail')), 'Error', 'error');
      $success = FALSE;
    }

    try {
      $result = civicrm_api3('Setting', 'create', array('statelegemail_states' => $values['states']));
    }
    catch (CiviCRM_API3_Exception $e) {
      $error = $e->getMessage();
      CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'com.aghstrategies.statelegemail')));
      CRM_Core_Session::setStatus(ts('Error saving enabled states', array('domain' => 'com.aghstrategies.statelegemail')), 'Error', 'error');
      $success = FALSE;
    }

    if ($success) {
      CRM_Core_Session::setStatus(ts('You have successfully updated the state legislator petition settings.', array('domain' => 'com.aghstrategies.statelegemail')), 'Settings saved', 'success');
    }
    parent::postProcess();
  }

}
