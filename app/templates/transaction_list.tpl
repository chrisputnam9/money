<div class='row'>
        {{#category}}
            <h2 class='text-center'>{{category}}</h2>
            <p class='text-center'><a href='{{show_all_url}}'>Show All</a></p>
            <br>
        {{/category}}
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
                        <td><a href='{{category_url}}'>{{category_value}}</a></td>
                        <td>
                            <a href='/transaction/delete?id={{id}}' class='btn btn-danger' data-confirm="Are you sure you want to delete this item? {{#is_repeat_parent}}All recurrances will be deleted as well.{{/is_repeat_parent}}{{#is_repeat_child}}This recurrance may be re-created automatically based on the master transaction.{{/is_repeat_child}}"><span class="glyphicon glyphicon-trash"></span></a>
                            <a href='/transaction/form?id={{id}}' class='btn btn-primary'><span class="glyphicon glyphicon-pencil"></span></a>
                            {{#is_repeat_parent}}
                                <span title="Recurring Transaction"><a href='#' class='btn btn-disabled disabled'><span class="glyphicon glyphicon-repeat"></span></a></span>
                            {{/is_repeat_parent}}
                            {{#is_repeat_child}}
                                <a href='/transaction/form?id={{repeat_parent_id}}' class='btn btn-info'><span class="glyphicon glyphicon-repeat"></span></a>
                            {{/is_repeat_child}}
                        </td>
                    </tr>
                {{/transactions}}
                </tbody>
            </table>
        </div>
    </div>
</div>
