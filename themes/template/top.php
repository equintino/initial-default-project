<nav id="topHeader" class="navbar navbar-expand-lg navbar-dark" >
    <a class="navbar-brand" href="http://www.emqdesenv.cf" target="_blank">
        <img src="<?= $head["logo"] ?>" alt="logo" height="40" />
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse pl-4" id="navbarSupportedContent" style="">
        <ul class="nav navbar-nav mr-auto">
            <li class="active">
                <a data-id="home" class="nav-link" href="<?= url("home") ?>" >Home<span class="sr-only">(current)</span></a>
            </li>
            <li class="dropdown" >
                <!-- <a id="cadastro" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" >
                    Cadastro
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a id="cliente" href="<?= url("cadastro") ?>" class="dropdown-item" >
                        Cliente
                    </a>
                    <?php if (in_array('Fornecedor', $access) || in_array("*", $access)): ?>
                        <a id="fornecedor" href="<?= url("cadastro/fornecedor") ?>" class="dropdown-item" >
                            Fornecedor
                        </a>
                    <?php endif; ?>
                    <?php if (in_array('Transportadora', $access) || in_array("*", $access)): ?>
                        <a id="transportadora" href="<?= url("cadastro/transportadora") ?>" class="dropdown-item" >
                            Transportadora
                        </a>
                    <?php endif; ?>
                </div> -->
            </li>
            <li class="dropdown" >
                <!-- <a id="gerenciamento" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false" >
                    Gerenciamento
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if (!empty($access) && in_array('Gerenciamento de Preços', $access) || in_array("*", $access)): ?>
                        <a id="preco" href="<?= url("gerenciamento/precos") ?>" class="dropdown-item" >
                            Altera Preços
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($access) && in_array('Gerenciamento de Entrega', $access) || in_array("*", $access)): ?>
                        <a id="entrega" href="<?= url("gerenciamento/entregas") ?>" class="dropdown-item" >
                            Entrega de Produtos
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($access) && in_array('Gerenciamento de Orçamento', $access) || in_array("*", $access)): ?>
                        <a id="orcamento" href="<?= url("gerenciamento/orcamento") ?>" class="dropdown-item" >
                            Orçamentos
                        </a>
                    <?php endif ?>
                    <?php if (in_array('Gerenciamento de Estoque', $access) || in_array("*", $access)): ?>
                        <a id="estoque" href="<?= url("gerenciamento/estoque") ?>"  class="dropdown-item" >
                            Tempo no Estoque
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($access) && in_array('Gerenciamento de Vendas', $access) || in_array("*", $access)): ?>
                        <a id="vendas" href="<?= url("gerenciamento/vendas") ?>" class="dropdown-item" >
                            Vendas
                        </a>
                    <?php endif; ?>
                </div> -->
            </li>
            <li>
                <!-- <a id="orcamento" class="nav-link" href="<?= url("orcamento") ?>" >Pedidos & Orçamentos</a> -->
            </li>
        </ul>
        <div class="navbar navbar-right config">
            <ul class="nav navbar">
                <?php if(!empty($access) && (in_array("Login de Acesso", $access) || in_array("*", $access))): ?>
                    <li>
                        <a data-id="user" class="nav-link icon-login" href="<?= url("user") ?>" >
                            <i class="fa fa-id-card" title="Cadastro de Login"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(!empty($access) && (in_array("Segurança", $access) || in_array("*", $access))): ?>
                    <li>
                        <a data-id="shield" class="nav-link icon-shield" href="<?= url("shield") ?>" >
                            <i class="fa fa-shield" title="Segurança" ></i>
                        </a>
                    </li>
                <?php endif ?>
                <?php if(!empty($access) && (in_array("Configuração", $access) || in_array("*", $access))): ?>
                    <li>
                        <a data-id="config" class="nav-link icon-config" href="<?= url("config") ?>">
                            <i class="fa fa-cog" title="Configuração" ></i>
                        </a>
                    </li>
                <?php endif ?>
                <li>
                    <a data-id="exit" class="nav-link icon-exit" href="<?= url("exit") ?>" >
                    <!-- <a id="sair" class="nav-link icon-exit" href="sair" > -->
                        <i class="fa fa-sign-out" title="Sair" ></i>
                    </a>
                </li>
            </ul>
        </div><!-- navbar rigth-->
    </div><!-- navbar-collapse -->
</nav>
<div class="identification"><i>Usuário:</i> <?= ($logged ?? "No user logged in") ?></div>
