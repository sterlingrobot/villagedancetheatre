jQuery(document).ready(function($) {
    'use strict';

    var childNum = 1;

    $('.add-child-form').on('click', function (e) {
      e.preventDefault();

      childNum++;

      $(this)
        .prev('fieldset')
        .clone(true, true)
        .find('legend')
        .text('Child ' + childNum)
        .end()
        .find('label')
        .attr('for', function () {
          return $(this).attr('for').replace(/\d+/, childNum);
        })
        .end()
        .find('input, select')
        .attr('name', function () {
          return $(this).attr('name').replace(/\d+/, childNum);
        })
        .attr('id', function () {
          return $(this).attr('id').replace(/\d+/, childNum);
        })
        .val('')
        .end()
        .insertBefore(this);
    });

    $('.remove-child-form').on('click', function (e) {
      e.preventDefault();

      childNum = 0;

      $(this).closest('fieldset').remove();

      $('fieldset').each(function (i) {
        childNum++;

        $(this)
          .find('legend')
          .text('Child ' + childNum)
          .end()
          .find('label')
          .attr('for', function () {
            return $(this).attr('for').replace(/\d+/, childNum);
          })
          .end()
          .find('input, select')
          .attr('name', function () {
            return $(this).attr('name').replace(/\d+/, childNum);
          })
          .attr('id', function () {
            return $(this).attr('id').replace(/\d+/, childNum);
          });
      });
    });

    $('.form-control').on('focus', function (e) {
      return $(this).closest('.form-group').removeClass('has-error');
    });

    $('[name="inquiry-form"]').on('submit', function (e) {
      var $form = $(this),
        $notice = $form.find('.alert');

      e.preventDefault();

      if (!validateForm($form)) {
        return;
      }

      $form.addClass('sending');

      $.post($form.attr('action'), $form.serialize()).then(function (response) {
        console.log(response);
        $notice
          .toggleClass('alert-success', response.status === 'success')
          .toggleClass('alert-warning', response.status === 'error');
        $('<h3></h3>').text(response.heading).appendTo($notice);
        $('<p></p>').html(response.body).appendTo($notice);
      });
    });

    $('[name="inquiry-form"]').on('click', '.close', function (e) {
      var $form = $(this).closest('.form'),
        $notice = $form.find('.alert'),
        shouldReset = $notice.hasClass('alert-success');

      e.preventDefault();

      $form.removeClass('sending');
      $notice.removeClass('alert-success alert-warning').find('h3, p').remove();

      return shouldReset && $form[0].reset() && grecaptcha.reset();
    });

    function validateForm($form) {
      var valid = true;

      $form.find('[required]').each(function () {
        var val = this.value.trim();
        if (!val.length) {
          valid = false;
          $(this).closest('.form-group').addClass('has-error');
        }
      });

      return valid;
    }
  });