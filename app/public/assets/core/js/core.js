CPI = (function($) {

    var CPI = {};

    // Combo Box Functionality
    $.fn.combobox = function () {
        // Loop through all
        return this.each(function () {
            var $input = $(this),
                select = $input.data('combobox'),
                $select = $(select),
                $optgroups = $select.find('optgroup'),
                $options = $select.find('option'),
                input_id = $input.attr('id'),
                datalist_id = input_id + '__datalist',
                $datalist = $('<datalist>')
                    .attr('id', datalist_id);

            $optgroups.each( function () {
                var $group = $(this),
                    group = $group.attr('label');

                $group.find('option').each( function () {
                    var $option = $(this),
                        value = $option.val(),
                        text = $option.text(),
                        $list_option = $('<option>');
                    if (value == '') {
                        return true;
                    }
                    $list_option.text(group)
                        .val(text);
                    $datalist.append($list_option);
                });

            });

            $datalist.insertAfter($input);
            $input.attr('list', datalist_id);
            $input.prop('required', true);

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
        $('[data-combobox]').combobox();
        $('[data-confirm]').confirm();
        $('select[data-select]').autoselect();
        $('.js-file-upload').fileupload();

        $('.js-click').click();

        $('.js-show').show();
        $('.js-hide').hide();

        $('[data-click]').click(function (event) {
            var $this = $(this)
                target = $this.data('click');
                $target = $(target);

            if ($target.length > 0) {
                event.preventDefault();
                $target.click();
            }
        });
    });

    return CPI;

})(jQuery);
