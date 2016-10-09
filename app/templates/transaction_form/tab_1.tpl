<div id='tab_1'>

    <div class='col-sm-12'>  
        <div class="form-group">
            <label for="repeat_type">Repeat Type</label>
            <select class="form-control js-toggle" name="repeat_type" id="repeat_type" data-toggle_group=".repeat_type_options">
                <option value="">None</option>
                <option value="month" data-toggle_target=".repeat_type_month_options">Monthly - Nth day</option>
            </select>
        </div>
    </div>

    <div class='repeat_type_options repeat_type_month_options'>
        <div class='col-sm-6'>  
            <div class="form-group">
                <label for="month_count"><em>*</em> Every __ Months</label>
                <input class="form-control" name="month_count" id="month_count" value=""/>
            </div>
        </div>
        <div class='col-sm-6'>  
            <div class="form-group">
                <label for="month_day"><em>*</em> On day __ of the month</label>
                <input class="form-control" name="month_day" id="month_day" value=""/>
            </div>
        </div>
    </div>

</div>
