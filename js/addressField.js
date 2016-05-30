/**
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */

CRM.$(function($) {
  var addressFields = [
    'Street_Address',
    'City',
    'State_Province',
    'Postal_Code',
  ];
  $.each(addressFields, function(index, value) {
    var addrFieldSelector = '#customData .custom-group-Letter_To input[data-crm-custom="Letter_To:' + value + '_Field"]';
    var $addrField = $(addrFieldSelector);
    var initAddrField = function(f) {
      f.attr({
        placeholder: '- Select Field -',
        allowClear: 'true',
      });
      createEntityRef(f, $('#contact_profile_id').val(), value.toLowerCase());
    };
    initAddrField($addrField);

    $('body').on('crmLoad', function() {
      $addrField = $(addrFieldSelector);
      initAddrField($addrField);
    });

    $('#contact_profile_id').change(function() {
      $addrField.crmEntityRef('destroy');
      $addrField.val('');
      createEntityRef($addrField, $('#contact_profile_id').val(), value.toLowerCase());
    });
  });

  function createEntityRef($field, profileId, fieldName) {
    $field.crmEntityRef({
      entity: 'UFField',
      placeholder: '- Select Field -',
      api: {
        params: {
          uf_group_id: profileId,
          field_name: fieldName,
        },
      },
      allowClear: true,
      select: {minimumInputLength: 0},
    });
  }
});
