<div class='row'>
        {{#category}}
            <h2 class='text-center'>{{category}}</h2>
            <p class='text-center'>
				<a href='{{show_all_url}}'>Show All</a>
				| <a href='{{csv_url}}'>Download CSV</a>
			</p>
            <br>
        {{/category}}
    <div class='col-sm-12'>

        <div class="panel panel-default panel-tight hidden-xs">
            <div class="panel-body panel-heading">
                <div class="col-xs-8 col-sm-9 col-md-10 pad-top-md col-bare">
                    <div class="col-xs-6 col-sm-3 col-md-1 col-bare">Date</div>
                    <div class="col-xs-5 col-sm-9 col-md-1 col-bare">
                        <div class="visible-sm-block">Amount</div>
                        <div class="hidden-sm text-right">Amount</div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-md-offset-1 col-bare">Category</div>
                    <div class="hidden-xs col-sm-9 col-md-6 col-bare">
                        Account From / To
                    </div>
                </div>
                <div class="col-xs-4 col-sm-3 col-md-2 col-bare text-right">Actions</div>
                <div class="col-xs-12 col-bare visible-xs-block">
                    Account From / To
                </div>
            </div>
        </div>

    {{#transactions}}
        <div class="panel panel-default panel-tight">
            <div class="panel-body">

                <div class="col-xs-8 col-sm-9 col-md-10 pad-top-md col-bare">
                    <div class="col-xs-6 col-sm-3 col-md-1 col-bare">
                        {{date_occurred_formatted}}
                    </div>
                    <div class="col-xs-5 col-sm-9 col-md-1 col-bare">
                        <div class="visible-sm-block classification-{{classification_value}}"><b>{{amount_formatted}}</b></div>
                        <div class="hidden-sm hidden-sm text-right classification-{{classification_value}}"><b>{{amount_formatted}}</b></div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-md-offset-1 col-bare">
                        <a href='{{category_url}}'>{{category_value}}</a>
                    </div>
                    <div class="hidden-xs col-sm-9 col-md-6 col-bare">
                        {{account_from_value}}
                        <span class='glyphicon glyphicon-triangle-right'></span>
                        {{account_to_value}}
                    </div>
                </div>

                <div class="col-xs-4 col-sm-3 col-md-2 col-bare text-right">
                    {{#is_repeat_parent}}
                        <span title="Recurring Transaction"><a href='#' class='btn btn-disabled disabled'><span class="glyphicon glyphicon-repeat"></span></a></span>
                    {{/is_repeat_parent}}
                    {{#is_repeat_child}}
                        <span title="Recurrance - Edit Parent"><a href='/transaction/form?id={{repeat_parent_id}}' class='btn btn-sm btn-info'><span class="glyphicon glyphicon-repeat"></span></a></span>
                    {{/is_repeat_child}}

                    <span title="Edit"><a href='/transaction/form?id={{id}}' class='btn btn-sm btn-primary'><span class="glyphicon glyphicon-pencil"></span></a></span>
                    <span title="Delete"><a href='/transaction/delete?id={{id}}' class='btn btn-sm btn-danger' data-confirm="Are you sure you want to delete this item? {{#is_repeat_parent}}All recurrances will be deleted as well.{{/is_repeat_parent}}{{#is_repeat_child}}This recurrance may be re-created automatically based on the master transaction.{{/is_repeat_child}}"><span class="glyphicon glyphicon-trash"></span></a></span>

                    <span class='glyphicon glyphicon-{{entry_icon}} label-icon' title='{{entry_type}}'></span>
                </div>

                <div class="col-xs-12 col-bare visible-xs-block">
                    {{account_from_value}}
                    <span class='glyphicon glyphicon-triangle-right'></span>
                    {{account_to_value}}
                </div>

            </div>
        </div>
    {{/transactions}}

    </div>
</div>
