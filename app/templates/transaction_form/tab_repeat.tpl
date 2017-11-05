<div id='tab_repeat'>

    <div class='col-sm-12'>  
        <div class="form-group">
            <label for="repeat_type">Repeat Type</label>
            <select class="form-control js-toggle" name="repeat[type]" id="repeat_type" data-toggle_group=".repeat_type_options">
                <option value="">None</option>
                <option
                    value="month"
                    {{#repeat.type_month}}selected="selected"{{/repeat.type_month}}
                    data-toggle_target=".repeat_type_month_options,.repeat_type_all_options">Monthly - Nth day</option>
            </select>
        </div>
    </div>

    <div class='repeat_type_options repeat_type_month_options'>
        <div class='col-sm-6'>  
            <div class="form-group">
                <label for="month_count"><em>*</em> Every __ Months</label>
                <input class="form-control" name="repeat[month_count]" id="month_count" value="{{repeat.month_count}}"/>
            </div>
        </div>
        <div class='col-sm-6'>  
            <div class="form-group">
                <label for="month_day"><em>*</em> On day __ of the month</label>
                <input class="form-control" name="repeat[month_day]" id="month_day" value="{{repeat.month_day}}"/>
            </div>
        </div>
    </div>

    <div class='repeat_type_options repeat_type_all_options'>
        <div class='col-sm-12'>  
            <div class="form-group">
                <label for="repeat_date_end">Repeat Until <small>(Inclusive)</small></label>
                <input type="date" class="form-control" name="repeat[date_end]" id="repeat_date_end" value="{{repeat.date_end}}">
            </div>
        </div>
    </div>

</div>
