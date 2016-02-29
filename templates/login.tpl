<div class='col-sm-4 col-sm-offset-4'>  
    <h1>Login</h1>
    {{#error}}
    <p class='alert bg-danger'>{{error}}</p>
    {{/error}}
    <form action="" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" value="{{username}}">
        </div>
        <div class="form-group">
            <label for="username">Password</label>
            <input type="password" class="form-control" name="password" id="password" value="{{password}}">
        </div>
        <button type="submit" class="btn bt-default">Log In</button>
    </form>
</div>
