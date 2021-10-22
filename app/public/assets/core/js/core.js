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

                    // Only change blank items, and items whose values were set by this functionality
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
            var instance = this;

            instance.initialized = false;
            instance.open = false;

                // Existing elements
            var $input = $(this),
                select = $input.data('combobox'),
                $select = $(select),
                $optgroups = $select.find('optgroup'),
                $options = $select.find('option'),
                input_id = $input.attr('id'),

                // New elements
                dropdown_id = input_id + '__dropdown',
                $dropdown = $('<div class="dropdown">'),
                $dropdown_menu = $('<ul class="dropdown-menu dropdown-menu-right full-width" >')
                    .attr('aria-labelledby', dropdown_id);

                $input_group = $('<div class="input-group">'),
                $button_span = $('<span class="input-group-btn">'),

                $button = $('<button class="js-dropdown-toggle btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">')
                    .attr('id', dropdown_id),
                $button_caret = $('<span class="glyphicon glyphicon-menu-down">'),

                $clear_button = $('<button class="js-clear btn btn-default" type="button">')
                    .attr('data-target', '#' + input_id),
                $clear_button_icon = $('<span class="glyphicon glyphicon-remove">'),

            // Throw it in the DOM!
            $dropdown.insertAfter($input);
                $dropdown.append($input_group, $dropdown_menu);
                $input.prop('required', true).detach();
                $input_group.append($input, $button_span);
                    $button_span.append($button);
                        $button.append($button_caret);
                    $button_span.append($clear_button);
                        $clear_button.append($clear_button_icon);

            // Hide the original select input
            $select.addClass('hidden');

            // Add the options by group
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
                        $input.val(text);
                        $input.trigger('input-from-combobox');
                    });

                });

                first = false;

            });

            // Set up functions
            instance.toggleDropdown = function (open) {
                if (open === true) {
                    instance.open = true;
                } else if ( open === false ) {
                    instance.open = false;
                }
                else {
                    instance.open = ( ! instance.open );
                }

                $dropdown.toggleClass('open', instance.open);
            }
            instance.showDropdown = function () {
                instance.toggleDropdown(true);
            }
            instance.hideDropdown = function () {
                instance.toggleDropdown(false);
            }

            instance.filterOptions = function (event) {
                var input_value = $input.val(),
                    selected = "",
                    groups = [],
                    $dropdown_options = $dropdown_menu.find('li>a')
                    $filtered_options = $();

                if (input_value == "" || ! this.initialized) {
                    $input.css({ 'font-weight':'', 'font-style':'' });
                    $dropdown_options.removeClass('hidden');
                } else {
                    $filtered_options = $dropdown_options.filter('[data-search*="'+input_value.toLowerCase()+'"]');
                    $input.css({ 'font-weight':'', 'font-style':'italic' });

                    // For filtering triggered by human input, update dropdwon:
                    if (event.type == 'keyup') {
                        if ($filtered_options.length > 0) {
                            $dropdown_options.addClass('hidden');
                            $filtered_options.removeClass('hidden');
                            $dropdown.trigger('show.bs.dropdown');
                        } else {
                            /* Show all and hide dropdown - no matches */
                            $dropdown_options.removeClass('hidden');
                            $dropdown.trigger('hide.bs.dropdown');
                        }
                    }
                }

                $options.each(function () {
                    var $option = $(this);
                    if ($option.text() == input_value) {
                        $input.css({ 'font-weight':'bold', 'font-style':'' });
                        selected = $option.val();

                        // Exact match, no need to show dropdown
                        $dropdown.trigger('hide.bs.dropdown');
                        $dropdown_options.removeClass('hidden');

                        // Quit once we have a match
                        return false;
                    }
                });

                $select.val(selected).trigger('combobox-change');
            }

            // Listen for bootstrap events
            $dropdown.on('show.bs.dropdown', instance.showDropdown);
            $dropdown.on('hide.bs.dropdown', instance.hideDropdown);

            // When input changes, select value, style
            $input.on('keyup input input-from-combobox input-clear', function (event) {
                instance.filterOptions(event);
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
            instance.filterOptions();

            $select.on('change click autoselect-change', updateInput);

            this.initialized = true;

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
                $progress_bar.removeClass('progress-bar-danger');

                xhr.send(fd);
            });
        
            // File selected, upload right away
            $input.change(function (event) {
                $form.submit();
            });

            var uploadProgress = function (evt) {
                var percent;

                if (evt.lengthComputable) {
                    percent = Math.round(evt.loaded * 95 / evt.total);
                } else {
                    percent = 50;
                }

                $progress_bar.css('width', percent + '%');
                if (percent == 95) {
                    $percents.html('Upload complete. Processing image...');
                } else {
                    $percents.html(percent + '%');
                }

            }

            var uploadComplete = function (evt) {
                var responseJson = JSON.parse(evt.target.responseText);
                $input.val('');

                $progress_bar.css('width', '95%');

                if ('location' in responseJson) {
                    $progress_bar.addClass('progress-bar-success');

                    document.location = responseJson.location;
                } else {
                    var error="There may have been an error uploading the file. Please try again and report if this happens a second time.";
                    if ('error' in responseJson) {
                        error=responseJson.error;
                    }

                    $progress_bar.removeClass('progress-bar-success');
                    $progress_bar.addClass('progress-bar-danger');
                    $percents.html('ERROR: ' + error);
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

    // Auto Classify Transaction Based on Account Classifications
    $.fn.autoClassify = function () {
        var PAYMENT = 1,
            CREDIT = 2,
            INCOME = 3,
            TRANSFER = 4,

            $from = $('#account_from'),
            $to = $('#account_to'),
            $classification = $('#classification');

        return this.each(function () {
            $(this).on('change combobox-change', function() {
                var from_class = $from.find('option:selected').data('classification'),
                    to_class = $to.find('option:selected').data('classification'),
                    from_internal = (from_class == 'Bank Account' || from_class == 'Credit Card'),
                    to_internal = (to_class == 'Bank Account' || to_class == 'Credit Card'),
                    classification = PAYMENT; // Payment

                // If to external, we'll just keep as Payment
                if (to_internal) {
                    if (from_internal) {
                        classification = TRANSFER;
                    } else if (to_class == 'Bank Account') {
                        classification = INCOME;
                    } else if (to_class == 'Credit Card') {
                        classification = CREDIT;
                    }
                }

                $classification.val(classification);

            });
        });
    };

	// Check date input and warn if it is more than 10 days in past or future
	$.fn.checkInputDate = function () {
		const $input = $(this);
		const value = $input.val();
		const target = $input.data('warn-output');
		const $warning_element = $(target);
		let warning = "";
		if (value) {
			const date_selected = new Date(value);
			const date_now = new Date();
			const days = ( (date_selected - date_now) / 86400000 ); // 1 day in milliseconds
			if (days > 0) {
				warning = 'This is a future date';
			} else if (days < -10) {
				warning = 'This is more than 10 days ago';
			}
		}
		$input.closest('.form-group').toggleClass('bg-warning', (warning !== ""));
		$warning_element.text(warning);
	};

    // On Load
    $(function () {

		const $transaction_id = $('#transaction_id');
		const transaction_id = $transaction_id.length > 0 ? $transaction_id.val() : false;
		const is_transaction = (transaction_id !== false);
		const is_new_transaction = (transaction_id === "");

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

        $('#account_from,#account_to').autoClassify();

        // Simple stuff:
        $('.js-click').click();
        $('.js-hide')
			.addClass('hidden')
			.removeClass('js-hide');
        $('.js-show').removeClass('js-show');

        // Clear an input target from a click
        $('.js-clear').on('click', function () {
            const $btn = $(this);
            const target = $btn.data('target');
            const $targets = $(target);
            $targets.each(function () {
                const $target = $(this);
                if ($target.is('input')) {
                    $target.val("")
                        .trigger("input-clear");
                    $target.trigger('change');
                }
            })
        });

        // Set an input value from a click
        $('.js-setvalue').on('click', function () {
            const $btn = $(this);
            const target = $btn.data('target');
            const value = $btn.data('value');

            $(target).each(function () {
                const $target = $(this);
                if ($target.is('input')) {
                    $target.val(value);
                    $target.trigger('change');
                }
            })
        });

        // Swap values on click
        $('.js-swap-values').on('click', function () {
            const $btn = $(this);
            const target1 = $btn.data('target1');
            const target2 = $btn.data('target2');
			const $target1 = $(target1);
			const $target2 = $(target2);

			if ($target1.length !== 1) {
				throw new Error('Unexpected number of targets ('+$target1.length+') for target1: ' + target1);
			}

			if ($target2.length !== 1) {
				throw new Error('Unexpected number of targets ('+$target2.length+') for target2: ' + target2);
			}

			let value1, value2;

			// Only use case so far
			if ($target1.is('select') && $target2.is('select')) {
				value1 = $target1.val();
				value2 = $target2.val();

				$target1.val(value2);
				$target1.trigger('change');

				$target2.val(value1);
				$target2.trigger('change');
			}
        });

        // Check date input and warn if it is more than 10 days in past or future
		const $js_date_warn = $('.js-date-warn');
		if ($js_date_warn.length > 0) {
			$js_date_warn.on('change', function () {
				$(this).checkInputDate();
			});

			// Check date on load - IF this is a brand new transaction (eg. date has been read in from text, photo, etc)
			if (is_new_transaction) {
				$js_date_warn.checkInputDate();
			}
		}

    });

    return CPI;

})(jQuery);
