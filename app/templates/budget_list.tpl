{{#budgeted_length}}
<div class='row'>
    {{#budgeted}}
    <div class='col-xs-12 col-sm-6 col-md-4'>
        <p class="label label-default">{{category}}</p>
        <div class="progress">
            <div class="progress-bar progress-bar-{{status}}" role="progressbar" aria-valuenow="{{remaining_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{remaining_percentage}}%; min-width:30px;">
                <strong><span class='percent'>{{remaining_formatted}}</span></strong>
            </div>
        </div>
    </div>
    {{/budgeted}}
</div>
{{/budgeted_length}}

{{#unbudgeted_length}}
<div class='row'>
    <br/>
    <div class='col-sm-offset-2 col-sm-8 col-xs-12'>
        <h2 class='text-center'>Unbudgeted Spending {{title}}</h2>
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
                        <td>{{category_value}}</td>
                        <td align="right">{{amount_formatted}}</td>
                    </tr>
                {{/unbudgeted}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{/unbudgeted_length}}
