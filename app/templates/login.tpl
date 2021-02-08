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
        <div class="form-group">
            <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="remember" id="remember" value="1" {{#remember}}checked='checked'{{/remember}} >
                <label for="remember" class="custom-control-label">Remember Me (Private Device)</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Log In</button>
    </form>
</div>
