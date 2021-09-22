<div id="user">
    <header class="header row form-inline">
        <div class="col select-company">
            <?php if(!empty($companys)): ?>
            <span class="color-primary">Empresa:</span>
            <select class="form-input" name="NomeFantasia">
                <option value=""></option>
                <?php foreach($companys as $company): ?>
                <option value="<?= $company->ID ?>" <?= ($company->ID === $companyId ? "selected" : null) ?> ><?= $company->NomeFantasia ?></option>
                <?php endforeach ?>
            </select>
            <?php endif ?>
        </div>
        <div class="col-2 buttons">
            <button class="button btnAction" style="float: right">Adicionar</button>
            <button class="button btnAction" style="float: right; margin-right: 5px">Listar</button>
        </div>
    </header>
    <main id="exhibition" ></main>
</div>
