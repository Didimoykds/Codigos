<?php
require_once 'simple_html_dom.php';
function filtro($tagname, $constraint, $file)
{
    $pattern = "/<$tagname $constraint>(.*?)<\/$tagname>/";
    preg_match($pattern,$file,$matches); //Utilizando Expressão Regular para filtrar o dado 
    unset($matches[0]); // Garantir a unicidade do array
    $string = implode("",$matches); // Transformar o array em String
    return $string;
}
function recuperar_primeira_pagina($url)
{
    $file = str_get_html(file_get_contents($url)); // Coletar o conteúdo e transformar em String
    $resultado = filtro("div", "class=\"dm_row dm_light\"", $file); // Utilizando expressão regular para filtrar
    return $resultado;
}
function recuperar_segunda_pagina($url)
{
    $file = str_get_html(file_get_contents($url));
    $pattern = "/<div id=\"dm_cats\">(.*?)<\/div>.*?<br \/>/";
    preg_match($pattern, $file, $matches);
    unset($matches[1]); // Garantir a unicidade do array
    $string = implode("", $matches);
    return $string;
}
function str_para_link($link,$url)
{
    if (substr($link, 0, 1) == "/" && substr($link, 0, 2) != "//") {
        $link = parse_url($url)["scheme"]."://".parse_url($url)["host"].$link;
    } 
    return $link;
}

function primeira_camada()
{
    $url = 'http://licitacoes.ssp.df.gov.br./index.php/licitacoes';
    $conteudo = recuperar_primeira_pagina($url);
    $elemento = filtro("h3","class=\"dm_title\"",$conteudo);
    $xml = simplexml_load_string("<string>".$elemento."</string>");
    $titulo = $xml->a;
    $link = $xml->a['href'];
    $newLink = str_para_link($link,$url);
    echo "<hr style='border-color:red;'/>";
    echo "<h1><strong>$titulo : </strong><a href=\"$newLink\">$newLink</a></h1>";
    echo "<hr style='border-color:red;'/>";
    return $newLink;
}

function distribuirDados($url){
    $file = str_get_html(file_get_contents($url));
    $xml = simplexml_load_string($file);
    $resultado = $xml->xpath('//*[@id="dm_docs"]')[0];
    $resultado2 = $xml->xpath('//*[@class="dm_row dm_light"]');
    
    foreach($resultado2 as $objeto){
        $titulo = $resultado->h2;
        $linkName = $objeto->h3->a;
        $link = $objeto->h3->a['href'];
        $publicado = $objeto->div->table->tr->td[1];
        $descricao = $objeto->div[1]->p;
        $descricao2 = $objeto->div[1]->p->span;
        $download = $objeto->div[3]->ul->li[0]->a;
        $downloadLink = str_para_link($download['href'], $url);
        $details = $objeto->div[3]->ul->li[1]->a;
        $detailsLink = str_para_link($details['href'], $url);
        echo "<hr/>";
        echo "<b>$titulo</b><br/>";
        echo "$linkName : <a href=\"$link\">$link</a><br/>";
        echo "Publicado: $publicado<br/>";
        echo "Descrição: $descricao $descricao2 <br/>";
        echo "$download: <a href=\"$downloadLink\">$downloadLink</a><br/>";
        echo "$details: <a href=\"$detailsLink}\">$detailsLink</a><br/>";
    }
}

function segunda_camada()
{
    $url = primeira_camada();
    $conteudo = recuperar_segunda_pagina($url);
    $xml = simplexml_load_string("<string>".$conteudo."</string>");
    $i = 0;
    while(isset($xml->div->div->div[$i]->h3->a)){
        $tags_a[] = $xml->div->div->div[$i]->h3->a;
        $i++;
    }
    foreach($tags_a as $tag_a){
        $link = str_para_link($tag_a['href'],$url);
        echo "<hr style='border-color:green;'/>";
        echo "<h2>$tag_a: <a href=\"$link\">$link</a></h2>";
        distribuirDados($link);
    }
}

segunda_camada();