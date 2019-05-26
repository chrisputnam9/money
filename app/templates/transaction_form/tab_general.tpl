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
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number" step="0.01" min="0.00" inputmode="numeric" autocomplete="off" class="form-control" name="amount" id="amount" value="{{amount}}" placeholder="0.00" required>
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
            <input type="date" class="form-control" name="date_occurred" id="date_occurred" value="{{date_occurred}}" required>
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
