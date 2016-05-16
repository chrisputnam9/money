<div class='row'>
    <div class='col-sm-12'>
        <h1>Money.cpi</h1>
        <hr/>
    </div>
</div>

<div class='row'>
    <div class='col-sm-12'>
        <h2>Add a transaction</h2>
    </div>
    <div class='col-sm-6'>
        <p>
            <a class="btn btn-primary btn-lg btn-block" href="/transaction/image" class="js-show" data-click="#image">Upload Image</a>
            <form action="/transaction/image" method="post" enctype="multipart/form-data" class="js-hide">
                <div class="form-group">
                    <label for="image">Use Camera or existing file</label>
                    <input type="file" accept="image/*" capture="camera" class="form-control js-change-submit" name="image" id="image" value="">
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Upload</button>
            </form>
        </p>
    </div>
    <div class='col-sm-6'>
        <p>
            <a class="btn btn-primary btn-lg btn-block" href="/transaction/form">Manual Entry</a>
        </p>
    </div>
</div>

<div class='row'>
    <div class='col-sm-12'>
        <h2>Transactions</h2>
    </div>
    <div class='col-sm-12'>
        <div class='table-responsive'>
            <table class='table table-striped table-hover table-condensed'>
                <thead>
                    <tr>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                {{#transactions}}
                    <tr>
                        <td align="right">{{amount_formatted}}</td>
                        <td align="right">{{date_occurred_formatted}}</td>
                        <td>{{account_from_value}}</td>
                        <td>{{account_to_value}}</td>
                        <td>{{category_value}}</td>
                        <td>
                            <a href='/transaction/form?id={{id}}' class='btn btn-primary'><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href='/transaction/delete?id={{id}}' class='btn btn-danger' data-confirm="Are you sure you want to delete this item?"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                {{/transactions}}
                </tbody>
            </table>
        </div>
    </div>
</div>
