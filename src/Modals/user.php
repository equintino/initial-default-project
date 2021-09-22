<?php if(!empty($act) && $act === "edit"): ?>
<div id="edit" >
    <form id="login-register" action="#" method="POST" class="form-horizontal">
        <fieldset class="fieldset">
            <legend>IDENTIFICAÇÃO</legend>
            <div class="form-row mb-2">
                <div class="col-md">
                    <input name="id" type="hidden" value="<?= (isset($user) ? $user->id : null) ?>" />
                    <label class="label" for="nome">Nome Completo: </label><br>
                    <input id="nome" name="Nome" class="form-input" type="text" required="required" value="<?= (isset($user) ? $user->Nome : null) ?>"/></div>
                <div class="col-md">
                    <label class="label" for="email">Email:</label><br>
                    <input type="text" class="form-input" id="email" name="Email" required="required" style="text-transform: lowercase" value="<?= (isset($user) ? $user->Email : null) ?>"/></div><!-- col -->
            </div><!-- row -->
            <?php if(!isset($user)): ?>
            <div class="form-row mb-2">
                <div class="col-md">
                    <label for="login" class="label">Login:</label><br>
                    <input type="text" class="form-input" id="login" name="Logon" value="<?= ((isset($user) ? $user->Logon : null)) ?>" required="required"/></div><!-- col -->
                <div class="col-md">
                    <label for="senha" class="label">Senha: </label><br>
                    <input type="password" class="form-input" id="senha" name="Senha" <?= (isset($user)  ? "disabled" : ("required='required'")) ?> /></div><!-- col -->
                <div class="col-md">
                    <label for="senha2" class="label">Confirme: </label><br>
                    <input type="password" class="form-input" id="senha2" name="confSenha" <?= (isset($user) ? "disabled" : ("required='required'")) ?>/></div><!-- col -->
            </div><!-- row -->
            <?php else: ?>
                <input type="hidden" name="Logon" value="<?= $user->Logon ?>" ?>
            <?php endif ?>
            <div class="row mr-4" >
                <div class="col">
                    <label for="cargo" class="label">Cargo: </label><br>
                    <input type="text" class="form-input" id="cargo" name="Cargo" value="<?= (isset($user) ? $user->Cargo : null) ?>"/></div>
                <div class="col">
                    <label for="grupo" class="label" >Grupo:</label><br>
                    <select name="Group_id" class="form-input" required="required" >
                        <option value=""></option>
                        <?php foreach($groups as $group): ?>
                        <option value="<?= $group->id ?>" <?= (isset($user) && $user->Group_id ==  $group->id) ? "selected" : null ?>><?= $group->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md">
                    <label for="visivel" class="label mb-3">ATIVO: &nbsp&nbsp</label><br>
                    <label class="label">SIM </label><input class="form-radio" type="radio" name="Visivel" value=1 <?= (isset($user) && $user->Visivel == 1 ? "checked" : null) ?> />
                    <label class="label"> NÂO </label><input type="radio" name="Visivel" value=0 <?= (isset($user) && $user->Visivel == "0" ? "checked" : null) ?> />
                </div><!-- col -->
            </div>
        </fieldset>
        <button type="submit" class="button save" style="float: right;"><?= (isset($user) ? "Gravar Alteração" : "Salvar") ?></button>
        <?php if(!isset($user)): ?>
        <button type="reset" class="button cancel" style="float: right;">Limpar</button>
        <?php endif ?>
    </form>
</div><!-- edit -->

<?php elseif(!empty($act) && $act === "list"): ?>
    <fieldset class="fieldset p-3" >
        <legend>LISTA DE USUÁRIOS</legend>
        <table id="tabList" class="my-table" width="100%" >
            <thead>
                <tr>
                    <th></th>
                    <th>NOME</th>
                    <th>LOGIN</th>
                    <th>GRUPO</th>
                    <th>ATIVO</th>
                    <th>EDITAR</th>
                    <th>EXCLUIR</th>
                    <th>RESETAR SENHA</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($users)):
                    $login = $_SESSION["login"]->Logon;
                    foreach($users as $user):
                            $arrow = ($login === $user->Logon ? "<i class='fa fa-arrow-right' aria-hidden='true' ></i>" : null);
                            if($user->Logon !== "admin"): ?>
                        <tr <?= ($login !== $user->Logon ?: "style='background: #c3d2dd'") ?> >
                            <td><?= (!empty($arrow) ? $arrow : null) ?></td>
                            <td><?= $user->Nome ?></td>
                            <td><?= $user->Logon ?></td>
                            <td><?= (!empty($user->getGroup()) ? $user->getGroup()->name : null) ?></td>
                            <td><?= $user->Visivel == 1 ? "SIM" : "NÃO"; ?></td>
                            <td title="Edita" data-id="<?= $user->id ?>" data="<?= $user->Logon ?>" ><i class="fa fa-pencil" ></i></td>
                            <td title="Exclui" data-id="<?= $user->id ?>" data="<?= $user->Logon ?>" ><i class="fa fa-times"></i></td>
                            <td title="Reseta" data-id="<?= $user->id ?>" data="<?= $user->Logon ?>" ><i class="fa fa-key "></i></td>
                        </tr>
                <?php endif; endforeach;
                    endif ?>
            </tbody>
        </table>
    </fieldset>
<?php endif; ?>
