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

                $select.val(selected);
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

    $(function () {
        $('[data-combobox]').combobox();
    });

    return CPI;

})(jQuery);
