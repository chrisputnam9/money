<div id='tab_general'>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="account_to"><em>*</em> Payee - Account/Party To</label>
            <select class="form-control" name="account_to" id="account_to" data-select="#category,#account_from">
                <option value="">- Select One -</option>
            {{#account_to_options}}
                <optgroup label="{{group}}">
                {{#options}}
                    <option value="{{id}}" {{selected}} data-select="{{popular_category}}" data-select2="{{popular_account_from}}" data-classification="{{group}}">{{title}}{{#account_number}} ({{account_number}}){{/account_number}}</option>
                {{/options}}
                </optgroup>
            {{/account_to_options}}
            </select>
            <input type="text" autocapitalize="words" class="form-control" data-combobox="#account_to" name="account_to_other" id="account_to_other" value="{{account_to_other}}" placeholder="Enter new account" autocomplete="off">
        </div>
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="account_from"><em>*</em> Payer - Account/Party From</label>
            <select class="form-control" name="account_from" id="account_from">
                <option value="">- Select One -</option>
            {{#account_from_options}}
                <optgroup label="{{group}}">
                {{#options}}
                    <option value="{{id}}" {{selected}} data-classification="{{group}}">{{title}}{{#account_number}} ({{account_number}}){{/account_number}}</option>
                {{/options}}
                </optgroup>
            {{/account_from_options}}
            </select>

            <input type="text" autocapitalize="words" class="form-control" data-combobox="#account_from" name="account_from_other" id="account_from_other" value="{{account_from_other}}" placeholder="Enter new account" autocomplete="off">

        </div>
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="amount"><em>*</em> Amount</label>
            <div class="input-group input-prepend">
                <span class="input-group-addon">$</span>
                <select class="form-control" name="amount" id="amount_select">
                    <optgroup label="Detected amount for this transaction">
                    {{#amount_options}}
                        <option value="{{amount}}" {{selected}} ">{{amount}}</option>
                    {{/amount_options}}
                    </optgroup>
                </select>
                <input type="number" step="0.01" min="0.00" inputmode="numeric" autocomplete="off" class="form-control" data-combobox="#amount_select" name="amount_other" id="amount" value="" placeholder="0.00" required autocomplete="off">
            </div>
        </div>
    </div>
    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="category"><em>*</em> Category</label>
            <select class="form-control" name="category" id="category" required>
                <option value="">- Select One -</option>
            {{#category_options}}
                <option value="{{id}}" {{selected}}>{{title}}</option>
            {{/category_options}}
            </select>
        </div>
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="date_occurred"><em>*</em> Date</label>
            <div class="input-group">
                <input type="date" class="form-control js-date-warn" data-warn-output=".date_occurred_warning" name="date_occurred" id="date_occurred" value="{{date_occurred}}" required>
                <span class="input-group-btn">
					<button title='Today' class="js-setvalue btn btn-default" data-target="#date_occurred" data-value="{{today_datestamp}}" type="button">
                        <b>Tdy</b>
					</button>
					<button title='Yesterday' class="js-setvalue btn btn-default" data-target="#date_occurred" data-value="{{yesterday_datestamp}}" type="button">
                        <b>Yst</b>
					</button>
					<button class="js-clear btn btn-default" data-target="#date_occurred" type="button">
                        <span class="glyphicon glyphicon-remove"></span>
					</button>
                </span>
			</div>
            <strong>&nbsp;<small class="date_occurred_warning text-danger"></small></strong>
        </div>
        <div class="form-group">
            <label for="classification"><em>*</em> Classification</label>
            <select class="form-control" name="classification" id="classification">
            {{#classification_options}}
                <option value="{{id}}" {{selected}}>{{title}}</option>
            {{/classification_options}}
            </select>
        </div>
    </div>
    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea class="form-control" name="notes" id="notes" placeholder="Description/Comments" rows="5">{{notes}}</textarea>
        </div>
    </div>

</div>
