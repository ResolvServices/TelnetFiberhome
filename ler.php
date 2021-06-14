<?php

$arquivo = file('script.txt');


/*while(!feof($arquivo)){
    $linha = fgets($arquivo);
    $linha = explode("\n",$linha);
}
*/
/*while(!feof($arquivo))
{
 $linha = fgets($arquivo, 1024);
 echo $linha."\n";
 };*/
$slotportid;

$arquivo = implode("\n",$arquivo);
//fclose($arquivo);
print_r ($arquivo);

?>