<?php
/**
 * @file
 * Settings metadata for com.aghstrategies.statelegemail.
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */

return array(
  'statelegemail_states' => array(
    'group_name' => 'State Legislators Email Preferences',
    'group' => 'statelegemail',
    'name' => 'statelegemail_states',
    'type' => 'Array',
    'default' => NULL,
    'add' => '4.6',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Array of valid states for sending email to legislators',
    'help_text' => 'Enabled state(s)',
  ),
  'statelegemail_key' => array(
    'group_name' => 'State Legislators Email Preferences',
    'group' => 'statelegemail',
    'name' => 'statelegemail_key',
    'type' => 'String',
    'default' => NULL,
    'add' => '4.6',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'API key from https://sunlightfoundation.com/api/accounts/register/)',
    'help_text' => 'Sunlight Foundation API Key',
  ),
  'statelegemail_stateconfig' => array(
    'group_name' => 'State Legislators Email Preferences',
    'group' => 'statelegemail',
    'name' => 'statelegemail_stateconfig',
    'type' => 'Array',
    'default' => NULL,
    'add' => '4.6',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Array of details about state legislators',
    'help_text' => 'State details',
  ),
);
