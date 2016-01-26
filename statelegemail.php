<?php
/**
 * @file
 * Extension to email state legislators from CiviCRM petitions.
 *
 * Copyright (C) 2014-15, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */

require_once 'statelegemail.civix.php';

/**
 * Implements hook_civicrm_buildForm().
 */
function statelegemail_civicrm_buildForm($formName, &$form) {
  switch ($formName) {
    case 'CRM_Campaign_Form_Petition':
      CRM_Core_Resources::singleton()->addScriptFile('com.aghstrategies.statelegemail', 'js/addressField.js');
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function statelegemail_civicrm_navigationMenu(&$menu) {
  _statelegemail_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('State Legislators Email Settings', array('domain' => 'com.aghstrategies.statelegemail')),
    'name' => 'statelegemail_settings',
    'url' => 'civicrm/statelegemail/settings',
    'permission' => 'administer CiviCRM',
    'operator' => 'AND',
    'separator' => 0,
  ));
  _statelegemail_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function statelegemail_civicrm_config(&$config) {
  _statelegemail_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function statelegemail_civicrm_xmlMenu(&$files) {
  _statelegemail_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function statelegemail_civicrm_install() {
  _statelegemail_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function statelegemail_civicrm_uninstall() {
  _statelegemail_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function statelegemail_civicrm_enable() {
  _statelegemail_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function statelegemail_civicrm_disable() {
  _statelegemail_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function statelegemail_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _statelegemail_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function statelegemail_civicrm_managed(&$entities) {
  _statelegemail_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function statelegemail_civicrm_caseTypes(&$caseTypes) {
  _statelegemail_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function statelegemail_civicrm_angularModules(&$angularModules) {
  _statelegemail_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function statelegemail_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _statelegemail_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
