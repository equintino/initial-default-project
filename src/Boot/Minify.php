<?php

//if(preg_match("/{$_SERVER["REMOTE_ADDR"]}/", url()) || preg_match("/localhost/", url())) {
//if(preg_match("/{$_SERVER["REMOTE_ADDR"]}/", url())) {
    /** css */
    $minCSS = new MatthiasMullie\Minify\CSS();

     /** theme */
      $themes = "/../../themes/";
      $cssDir = scandir(__DIR__ . $themes . CONF_VIEW_THEME . "/assets/css");
      foreach($cssDir as $css) {
          $cssFiles = __DIR__ . $themes . CONF_VIEW_THEME . "/assets/css/{$css}";
          if(is_file($cssFiles) && pathinfo($cssFiles)["extension"] === "css") {
              $minCSS->add($cssFiles);
          }
      }

    //$minCSS->add(__DIR__ . "/../../shared/styles/datatables.css");
    // $minCSS->add(__DIR__ . "/../../shared/styles/style-login.css");
    // $minCSS->add(__DIR__ . "/../../shared/styles/style-security.css");
    // $minCSS->add(__DIR__ . "/../../shared/styles/style-register.css");

    /** MinifyCss */
    $minCSS->minify(__DIR__ . $themes . CONF_VIEW_THEME . "/assets/style.css");

    /** js */
    $minJS = new MatthiasMullie\Minify\JS();

    /** theme */
    $jsDir = scandir(__DIR__ . $themes . CONF_VIEW_THEME . "/assets/js");
    foreach($jsDir as $js) {
        $jsFiles = __DIR__ . $themes . CONF_VIEW_THEME . "/assets/js/{$js}";
        if(is_file($jsFiles) && pathinfo($jsFiles)["extension"] === "js") {
            $minJS->add($jsFiles);
        }
    }

    //$minJS->add(__DIR__ . "/../../shared/scripts/datatables.js");
    $minJS->add(__DIR__ . "/../../shared/scripts/functions.js");
    $minJS->add(__DIR__ . "/../../shared/scripts/script-config.js");
    $minJS->add(__DIR__ . "/../../shared/scripts/script-security.js");
    $minJS->add(__DIR__ . "/../../shared/scripts/script-user.js");
    $minJS->add(__DIR__ . "/../../shared/scripts/script-menu.js");
    // $minJS->add(__DIR__ . "/../../shared/scripts/script.js");
    // $minJS->add(__DIR__ . "/../../shared/scripts/bootbox.js");
    // $minJS->add(__DIR__ . "/../../shared/scripts/script-register.js");
    // $minJS->add(__DIR__ . "/../../shared/scripts/script-management.js");
    // $minJS->add(__DIR__ . "/../../shared/scripts/script-budget.js");

    /** MinifyCss */
    $minJS->minify(__DIR__ . $themes . CONF_VIEW_THEME . "/assets/scripts.js");
//}
