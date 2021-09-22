<?php

/** Core */
$config = function() {
    return new Config\Config();
};

$user = function() {
    return new Models\User();
};

$group = function() {
    return new Models\Group();
};

/** cookie */
$cookie = filter_input(INPUT_COOKIE, "svlogin", FILTER_SANITIZE_STRIPPED);
parse_str($cookie, $cookie);

$login = base64_decode(filter_input(INPUT_COOKIE, "login", FILTER_SANITIZE_STRIPPED));

/** set constants */
$constText = "CONF_CONNECTION=local\r\nCONF_URL_BASE=stylized-database-table\r\nCONF_URL_TEST=test/stylized-database-table\r\nCONF_BASE_THEME=layout\r\nCONF_VIEW_THEME=template\r\nCONF_SITE_NAME=Site-Address\r\nCONF_SITE_TITLE=System Name\r\nCONF_SITE_DESC=System Description";
(new Support\FileTransation(".env", $constText))->getConst();

/** url */
function url(string $path=null): string
{
    $path = preg_replace("/^\//", "", $path);
    return "../" . CONF_URL_BASE . "/" . $path;
}

function url_back(): string
{
    return ($_SERVER["HTTP_REFERER"] ?? url("home"));
}

function theme(string $path)
{
    $bar = substr_count($_SERVER["REQUEST_URI"], "/");
    if(preg_match("/ops/", $_SERVER["REQUEST_URI"])) {
        return "../themes/" . CONF_VIEW_THEME . "/{$path}";
    } elseif($bar === 3) {
        return "../themes/" . CONF_VIEW_THEME . "/{$path}";
    } else {
        return "themes/" . CONF_VIEW_THEME . "/{$path}";
    }
}

/** @var string $format(d/m/y) */
function dateFormat(?string $date)
{
    if(empty($date)) {
        return null;
    }
    if(preg_match('/\//', $date)) {
        list($d, $m, $y) = explode("/", $date);
        $dateFormated = "{$y}-{$m}-{$d}";
        $datetime = new DateTime($dateFormated);
        return $datetime->format("Y-m-d H:i:s");
    }
    if(preg_match('/-/', $date)) {
        list($y, $m, $d) = explode("-", $date);
        if(preg_match('/ /', $d)) {
            $d = explode(" ", $d)[0];
        }
        return "{$d}/{$m}/{$y}";
    }
}

/** */
function removeAccent(string $string)
{
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
}

function removeAccentArray(array $array): array
{
    foreach($array as $key => $value) {
        $arr[removeAccent($key)] = $value;
    }
    return $arr ?? [];
}

function filterNull($var)
{
    return $var !== null;
}

function filterNullException($array, $except)
{
    return (
        array_filter($array, function($v, $k) use($except) {
            return ($v !== null || in_array($k, $except));
        },ARRAY_FILTER_USE_BOTH)
    );
}

function formatReal(string $value): float
{
    return str_replace(",",".",str_replace(".","",$value));
}

function getPost(array $post)
{
    foreach($post as $k => $v) {
        $requestData[$k] = filter_input(INPUT_POST, $k, FILTER_SANITIZE_STRIPPED);
    }
    return $requestData;
}
