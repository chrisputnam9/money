<div class='row'>
    <div class='col-sm-12'>
        <div class="panel panel-default panel-tight">

            <div class="panel-heading">
                <b>Current User</b>
            </div>

            <div class="panel-body">
                <div class='table-responsive text-left'>
                    <table class='table table-bordered'>
                        {{#user}}
                            <tr>
                                <th>Username:</th>
                                <td>{{name}}</td>
                            </tr>
                            <tr>
                                <th>API Key:</th>
                                <td><input type='text' readonly='readonly' value='{{api_key}}' size='25' /></td>
                            </tr>
                        {{/user}}
                    </table>
                </div>

                    <a class="btn btn-danger" href="/logout">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <span>Log Out</span>
                    </a>
            </div>
        </div>
    </div>
</div>
