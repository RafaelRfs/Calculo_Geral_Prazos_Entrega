 # **Calculo_Geral_Prazos Entrega**
 
 * Classe do Php para calcular o prazo de entrega de projetos com restrições de Feriados e Finais de Semana, horário de expediente(Entrada e saída do funcionário... *
 
 Chamada:
 
include('calculo_geral.php');

$calculo_geral = new Calculo_Geral();

$data[0] =  72; //Prazo de Horas/Niveis => 108 horas -> 4,5 ou 5 dias
$data[1] = '2018-03-15 13:00:00'; //Data Atual, Opcional, pega a data Atual
$data[2] = 8; // Entrada do funcionario, Opcional, caso n seja definido é 8:00 hrs
$data[3] = 20; //Saida do Funcionario, Opcional, caso n seja definido é 20:00 hrs
$data[4] = 1; //Dia inicial,Opcional Caso não seja definido é Segunda feira
$data[5] = 5; //Dia Final,Opcional Caso não seja definido é Sexta feira


$feriados[0] = '16/03';
//$feriados[1] = '22/03';
 //$feriados[2] = '19/03';
//$feriados[3] = '21/03';
//$feriados[4] = '22/03';


//$calculo_geral->feriadosAdd($feriados);

echo '<br>'.$calculo_geral->Prazos_entregas($data);


#Resultado: 19/03/2018 13:00:00#
