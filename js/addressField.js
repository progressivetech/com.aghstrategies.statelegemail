CRM.$(function($) {
  var addressFields = [
    'Street_Address',
    'City',
    'State_Province',
    'Postal_Code',
  ];
  $.each(addressFields, function(index, value) {
    var $addrField = $('#customData .custom-group-Letter_To input[data-crm-custom="Letter_To:' + value + '_Field"]');
    $addrField.attr({
      placeholder: '- Select Field -',
      allowClear: 'true',
    });
    createEntityRef($addrField, $('#contact_profile_id').val(), value.toLowerCase());

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
