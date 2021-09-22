<?php

/** table creation file */
require_once __DIR__ . "/../Support/FileTransation.php";
//require_once __DIR__ . "/../Boot/Helpers.php";
require __DIR__ . "/../../vendor/autoload.php";

// $session = new Core\Session();
// $creation = new Database\CreationProcess();
//$creation->define("local");

/** init table Dados_Empresa */
$company = new Models\Company();
$company->createThisTable();
$data = [
    "NomeFantasia" => "Nome Fantasia",
    "CNPJ" => "56.123.111/1234-25",
    "ATIVO" => 1
];
$company->bootstrap($data);
$company->save();
echo "Dados_Empresa - " . $company->message() . "<br>";

/** init table CadArquivos */
$file = new Models\FileRegistration();
$file->createThisTable();
$date = new \DateTime();
$data = [
    //"COD_ARQUIVO" => 1,
    "IND_LOCAL" => 5,
    "COD_EMPRESA" => 1,
    "COD_DOCUMENTO" => 1,
    "NOM_ARQUIVO" => "teste.pdf",
    "DAT_INCLUSAO" => null,
    "IND_TIPO" => 1
];
$file->bootstrap($data);
$file->save();
echo "Arquivo - " . $file->message() . "<br>";

/** init table PFisica */
// $client = new Models\Client();
// $client->createThisTable();
// $data = [
//     "Nome" => "Cliente Teste",
//     "CPF" => "111.111.111-11",
//     "Email" => "teste@gmail.com"
// ];
// $client->bootstrap($data);
// $client->save();
// echo "PFisica - " . $client->message() . "<br>";

/** init table PJuridica */
// $legalPerson = new Models\LegalPerson();
// $legalPerson->createThisTable();
// $data = [
//     "NomeFantasia" => "Minha Empresa",
//     "CNPJ" => "11.117.117/0001-15",
//     "Email" => "empresa@gmail.com"
// ];
// $legalPerson->bootstrap($data);
// $legalPerson->save();
// echo "PJuridica - " . $legalPerson->message() . "<br>";

/** init table Transportadora */
// $transport = new Models\Transport();
// $transport->createThisTable();
// $data = [
//     "RasSocial" => "SEM FRETE",
//     "Cnpj" => null,
//     "IDEmpresa" => 1,
//     "Contato" => "Sem Contato"
// ];
// $transport->bootstrap($data);
// $transport->save();
// echo "Transportadora - " . $transport->message() . "<br>";

/** init table Fornecedor */
// $supplier = new Models\Supplier();
// $supplier->createThisTable();
// $data = [
//     "RasSocial" => "Minha Empresa",
//     "CNPJ" => "11.117.117/0001-15",
//     "Email" => "empresa@gmail.com"
// ];
// $supplier->bootstrap($data);
// $supplier->save();
// echo "Fornecedor - " . $supplier->message() . "<br>";

/** init table Vendedor */
// $saleman = new Models\Saleman();
// $saleman->createThisTable();
// $data = [
//     "LogON" => "edmilson",
//     "Senha" => "123",
//     "Email" => "vendedor@gmail.com",
//     "IDEmpresa" => "1"
// ];
// $saleman->bootstrap($data);
// $saleman->save();
// echo "Vendedor - " . $saleman->message() . "<br>";

/** init table Venda */
// $sale = new Models\Sale();
// $sale->createThisTable();
// $data = [
//     "Pedido" => "1",
//     "IDCliente" => 1,
//     "NomeCliente" => "Cliente Teste",
//     "CNPJeCPF" => "111.111.111-11",
//     "TipoCliente" => "PFisica",
//     "TabVenda" => 1,
//     "DataVenda" => "2020-01-01 00:00:00.000",
//     "HoraVenda" => "2020-01-01 18:00:00.000",
//     "Vendedor" => 1,
//     "Valor" => 100.1000,
//     "FormaPagamento" => 1,
//     "TipoPagamento" => 1,
//     "TipoEntrega" => 1,
//     "Frete" => 10.10000,
//     "OBS" => "observação",
//     "Comprador" => "Eu mesmo",
//     "Transportadora" => 1,
//     "DataEntrega" => "2020-01-01 01:00:00.000",
//     "HoraEntrega" => "2020-01-01 00:00:00.000",
//     "IDEmpresa" => "1",
//     "Status" => "V"
// ];
// $sale->bootstrap($data);
// $sale->save();
// echo "Pedido - " . $sale->message() . "<br>";

/** init table Produto_Venda */
// $product = new Models\Product();
// $product->createThisTable();
// $data = [
//     "Pedido" => 1,
//     "IDProduto" => "1",
//     "Descrição" => "Produto de Teste",
//     "IDEmpresa" => "1"
// ];
// $product->bootstrap($data);
// $product->save();
// echo "Produto - " . $product->message() . "<br>";die;
