/**
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */

CRM.$(function($) {
  var legList = $('<div/>', {
    id: 'legislator-list',
    class: 'crm-section',
  });
  var greeting = $('<div/>', {
    id: 'legislator-greeting',
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
          state: $(stateProvince).val(),
          city: $(city).val(),
          address: $(address).val(),
        },
        function(data) {
          var legLabel = $('<div/>', {
            class: 'label',
            html: '<label>Recipients</label>',
          });
          var legContent = $('<div/>', {
            class: 'content',
            html: 'Your letter will be delivered to the following legislators: ',
          });
          var legClear = $('<div/>', {
            class: 'clear',
          });
          var legCount = 0;
          $.each(data, function(index, value) {
            var legRow = $('<div/>', {
              html: value.name,
            });
            legContent.append(legRow);
            var greetingRow = $('<div/>', {
              html: value.greeting,
            });
            greeting.append(greetingRow);
            legCount++;
          });
          if (legCount) {
            legList.html([legLabel, legContent, legClear]);
          }
        }
      );
    } else {
      legList.html('');
      greeting.html('');
    }
  }

  var messageField = document.getElementById(CRM.vars.statelegemail.message);
  $('.crm-petition-contact-profile').after(legList);
  $(messageField).before(greeting);
  var zip = document.getElementById(CRM.vars.statelegemail.Postal_Code_Field);
  var stateProvince = document.getElementById(CRM.vars.statelegemail.State_Province_Field);
  var city = document.getElementById(CRM.vars.statelegemail.City_Field);
  var address = document.getElementById(CRM.vars.statelegemail.Street_Address_Field);

  getRecips();
  $(zip).keyup(getRecips);
  $(city).keyup(getRecips);
  $(address).keyup(getRecips);
  $(stateProvince).change(getRecips);

  $('.send_cc-section').appendTo($('.crm-petition-activity-profile'));

});
