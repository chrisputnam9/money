<form action="" method="post" enctype="multipart/form-data">

    <div class='col-sm-12'>  
        <h1>{{form_title}}</h1>
        {{#error}}
        <p class='alert bg-danger'>{{error}}</p>
        {{/error}}
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="amount"><em>*</em> Amount</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" name="amount" id="amount" value="{{amount}}" placeholder="0.00">
            </div>
        </div>
    </div>
    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="category"><em>*</em> Category</label>
            <select class="form-control" name="category" id="category">
                <option value="">- Select One -</option>
            {{#category_options}}
                <option value="{{id}}">{{title}}</option>
            {{/category_options}}
            </select>
        </div>
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="account_from"><em>*</em> Payer - Account/Party From</label>
            <select class="form-control" name="account_from" id="account_from">
                <option value="">- Select One -</option>
            {{#account_options}}
                <optgroup label="{{group}}">
                {{#options}}
                    <option value="{{id}}">{{title}}{{#account_number}} ({{account_number}}){{/account_number}}</option>
                {{/options}}
                </optgroup>
            {{/account_options}}
            </select>
            <input type="text" class="form-control" name="account_from_other" id="account_from_other" value="" placeholder="Enter new account">
        </div>
    </div>
    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="account_to"><em>*</em> Payee - Account/Party To</label>
            <select class="form-control" name="account_to" id="account_to">
                <option value="">- Select One -</option>
            {{#account_options}}
                <optgroup label="{{group}}">
                {{#options}}
                    <option value="{{id}}">{{title}}{{#account_number}} ({{account_number}}){{/account_number}}</option>
                {{/options}}
                </optgroup>
            {{/account_options}}
            </select>
            <input type="text" class="form-control" name="account_to_other" id="account_to_other" value="" placeholder="Enter new account">
        </div>
    </div>

    <div class='col-sm-6'>  
        <div class="form-group">
            <label for="date"><em>*</em> Date</label>
            <input type="date" class="form-control" name="date" id="date" value="{{date}}">
        </div>
        <div class="form-group">
            <label for="classification"><em>*</em> Classification</label>
            <select class="form-control" name="classification" id="classification">
            {{#classification_options}}
                <option value="{{id}}">{{title}}</option>
            {{/classification_options}}
            </select>
        </div>
{{^image}}
    </div>
    <div class='col-sm-6'>  
{{/image}}
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea class="form-control" name="notes" id="notes" placeholder="Description/Comments" rows="5"></textarea>
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
    <div class='col-sm-12'>  
        <button type="submit" class="btn btn-block btn-large btn-primary">Save</button>
    </div>

</form>
