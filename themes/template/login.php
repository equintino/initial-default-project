<!DOCTYPE html>
    <head>
        <title><?= CONF_SITE_TITLE ?></title>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="<?= theme("assets/img/logo.png") ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?= theme("assets/style.css") ?>" rel="stylesheet"/>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    </head>
    <body class="body gradient">
        <header>
            <div class="logo"></div>
            <div id="alert"></div>
        </header>
        <main class="box">
            <form class="form-signin" action="#" method="post" >
                <div class="input-group mt-2">
                    <i class="fa fa-user icon-input" ></i>
                    <input type="text" id="login" class="form-input" name="login" placeholder="login ou e-mail" required="" autofocus="" value="<?= (!empty($login) ? $login : null) ?>" />
                </div>
                <div class="input-group">
                    <i class="fa fa-key icon-input"></i>
                        <input id="password" type="password" class="form-input" name="password" placeholder="Senha" />
                </div>
                <div class="input-group">
                    <i class="fa fa-building icon-input"></i>
                    <select id="connection-name" class="form-input" name="connection-name" placeholder="Conexão" required>
                        <option value=""></option>
                        <?php foreach($connectionList as $local):
                                    $selected = ($local === $connectionName ? "selected" : null)?>
                            <option value="<?= $local ?>" <?= $selected ?>><?= $local ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="checkbox m-2">
                    <label><input type="checkbox" name="remember" value="1" <?= (!empty($checked) && $checked == 1 ? "checked" : null) ?> > Lembre-me</label>
                </div>
                <button class="button-style" type="submit">Entrar</button>
                <p class="mt-1 text-muted">© 2020 <?= (!empty($version) ? "({$version})" : null) ?></p>
            </form>
        </main>
        <div id="boxe_main"></div>
        <div id="mask_main"></div>
        <script type="text/javascript" src="<?= theme("assets/scripts.js") ?>" ></script>
    </body>
</html>
