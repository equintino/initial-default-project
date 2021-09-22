<form id="form-token" class="form-signin" action="#" method="POST" >
    <input type="hidden" name="Logon" value="<?= $login ?>" />
    <input class="form-control" type="password" name="Senha" autofocus/>
    <label class="label" for="Senha">Digite sua nova senha</label>
    <input class="form-input" type="password" name="confSenha" />
    <label class="label" for="confSenha">Confirme</label>
    <button id="btn-token" class="button-style mt-3" >Enviar</button>
</form>
