<div class='row'>
    <br/>
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
