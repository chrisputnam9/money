CPI = (function($) {

    var CPI = {};

    // Auto select based on input change
    $.fn.autoselect = function () {
        return this.each(function () {
            var $input = $(this),
                target = $input.data('select')
                $target = $(target);

            if ($target.length == 0) {
                return true;
            }

            $input.on('change combobox-change', function() {
                if ($target.val() != ''){
                    return;
                }

                if ($input.is('select')) {
                    var $selected = $input.find('option:selected'),
                        text = $selected.data('select');
                }

                console.log($target);
                console.log('text: ' + text);

                if ($target.is('select')) {
                    $target.find('option').filter(function () {
                        return this.text == text;
                    }).prop('selected', true);
                }
            });

        });
    };

    // Propagate element click to another element
    $.fn.clickPropagate = function () {
        return this.each(function () {
            $(this).click(function (event) {
                var $this = $(this)
                    target = $this.data('click');
                    $target = $(target);

                if ($target.length > 0) {
                    event.preventDefault();
                    $target.click();
                }
            });
        });
    };

    // Combo Box Functionality
    $.fn.combobox = function () {
        return this.each(function () {
            var $input = $(this),
                select = $input.data('combobox'),
                $select = $(select),
                $optgroups = $select.find('optgroup'),
                $options = $select.find('option'),
                input_id = $input.attr('id'),
                dropdown_id = input_id + '__dropdown',
                $dropdown = $('<div class="dropdown">'),
                $input_group = $('<div class="input-group">'),
                $button_span = $('<span class="input-group-btn">'),
                $button = $('<button class="js-dropdown-toggle btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">')
                    .attr('id', dropdown_id),
                $button_caret = $('<span class="caret">'),
                $dropdown_menu = $('<ul class="dropdown-menu dropdown-menu-right full-width" >')
                    .attr('aria-labelledby', dropdown_id);

            // Throw it in the DOM!
            $dropdown.insertAfter($input);
                $dropdown.append($input_group, $dropdown_menu);
                $input.prop('required', true).detach();
                $input_group.append($input, $button_span);
                    $button_span.append($button);
                    $button.append($button_caret);

            // Add the options
            var $divider = $('<li role="separator" class="divider">');
            $optgroups.each( function () {
                var $group = $(this),
                    group = $group.attr('label');
                
                $dropdown_menu.append(
                    $divider.clone(),
                    $('<li class="dropdown-header">').text(group),
                    $divider.clone()
                );

                $group.find('option').each( function () {
                    var $option = $(this),
                        value = $option.val(),
                        text = $option.text(),
                        $link = $('<a>')
                            .attr('href', '#' + value)
                            .text(text),
                        $option = $('<li>')
                            .append($link);

                    if (value == '') {
                        return true;
                    }

                    $dropdown_menu.append($option);
                });

                first = false;

            });

            // I feel like bootstrap should already work this way... boo!
            $button.click(function() {$dropdown.toggleClass('open');});

            $select.hide();

            // When input changes, select value, style
            $input.on('input', function () {
                var value = $input.val(),
                    selected = "";

                if (value == "") {
                    $input.css({ 'font-weight':'', 'font-style':'' });
                } else {
                    $input.css({ 'font-weight':'', 'font-style':'italic' });
                }
                
                $options.each(function () {
                    var $option = $(this);
                    if ($option.text() == value) {
                        $input.css({ 'font-weight':'bold', 'font-style':'' });
                        selected = $option.val();
                    }
                });

                $select.val(selected).trigger('combobox-change');
            });

            // Update input based on select value
            var updateInput = function () {
                var selected = $select.val(),
                    $option = $select.find('option[value="'+selected+'"]');
                if (selected == "") {
                    $input.val("");
                } else {
                    $input.val($option.text());
                }
                $input.trigger('input');
            }

            updateInput();

            $select.on('change click', updateInput);

        })
    };

    // Confirm click functionality
    $.fn.confirm = function () {
        return this.each(function () {
            var $clickable = $(this),
                message = $clickable.data('confirm');
            
            $clickable.click(function(event) {
                result = window.confirm(message);
                if (!result) {
                    event.preventDefault();
                }
            });
        })
    }

    // Functionality for file upload form
    $.fn.fileupload = function () {
        return this.each(function () {
            var $form = $(this),
                $input = $form.find('input[type="file"]'),
                progress_container = $form.data('progress'),
                $progress_container = $(progress_container),
                $progress_bar = $progress_container.find('.progress-bar'),
                $percents = $progress_container.find('.percent');

            if ($input.length != 1) {
                return true;
            }

            // On submit, use ajax instead
            $form.submit(function (event) {
                var fd = new FormData(),
                    xhr = new XMLHttpRequest();

                event.preventDefault();

                fd.append($input.attr('name'), $input.get()[0].files[0]);
                fd.append('ajax', true);

                xhr.upload.addEventListener("progress", uploadProgress, false);
                xhr.addEventListener("load", uploadComplete, false);
                xhr.addEventListener("error", uploadFailed, false);
                xhr.addEventListener("abort", uploadCanceled, false);
                xhr.open("POST", "/transaction/image");

                // Show progress container
                $progress_container.show();

                xhr.send(fd);
            });
        
            // File selected, upload right away
            $input.change(function (event) {
                $form.submit();
            });

            var uploadProgress = function (evt) {
                if (evt.lengthComputable) {
                    var percent = Math.round(evt.loaded * 100 / evt.total) + '%';
                } else {
                    var percent = '50%';
                }
                $progress_bar.css('width', percent);
                $percents.html(percent);
            }

            var uploadComplete = function (evt) {
                var responseJson = JSON.parse(evt.target.responseText);

                $progress_bar.css('width', '100%');
                $percents.html('100%');
                $input.val('');

                if ('location' in responseJson) {
                    document.location = responseJson.location;
                } else {
                    alert("There may have been an error uploading the file. Please try again and report if this happens a second time.");
                }
            }

            var uploadFailed = function (evt) {
                alert("There was an error uploading the file. Please try again and report if this happens a second time.");
                $progress_container.hide();
                $input.val('');
            }

            var uploadCanceled = function (evt) {
                alert("The upload has been canceled by the user or the browser dropped the connection. Please try again.");
                $progress_container.hide();
                $input.val('');
            }

        });
    };

    // On Load
    $(function () {

        // Bootstrap functionality

        // Custom Functionality
        $('.js-file-upload').fileupload();
        $('[data-click]').clickPropagate();
        $('[data-combobox]').combobox();
        $('[data-confirm]').confirm();
        $('select[data-select]').autoselect();

        // Simple stuff:
        $('.js-click').click();
        $('.js-hide').hide();
        $('.js-show').show();

    });

    return CPI;

})(jQuery);
