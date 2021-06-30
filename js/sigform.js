/**
 * Copyright (C) 2016, AGH Strategies, LLC <info@aghstrategies.com>
 * Licensed under the GNU Affero Public License 3.0 (see LICENSE.txt)
 */

CRM.$(function($) {
  var initialSelectedLeges = $('#selected_leges').val().split(',');
  if (initialSelectedLeges.length == 1 && initialSelectedLeges[0] == '') {
    initialSelectedLeges = [];
  }
  $('#selected_leges').hide();
  var legList = $('<div/>', {
    id: 'legislator-list',
    class: 'crm-section',
  });
  var greeting = $('<div/>', {
    id: 'legislator-greeting',
    class: 'crm-section',
  });
  var legCheck = function() {
    var selectedLeges = [];
    maxLength = 0;
    greeting.children().hide();
    $('input[name="select-leges"]:checked').each(function() {
      var thisLegId = $(this).val();
      selectedLeges.push(thisLegId);
      greeting.children('[statelegemail-leg-id="' + thisLegId + '"]').show();
    });
    $('#selected_leges').val(selectedLeges.join(','));
  }
  var getRecips = function() {
    var firstRun = (initialSelectedLeges.length > 0);
    var zipval = $(zip).val();
    if (!$(stateProvince).val().length
      || !$(city).val().length
      || !$(address).val().length) {
      legList.html('');
      return;
    }

    var url = new URL(location.href);
    var surveyId = parseInt(url.searchParams.get("sid"));

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
          surveyId: surveyId,
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
          greeting.html('');
          $.each(data, function(index, value) {
            var legCheckBox = $('<input/>', {
              type: 'checkbox',
              class: 'crm-form-checkbox',
              value: value.leg_id,
              name: 'select-leges',
              id: 'select-leges-' + value.leg_id,
              change: legCheck,
            });
            if (firstRun) {
              var boxIndex = initialSelectedLeges.indexOf(value.leg_id);
              if (boxIndex >= 0) {
                legCheckBox.prop('checked', true);
                initialSelectedLeges.splice(boxIndex, 1);
              } else {
                legCheckBox.prop('checked', false);
              }
            } else {
              legCheckBox.prop('checked', false);
            }
            var legRow = $('<div/>', {
              class: 'statelegemail-paper-row',
              html: ' ',
            }).prepend(legCheckBox).append($('<label/>', {
              for: 'select-leges-' + value.leg_id,
              html: value.name,
            }));
            legContent.append(legRow);
            var greetingRow = $('<div/>', {
              html: value.greeting,
              'statelegemail-leg-id': value.leg_id,
            });
            if (legCheckBox.prop('checked')) {
              greetingRow.show();
            }
            else {
              greetingRow.hide();
            }
            if (data.length == 1) {
              legCheckBox.prop('checked', true);
              legCheckBox.attr("disabled", true);
            }
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
