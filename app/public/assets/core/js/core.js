CPI = (function($) {

    var CPI = {};

    // Auto select based on input change
    $.fn.autoselect = function () {
        return this.each(function () {
            var $input = $(this),
                targets = $input.data('select').split(','),
                $targets = [];

            $.each(targets, function(t, target) {
                var $target = $(target);
                if ($target.length > 0)
                {
                    $targets.push($target);
                }
            });

            if ($targets.length == 0) {
                return true;
            }


            $input.on('change combobox-change', function () {
                updateTargets();
            });

            var updateTargets = function () {
                $.each($targets, function(i, $target) {
                    var data_select = 'select';

                    if (i > 0) {
                        data_select+= (i + 1);
                    }

                    if ($target.val() != '' && ! $target.hasClass('js-autochanged')){
                        return;
                    }

                    if ($input.is('select')) {
                        var $selected = $input.find('option:selected'),
                            value = $selected.data(data_select);
                    }

                    if ($target.is('select')) {
                        $target.val(value)
                            .addClass('js-autochanged')
                            .trigger('autoselect-change');
                    }
                });
            };

            // Initiate from page load
            updateTargets();

        });
    };

    // Propagate element click to another element
    $.fn.clickPropagate = function () {
        return this.each(function () {
            $(this).click(function (event) {
                var $this = $(this),
                    target = $this.data('click'),
                    $target = $(target);

                if ($target.length > 0) {
                    event.preventDefault();
                    $target.click();
                }
            });
        });
    };

    // Toggle one element (show/hide) when another is clicked
    $.fn.clickToggle = function () {
        return this.each(function () {
            $(this).click(function (event) {
                var $this = $(this),
                    target = $this.data('clicktoggle'),
                    $target = $(target);

                if ($target.length > 0) {
                    event.preventDefault();
                    $target.toggleClass('hidden');
                }
            });
        });
    };

    // Focus one element when another is clicked
    $.fn.clickFocus = function () {
        return this.each(function () {
            $(this).click(function (event) {
                var $this = $(this),
                    target = $this.data('clickfocus'),
                    $target = $(target);

                if ($target.length > 0) {
                    event.preventDefault();
                    $target.focus();
                }
            });
        });
    };

    // Combo Box Functionality
    $.fn.combobox = function () {
        return this.each(function () {
            var $input = $(this),
                initialized = false,
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
            $optgroups.each( function () {
                var $group = $(this),
                    group = $group.attr('label');

                $group.find('option').each( function () {
                    var $option = $(this),
                        value = $option.val(),
                        text = $option.text(),
                        search = (group + ' ' + text).toLowerCase(),
                        $link = $('<a>')
                            .attr('href', '#' + value)
                            .attr('data-search', search)
                            .text(text)
                            .append($('<span>').text(group)),
                        $option = $('<li>')
                            .append($link);

                    if (value == '') {
                        return true;
                    }

                    $dropdown_menu.append($option);

                    // fill input when link is clicked
                    $link.click(function(event) {
                        event.preventDefault();
                        $input.val(text).trigger('input');
                        $dropdown.removeClass('open');
                        $button.removeClass('open');
                    });

                });

                first = false;

            });

            // I feel like bootstrap should already work this way... boo!
            $dropdown.on('show.bs.dropdown hide.bs.dropdown', function() {
                $dropdown.toggleClass('open');
                /*
                if ($dropdown.hasClass('open')) {
                    $dropdown.addClass('open');
                    $button.addClass('open');
                } else {
                    $dropdown.removeClass('open');
                    $button.removeClass('open');
                }
                */
            });

            $select.addClass('hidden');

            // When input changes, select value, style
            $input.on('input', function () {
                var value = $input.val(),
                    selected = "",
                    groups = [],
                    $dropdown_options = $dropdown_menu.find('li>a');

                if (value == "" || !initialized) {
                    $input.css({ 'font-weight':'', 'font-style':'' });
                    $dropdown_options.removeClass('hidden');
                } else {
                    var $filtered = $dropdown_options.filter('[data-search*="'+value.toLowerCase()+'"]');
                    $input.css({ 'font-weight':'', 'font-style':'italic' });
                    if ($filtered.length > 0) {
                        $dropdown.addClass('open');
                        $button.addClass('open');

                        $dropdown_options.addClass('hidden')
                        $filtered.removeClass('hidden');
                    } else {
                        $dropdown.removeClass('open');
                        $button.removeClass('open');

                        $dropdown_options.removeClass('hidden')
                    }
                }

                $options.each(function () {
                    var $option = $(this);
                    if ($option.text() == value) {
                        $input.css({ 'font-weight':'bold', 'font-style':'' });
                        selected = $option.val();

                        $dropdown.removeClass('open');
                        $button.removeClass('open');

                        $dropdown_options.removeClass('hidden')
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

            $select.on('change click autoselect-change', updateInput);

            initialized = true;

        });
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
                $progress_container.removeClass('hidden');

                xhr.send(fd);
            });
        
            // File selected, upload right away
            $input.change(function (event) {
                $form.submit();
            });

            var uploadProgress = function (evt) {
                var percent;
                if (evt.lengthComputable) {
                    percent = Math.round(evt.loaded * 100 / evt.total) + '%';
                } else {
                    percent = '50%';
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
                $progress_container.addClass('hidden');
                $input.val('');
            }

            var uploadCanceled = function (evt) {
                alert("The upload has been canceled by the user or the browser dropped the connection. Please try again.");
                $progress_container.addClass('hidden');
                $input.val('');
            }

        });
    };

    // Tab functionality
    $.fn.tabify = function () {
            var tabifyTarget = function ($link) {
                target = $link.attr('href')
                return $(target);
            };
        return this.each(function () {
            var $this = $(this),
                $lis = $this.find('li');
            $lis.each(function () {
                var $li = $(this),
                    $link = $li.find('a'),
                    $target = tabifyTarget($link);

                if ( ! $li.hasClass('active')) {
                    $target.addClass('hidden');
                }

                $link.on('click', function (event) {
                    event.preventDefault();
                    var $previous_li = $lis.filter('.active'),
                        $previous_link = $previous_li.find('a');
                    $previous_li.removeClass('active');
                    tabifyTarget($previous_link).addClass('hidden');
                    $li.addClass('active');
                    $target.removeClass('hidden');
                })
                    
            });
        });
    };

    // Functionality to toggle an element's visibility
    //  based on a click or select change
    $.fn.togglePropagate = function () {
        return this.each(function () {
            var $this = $(this);

            if ($this.is('select')) {

                // All targets have this class
                var group = $this.data('toggle_group'),
                    $group = $(group);
                $group.addClass('hidden');

                function update () {
                    var $option = $this.find(':selected'),
                        target = $option.data('toggle_target'),
                        $target = $(target);
                    $group.addClass('hidden');
                    $target.removeClass('hidden');
                }

                update();
                $this.on('change', update);

            } else {

                var target = $this.attr('href'),
                $target = $(target);
                if ($target.length > 0) {
                    $target.addClass('hidden');
                    $this.click(function(event) {
                        event.preventDefault();
                        $target.toggleClass('hidden');
                    });
                }

            }// end non-select element
        });
    };

    // On Load
    $(function () {

        // Bootstrap functionality

        // Custom Functionality
        $('.js-file-upload').fileupload();
        $('.js-tabify').tabify();
        $('.js-toggle').togglePropagate();
        $('[data-click]').clickPropagate();
        $('[data-clicktoggle]').clickToggle();
        $('[data-clickfocus]').clickFocus();
        $('[data-combobox]').combobox();
        $('[data-confirm]').confirm();
        $('select[data-select]').autoselect();

        // Simple stuff:
        $('.js-click').click();
        $('.js-hide')
			.addClass('hidden')
			.removeClass('js-hide');
        $('.js-show').removeClass('js-show');

    });

    return CPI;

})(jQuery);
