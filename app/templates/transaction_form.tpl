<form action="" method="post" enctype="multipart/form-data">

    <input type='hidden' name='id' value='{{id}}'/>

    <div class='col-sm-12'>  
        <h1>{{form_title}}</h1>
        {{#error}}
        <p class='alert bg-danger'>{{error}}</p>
        {{/error}}
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="account_from"><em>*</em> Payer - Account/Party From</label>
            <select class="form-control" name="account_from" id="account_from" data-select="#category">
                <option value="">- Select One -</option>
            {{#account_from_options}}
                <optgroup label="{{group}}">
                {{#options}}
                    <option value="{{id}}" {{selected}} data-select="{{popular_category}}">{{title}}{{#account_number}} ({{account_number}}){{/account_number}}</option>
                {{/options}}
                </optgroup>
            {{/account_from_options}}
            </select>
            <input type="text" autocapitalize="words" class="form-control" data-combobox="#account_from" name="account_from_other" id="account_from_other" value="{{account_from_other}}" placeholder="Enter new account">
        </div>
    </div>
    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="account_to"><em>*</em> Payee - Account/Party To</label>
            <select class="form-control" name="account_to" id="account_to" data-select="#category">
                <option value="">- Select One -</option>
            {{#account_to_options}}
                <optgroup label="{{group}}">
                {{#options}}
                    <option value="{{id}}" {{selected}} data-select="{{popular_category}}">{{title}}{{#account_number}} ({{account_number}}){{/account_number}}</option>
                {{/options}}
                </optgroup>
            {{/account_to_options}}
            </select>
            <input type="text" autocapitalize="words" class="form-control" data-combobox="#account_to" name="account_to_other" id="account_to_other" value="{{account_to_other}}" placeholder="Enter new account">
        </div>
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="amount"><em>*</em> Amount</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number" inputmode="numeric" autocomplete="off" class="form-control" name="amount" id="amount" value="{{amount}}" placeholder="0.00" required>
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
{{^image}}
    </div>
    <div class='col-sm-6'>  
{{/image}}
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea class="form-control" name="notes" id="notes" placeholder="Description/Comments" rows="5">{{notes}}</textarea>
        </div>
{{^image}}
{{/image}}
    </div>
{{#image}}
    <div class='col-sm-6'>  
        <div class="form-group">
            <label>Image</label>
            <input type='hidden' name='image' value='{{image}}'/>
            <br/>
            <div class='window window--thumbnail img-thumbnail'>
                <img class='img-responsive img-stretch' src='/upload/transaction/{{image}}' alt='{{image}}'/>
            </div>
        </div>
    </div>
{{/image}}

{{#id}}
    <div class='col-sm-2'>  
{{/id}}
{{^id}}
    <div class='col-sm-3'>  
{{/id}}
        <p>
            <button type="submit" class="btn btn-primary btn-lg btn-block" name="submit" value="apply">Apply</button>
        </p>
    </div>
    <div class='col-sm-3'>  
        <p>
            <button type="submit" class="btn btn-success btn-lg btn-block" name="submit" value="save_new">Save &amp; New</button>
        </p>
    </div>
    <div class='col-sm-3'>  
        <p>
            <button type="submit" class="btn btn-info btn-lg btn-block" name="submit" value="save_close">Save &amp; Close</button>
        </p>
    </div>
{{#id}}
    <div class='col-sm-2'>  
{{/id}}
{{^id}}
    <div class='col-sm-3'>  
{{/id}}
        <p>
            <a href="/" class="btn btn-warning btn-lg btn-block">Cancel</a>
        </p>
    </div>
{{#id}}
    <div class='col-sm-2'>  
        <p>
            <a href="/transaction/delete?id={{id}}" class="btn btn-danger btn-lg btn-block" data-confirm="Are you sure you want to delete this item?">Delete</a>
        </p>
    </div>
{{/id}}

</form>
