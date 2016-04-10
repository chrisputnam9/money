<div class='row'>
    <div class='col-sm-12'>
        <h1>Money.cpi</h1>
        <hr/>
    </div>
</div>

<div class='row'>
    <div class='col-sm-12'>
        <label>Add a transaction:</label>
    </div>
    <div class='col-sm-6'>
        <p>
            <a class="btn btn-primary btn-lg btn-block" href="/transaction/image">Image</a>
        </p>
    </div>
    <div class='col-sm-6'>
        <p>
            <a class="btn btn-primary btn-lg btn-block" href="/transaction/form">Manual</a>
        </p>
    </div>
</div>

<div class='row'>
    <div class='col-sm-12'>
        <label>Transactions</label>
    </div>
    <div class='col-sm-12'>
        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Image</th>
                    <th>Notes</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            {{#transactions}}
                <tr>
                    <td>{{id}}</td>
                    <td>{{amount}}</td>
                    <td>{{date_occurred}}</td>
                    <td>{{image}}</td>
                    <td>{{notes}}</td>
                    <td>{{account_from}}</td>
                    <td>{{acount_to}}</td>
                    <td>{{category}}</td>
                    <td>{{classification}}</td>
                    <td>{{status}}</td>
                    <td><a href='/transaction/form?id={{id}}' class='btn btn-primary btn-block'>Edit</a></td>
                </tr>
            {{/transactions}}
            </tbody>
        </table>
    </div>
</div>
