<div id="config">
    <div class="buttons">
        <button class="button btnAction">Adicionar</button>
    </div>
    <div class="table-conf">
        <fieldset class="fieldset pt-3" style="width: 100%">
            <legend>BANCO DE DADOS</legend>
            <table aria-describedby="List of access to the bank" id="tab-conf" class="my-table" >
                <thead>
                    <tr>
                        <th scope=1></th>
                        <th scope=2>NOME</th>
                        <th scope=3>TIPO</th>
                        <th scope=4>ENDEREÇO</th>
                        <th scope=5>NOME DO BANCO</th>
                        <th scope=6>USUÁRIO</th>
                        <th scope=7 colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $localSelected = $config->getConfConnection();
                        foreach($config->getFile() as $local => $params):
                            $active = null;
                            $background = null;
                            $arrow = null;
                            $config->local = $local;
                            if($localSelected === $local) {
                                $active = "*";
                                $arrow = "<i class='fa fa-arrow-right' aria-hidden='true' ></i>";
                                $background = "#c3d2dd";
                            } ?>
                    <tr style="background: <?= $background ?>">
                        <td><?= (!empty($arrow) ? $arrow : null) ?></td>
                        <td><?= $active.$local ?></td>
                        <td><?= $config->type() ?></td>
                        <td><?= $config->address() ?></td>
                        <td><?= $config->database() ?></td>
                        <td><?= $config->user() ?></td>
                        <td class="icon-edition"><span class="fa fa-pencil edition"></span></td>
                        <td class="icon-edition"><span class="fa fa-trash delete"></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
