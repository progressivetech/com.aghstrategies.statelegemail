{* Copyright (C) 2014-15, AGH Strategies, LLC <info@aghstrategies.com> *}
{* Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt) *}

{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}

<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
