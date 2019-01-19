<div class='col-sm-4 col-sm-offset-4'>  
    <h1>Enter Text</h1>
    {{#error}}
        <p class='alert bg-danger'>{{error}}</p>
    {{/error}}
    <form action="/transaction/text" method="post">
        <div class="form-group">
            <textarea class="form-control" name="transaction-text-entry" id="transaction-text-entry" placeholder="Paste text here" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
    </form>
</div>
