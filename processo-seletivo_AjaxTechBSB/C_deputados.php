<?php
require "simple_html_dom.php";
set_time_limit(0);
function filtro($tagname, $constraint, $file)
{
    $pattern = "/<$tagname $constraint>(.*?)<\/$tagname>/";
    preg_match($pattern,$file,$matches); //Utilizando Expressão Regular para filtrar o dado 
    unset($matches[0]); // Garantir a unicidade do array
    $matches = array_unique($matches);
    $string = implode("",$matches); // Transformar o array em String
    return $string;
}

function recuperar_pagina()
{
    $url = "http://www2.camara.leg.br/transparencia/licitacoes-e-contratos/editais";
    $file = str_get_html(file_get_contents($url)); // Coletar o conteúdo e transformar em String
    $resultado = filtro("ul", "class=\"listaMarcada\"", $file); // Utilizando expressão regular para filtrar
    return $resultado;
}

function obter_links()
{
    $string = "<string>". recuperar_pagina()."</string>";
    $xml = simplexml_load_string($string); // Transformando string em XML.
    $i = 0;
    while (isset($xml->li[$i]->a['href'])){   // Coletar todos os links da página através do XML
        $links[] = $xml->li[$i]->a;
        $i++;
    }
    return $links;
}

function obter_conteudo()
{
    $links = obter_links();
    foreach($links as $link){ // Passar por todas as páginas e coletar informações
        $file = str_get_html(file_get_contents($link['href'])); // Coletando página e transformando em String.
        $resultado = filtro("div","class=\"\" id=\"parent-fieldname-.*?\"",$file); // XML para coletar somente o conteúdo important
        if($resultado<>NULL){
            $licitacoes[] = "<hr><br/><a id='paglink' href='{$link['href']}'><b>$link:</b> {$link['href']}</a><br/>".$resultado; // Colando Link, para o destino da página, ao qual o conteúdo pertence.
        } else {
             $licitacoes[] = "<hr><br/><a id='paglink' href='{$link['href']}'><b>$link:</b> {$link['href']}</a><br/>"."<strong style='color:red'>Algum erro ocorreu nessa página!</strong>";
        }
    }
    $string = implode("",$licitacoes);
    return $string;
}

$dados = obter_conteudo();
echo "$dados";
