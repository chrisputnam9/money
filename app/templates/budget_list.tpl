{{#budgeted_length}}
<div class='row'>
    <div class='col-sm-12'>

        <div class="panel panel-default panel-tight hidden-xs hidden-sm">
            <div class="panel-body panel-heading">
                <div class="col-xs-6 col-md-3 col-bare">Category</div>
                <div class="col-xs-6 col-md-3 col-bare pad-left">Spent</div>
                <div class="col-xs-12 col-md-6 col-bare">Remaining</div>
            </div>
        </div>

    {{#budgeted}}
        <a href='{{transactions_url}}' class='text-muted'>
            <div class="panel panel-default panel-tight">
                <div class="panel-body">
                    <div class="col-xs-6 col-md-3 col-bare">
                        <b>{{category}}</b>
                        <br>
                    </div>
                    <div class="col-xs-6 col-md-3 col-bare pad-left">
                        <div class="hidden-xs hidden-sm">
                            <small>{{spending_formatted}} spent of {{limit_formatted}}</small>
                        </div>
                        <div class="visible-xs-block visible-sm-block text-right">
                            <small>{{spending_formatted}} spent of {{limit_formatted}}</small>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-bare">
                        <div class="progress">
                            <div class="progress-bar progress-bar-{{status}}" role="progressbar" aria-valuenow="{{remaining_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{remaining_percentage}}%; min-width:30px;">
                                <strong><span class='percent'>{{remaining_formatted}}</span></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-bare">
                        {{budget_list_formatted}}
                    </div>
                </div>
            </div>
        </a>
    {{/budgeted}}

    </div>
</div>
{{/budgeted_length}}

{{#unbudgeted_length}}
<div class='row'>
    <br/>
    <div class='col-sm-offset-2 col-sm-8 col-xs-12'>
        <h2 class='text-center'>Unbudgeted</h2>
        <div class='table-responsive text-left'>
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                {{#unbudgeted}}
                    <tr>
                        <td><a href="{{transactions_url}}">{{category_value}}</a></td>
                        <td align="right">{{amount_formatted}}</td>
                    </tr>
                {{/unbudgeted}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{/unbudgeted_length}}
