<!DOCTYPE html>
<html
    <head>
        <?php require "C_deputados.php"; ?>
        <?php require "C_legislativa_DF.php"; ?>
        <title>Agent-Crawler</title>
        <meta charset="UTF-8"/>
        <style>
            div{
                align-content: center;
            }
            ul{
                list-style-type:none;
            }
        </style>
    </head>
    <body>
        <div>
            <?php
                if(isset($_POST['comecar'])){
                    if($_POST['comecar'] == "Legislativa")
                    {
                        obter_conteudoL();
                    }
                    if($_POST['comecar'] == "Deputados")
                    {
                        obter_conteudoD();
                    }
                }
            ?>
            <h1>Agents</h1>
            <form method="post">
                <ul>
                    <li>
                        <label class="inpTit">C&Acirc;MARA LEGISLATIVA DO DISTRITO FEDERAL</label>
                        <input class="buscar" type="submit" name="comecar" value="Legislativa"/>
                    </li>
                </ul>
                 <ul>
                    <li>
                        <label class="inpTit">C&Acirc;MARA DOS DEPUTADOS</label>
                        <input class="buscar" type="submit" name="comecar" value="Deputados"/>
                    </li>
                </ul>
                <ul>
                    <li>
                        <label class="inpTit">Limpar</label>
                        <input class="limpeza" type="submit" name="comecar" value="Resetar"/>
                    </li>
                </ul>
            </form>           
        </div>
    </body>
</html>