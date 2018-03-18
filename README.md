 # **Classe de Calculo Geral dos Prazos Entrega**
 
<p>Classe do Php para calcular o prazo de entrega de projetos com restrições de Feriados, Finais de Semana e com base no horário de expediente(Entrada e saída do funcionário).</p>
 
 Chamada:<br>
 
include('calculo_geral.php');<br>

$calculo_geral = new Calculo_Geral();<br>

$data[0] =  48; // Prazo de Horas/Niveis => 48 horas => 2 dias <br>
$data[1] = '2018-03-15 13:00:00'; //Data Atual, Opcional,caso n seja definido, pega a data atual<br>
$data[2] = 8; // Entrada do funcionario, Opcional, caso n seja definido é 8:00 hrs<br>
$data[3] = 20; //Saida do Funcionario, Opcional, caso n seja definido é 20:00 hrs<br>
$data[4] = 1; //Dia inicial,Opcional Caso não seja definido é Segunda feira<br>
$data[5] = 5; //Dia Final,Opcional Caso não seja definido é Sexta feira<br>


$feriados[0] = '16/03';<br>

//$calculo_geral->feriadosAdd($feriados);<br>

echo $calculo_geral->Prazos_entregas($data);<br>


<b>Resultado:</b> 19/03/2018 13:00:00
