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
            <label for="classification">Classification</label>
            <select class="form-control" name="classification" id="classification">
                <option>Payment</option>
                <option>Credit</option>
                <option>Income</option>
                <option>Transfer</option>
            </select>
        </div>
        <div class="form-group">
            <label for="account_from">Payer - Account/Party From</label>
            <select class="form-control" name="account_from" id="account_from">
                <option>Amazon Credit (8396)</option>
                <option>Lowe's Credit (0011)</option>
                <option>PNC - Checking (8485)</option>
                <option selected="selected">PNC - Credit (3385)</option>
                <option>PNC - Debit (9538)</option>
                <option>PNC - Reserve (8493)</option>
                <option>PNC - Savings (8506)</option>
                <option>USAA Credit (1087)</option>
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
