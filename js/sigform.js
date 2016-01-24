CRM.$(function($) {
  var legList = $('<div/>', {
    id: 'legislator-list',
    class: 'crm-section',
  });
  var getRecips = function() {
    var zipval = $(zip).val();
    if (!$(stateProvince).val().length
      || !$(city).val().length
      || !$(address).val().length) {
      legList.html('');
      return;
    }
    if ((zipval.length == 5 && $.isNumeric(zipval))
      || (zipval.length == 10
        && $.isNumeric(zipval.substring(0,5))
        && zipval.substring(5,6) == '-'
        && $.isNumeric(zipval.substring(6)))) {
      $.getJSON(CRM.url('civicrm/statelegemail/ajax/recipients'),
        {
          zip: $(zip).val().substring(0,5),
          state: !$(stateProvince).val(),
          city: !$(city).val(),
          address: !$(address).val(),
        },
        function(data) {
          var names = [];
          $.each(data, function(index, value) {
            names.push(value.name);
            // TODO: handle photourl.
          })
          newsList.html('<div class="label"><label>Recipients</label></div><div class="content">Your letter will be delivered to the following legislators: '+names.join(', ')+'</div><div class="clear"></div>');
        }
      );
    } else {
      legList.html('');
    }
  }

  var messageField = document.getElementById(CRM.vars.statelegemail.message);
  $('.crm-petition-contact-profile').after(legList);
  var zip = document.getElementById(CRM.vars.statelegemail.Postal_Code_Field);
  var stateProvince = document.getElementById(CRM.vars.statelegemail.State_Province_Field);
  var city = document.getElementById(CRM.vars.statelegemail.City_Field);
  var address = document.getElementById(CRM.vars.statelegemail.Street_Address_Field);

  getRecips();
  $(zip).keyup(getRecips);
  $(city).keyup(getRecips);
  $(address).keyup(getRecips);
  $(stateProvince).change(getRecips);

});
