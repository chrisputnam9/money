<div class='col-sm-4 col-sm-offset-4'>  
    <h1>{{form_title}}</h1>
    {{#error}}
    <p class='alert bg-danger'>{{error}}</p>
    {{/error}}
    <form action="" method="post" enctype="multipart/form-data">
        {{#image}}
        <div class="form-group">
            <label>Image</label>
            <input type='hidden' name='image' value='{{image}}'/>
            <br/>
            <div class='window window--thumbnail img-thumbnail'>
                <img class='img-responsive img-stretch' src='/upload/transaction/{{image}}' alt='{{image}}'/>
            </div>
        </div>
        {{/image}}
        <div class="form-group">
            <label for="account_from">Payer - Account/Party From</label>
            <select class="form-control" name="account_from" id="account_from">
                <option value="nnnn">Amazon Credit (nnnn)</option>
                <option value="nnnn">Lowe's Credit (nnnn)</option>
                <option value="nnnn">PNC - Checking (nnnn)</option>
                <option value="nnnn" selected="selected">PNC - Credit (nnnn)</option>
                <option value="nnnn">PNC - Debit (nnnn)</option>
                <option value="nnnn">PNC - Reserve (nnnn)</option>
                <option value="nnnn">PNC - Savings (nnnn)</option>
                <option value="nnnn">USAA Credit (nnnn)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="account_to">Payee - Account/Party To</label>
            <input type="text" class="form-control" name="account_to" id="account_to" value="" placeholder="Who got the money?">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" name="category" id="category">
                <option value="">- Select One -</option>
                <option>Allowance - Chris</option>
                <option>Allowance - Katie</option>
                <option>Auto/Transport</option>
                <option>Bills/Utilities</option>
                <option>Cash/ATM</option>
                <option>Entertainment</option>
                <option>Gifts/Donations</option>
                <option>Groceries</option>
                <option>Health/Fitness</option>
                <option>Home Improvement</option>
                <option>Income</option>
                <option>Kids</option>
                <option>Life Insurance</option>
                <option>Mortgage</option>
                <option>Restaurants</option>
                <option>Shopping</option>
                <option>Taxes</option>
                <option>Tuition</option>
                <option>Windows</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ammount">Amount</label>
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" name="ammount" id="ammount" value="" placeholder="0.00">
            </div>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" name="date" id="date" value="{{date}}">
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea class="form-control" name="notes" id="notes" placeholder="Description/Comments"></textarea>
        </div>
        <button type="submit" class="btn bt-default">Save</button>
    </form>
</div>
