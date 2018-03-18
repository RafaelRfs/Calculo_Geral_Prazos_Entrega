<?php
class Calculo_Geral{
	private $feriados = array();
	private $diaAtual1More = Null;
	
	public function __construct(){
		$this->feriados = $this->dias_feriados();
		$this->diaAtual1More = new DateTime();
		
	}
	
	public function dias_feriados($ano = null)
	{
	if ($ano === null)
	{
	$ano = intval(date('Y'));
	}

	$pascoa     = easter_date($ano); 
	$dia_pascoa = date('j', $pascoa);
	$mes_pascoa = date('n', $pascoa);
	$ano_pascoa = date('Y', $pascoa);

	//$feriados = array('14/03', '15/03', '30/04', '20/05');
	$feriados = array(                      // Tatas Fixas dos feriados Nacionais Basileiras
	mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal 
	mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes 
	mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador 
	mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência 
	mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida 
	mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos 
	mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica 
	mktime(0, 0, 0, 12, 25,  $ano), // Natal
	mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2ºfeira Carnaval
	mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3ºfeira Carnaval	
	mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa  
	mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa
	mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Cirist
	);
	sort($feriados);
	return $feriados;
	}


	public function isFeriado($data = ''){
	$fer = false;
	$data = (explode('/', $data));
	$day = isset($data[0])? $data[0] : date('d');
	$month = isset($data[1])? $data[1] : date('m');
	$year = isset($data[2])? $data[2] : date('Y');
	$convertDate = mktime (0, 0, 0, $month , $day, $year);
	//$feriados = dias_feriados($year);
	
	if(in_array($convertDate, $this->feriados)){
	$fer = true;	
	}
	return $fer;
	}


	function isFinalSemana($data = '',$diaFinal = 6){
	$fin = false;
	$data = (explode('/', $data));
	$day = isset($data[0])? $data[0] : date('d');
	$month = isset($data[1])? $data[1] : date('m');
	$year = isset($data[2])? $data[2] : date('Y');
	$date = $month.'/'.$day.'/'.$year;

	$dt = (date('N', strtotime($date)));
	if( $dt >= 6){
	$fin = true;	
	}
	return $fin;
	}


	function pegarDiaSemana($date) {
	return date('w', strtotime($date));
	}
	

	function getFeriados(){
		return $this->feriados;
	}
	
	function getDia1More(){ return $this->diaAtual1More; }
	
	function getDiaMore(){ return $this->getDia1More()->format('d/m/Y H:i:s');}
	function setDiaMore($date){ $this->diaAtual1More = $date;}
	
	function set1DiaMore($date){ $this->diaAtual1More = new DateTime($date->format('Y-m-d H:i:s'));}
	
	
	function addDia1More(){
		$this->verifyFeriadoFinalSemana();
		$this->diaAtual1More->add(new DateInterval('P1D'));
		$this->verifyFeriadoFinalSemana();
	}
	
	function addDias($dias = 0){
		for($i =0 ; $i < $dias ; $i++){
			$this->addDia1More();
		}
		$this->verifyFeriadoFinalSemana();
	}
	
	
	function addTime1DiaMore($horasDiferencaPrazo, $minutosDiferencaPrazo, $segundosDiferencaPrazo ){
			$this->diaAtual1More->add(new DateInterval('PT'.$horasDiferencaPrazo.'H'));
			$this->diaAtual1More->add(new DateInterval('PT'.$minutosDiferencaPrazo.'M'));
			$this->diaAtual1More->add(new DateInterval('PT'.$segundosDiferencaPrazo.'S'));	
	}
	
	
	function setTime1DiaMore($hora, $min , $seg){
		$this->diaAtual1More->setTime($hora,$min,$seg);
	}

	
	function verifyFeriadoFinalSemana(){
	$diaSemana = $this->diaAtual1More->format('d/m/Y');
	$dSeman = $this->pegarDiaSemana($diaSemana);

	  if($this->isFinalSemana($diaSemana)){
			if($dSeman == 6){
				$this->diaAtual1More->add(new DateInterval('P2D'));
							}
			else{
				$this->diaAtual1More->add(new DateInterval('P1D'));
				}		
			}
	   //Se for Sabado, Adiciona 2 Dias, Domingo Adiciona 1
	    $diaSemana = $this->diaAtual1More->format('d/m/Y');
		$dSeman = $this->pegarDiaSemana($diaSemana);
		
		if(!$this->isFinalSemana($diaSemana) && $this->isFeriado($diaSemana)){
		$this->diaAtual1More->add(new DateInterval('P1D'));		
		}
	//Se for Final de Semana
	
	    $diaSemana = $this->diaAtual1More->format('d/m/Y');
		$dSeman = $this->pegarDiaSemana($diaSemana);
		
		if(!$this->isFinalSemana($diaSemana) && $this->isFeriado($diaSemana) || $this->isFinalSemana($diaSemana)){
		$this->verifyFeriadoFinalSemana();
		}
		
		
		//Função Recursiva, chama ela mesma para verificar se a nova data é Feriado ou Final de Semana
	
	}//Fecha o GET 1 DAY MORE


	function feriadosAdd($array_holydays = array()){
	//$array_holidays = array('15/03', '16/03', '20/03');
	$year = date('Y');
	foreach($array_holydays as $feriado){
		$date_complete =  $feriado.'/'.$year;
		$datw = explode('/', $feriado);
		$data_normal = $datw[0].'/'.$datw[1].'/'.$year;
		$day = $datw[0];
		$month = $datw[1];
		
		if(!$this->isFeriado($data_normal)){ 
         $ferr = mktime(0, 0, 0, $datw[1],  $datw[0],  $year);	
		 $this->feriados[]  = $ferr;
		 sort($this->feriados); 
		}
	}
   } // Adiciona o feriado na matrix
	


	function Prazos_entregas($data){
		
	$prazoHoras = $data[0]; //Pega o prazo de Horas e define a quantidade de dias /Niveis
	
	$dataX = isset($data[1])? $data[1] : date('Y-m-d H:i:s') ; //Pega o horario Atual 2018-03-15 11:08:00
		
	$prazoHorasConvertida = new DateTime($dataX);
	$prazoHorasConvertida->add(new DateInterval('PT'.$prazoHoras.'H'));

	$horaentradaInicial = isset($data[2])? $data[2] : 8; //Define p 8:00 hrs o horario de entrada, caso não haja valor informado
	$minutosentradaInicial = 0;
	
	$horasaidaInicial = isset($data[3])? $data[3] : 20; //Define p 20:00 hrs o horario de saida, caso não haja valor informa
	$minutossaidaInicial = 0;

	$diaInicialExpediente = isset($data[4])? $data[4] : 1; //Define p Segunda caso não haja valor informado
	$diaFinalExpediente = isset($data[5])? $data[5] : 5;

	$dataAtual = new DateTime($dataX);
	$horasDias = round($prazoHoras/24);

	$dataCalculo = $dataAtual->format('Y-m-d'); //Data Base
	
	$date = new DateTime($dataCalculo);
	$date->setTime($horaentradaInicial, $minutosentradaInicial);
	
	
	$date2 = new DateTime($dataCalculo);
	$date2->setTime($horasaidaInicial, $minutossaidaInicial); //Calcula a horario final do expediente

	
	$diferencaTempEntradaSaida = date_diff($date2,$date);//Conta a diferenca entre entrada e saida do funcionario 8 as 20: 12 horas
	
	$prazoHorasdiferencaTemp  = date_diff($date2, $prazoHorasConvertida);
	
	$horasdiferencaEntradaSaida = $diferencaTempEntradaSaida->h; //Calcula o expediente de serviço
	$diaAtual = $date->format('Y-m-d H:i:s');
	
	$date3 = new DateTime($dataX); //Seta o Horario Atual 
	$horarioAtual = new DateTime($dataX);
	
	$diferencaTempAtual = date_diff($date2, $date3); //Calcula a diferença entre o tempo atual e o restante
	
	$horadiferencaAtual = $diferencaTempAtual->h;
	$minutodiferencaAtual = $diferencaTempAtual->i;
	
	$prazoHorasMomentNow  = date_diff($date3, $prazoHorasConvertida); //Calcula o tempo de diferença do agora e o prazo
	
	$this->setDiaMore($date3); //Seta a variavel Dia More p o momento atual
	
	
	if($prazoHorasMomentNow->d == 0   && $prazoHorasMomentNow->h <= $diferencaTempAtual->h  && $prazoHorasMomentNow->i <= $diferencaTempAtual->i ){
		
		$this->addTime1DiaMore($prazoHorasMomentNow->h, $prazoHorasMomentNow->i, $prazoHorasMomentNow->s);
	
	}//Se o prazo for menor ou igual a hora, minuto e segundo restante, ele acrescenta na hora atual o prazo
	
	else{
	
	$diasTrabalhoNormais = round($prazoHoras / $horasdiferencaEntradaSaida) - 1 ;
	$diferencaTrab = ($diasTrabalhoNormais ) - ($prazoHorasMomentNow->d) ;
	
	if($diferencaTrab   >  0){
		
		for($i = 1; $i < $diferencaTrab;$i++){
			$this->addDia1More();
		}		
	}//Igual o dias de trabalho se a diferença for maior que 1 diaa

	
	  $this->setTime1DiaMore($date->format('H'), $date->format('i'), $date->format('s'));// Seta o tempo p o horario de entrada		
	  
	   $diasRestantes = $prazoHorasMomentNow->d ;
	   
	   if($prazoHoras < 24){
		$this->addDia1More();
		$horas = $prazoHorasdiferencaTemp->h ;
		$minutos = $horarioAtual->format('i');
		$segundos = $horarioAtual->format('s');
		$this->addTime1DiaMore($horas,$minutos,$segundos);
	    }
	   
	   if($prazoHoras == 24 ){
		   // $this->addDia1More();
	   }//Se for igual a 24 horas
	   		
	   for($i=0; $i< $diasRestantes; $i++){
		   $this->addDia1More();
		   
		   if(($i+1) == $diasRestantes){
			   
			   $this->verifyFeriadoFinalSemana();
			   
			    if( $prazoHorasMomentNow->h == 0 && $prazoHorasMomentNow->i == 0 ){
					$horas = $horarioAtual->format('H');
					$minutos = $horarioAtual->format('i');
					$segundos = $horarioAtual->format('s');
					$this->setTime1DiaMore($horas,$minutos,$segundos ); // Se der N dias completos, o horario passa a ser o mesmo do atual 
				
				}else{
					
					$horas = $horarioAtual->format('H') + $prazoHorasMomentNow->h;
					$minutos = $horarioAtual->format('i');
					$segundos = $horarioAtual->format('s');	

					if($horas >=$horaentradaInicial && $minutos >= $minutosentradaInicial  &&  $horas <= $horasaidaInicial  && $minutos <= $minutossaidaInicial ){
						
						$this->setTime1DiaMore($horas,$minutos,$segundos ); // Adiciona horas extras ao horario de entrada 
						
					}//Calcula se tá no horario de trabalho do individuo, caso não esteja:
					
					else if($horas <$horaentradaInicial){
						//Nesse caso o programa vai deixar no horario de tempo
						
						
					}
					
					else if($horas > $horasaidaInicial){
						$horas = $horasaidaInicial - $prazoHorasMomentNow->h - $horadiferencaAtual;
						$contaPrazoHoras = $prazoHoras/$horasdiferencaEntradaSaida;
						$contaPrazoHoras2 = round($contaPrazoHoras) - $contaPrazoHoras;						
						$count_horas = round(($horasdiferencaEntradaSaida * $contaPrazoHoras2)) - 1;
						
						
						if($count_horas > 0){
						$this->addDia1More();	
						$this->addTime1DiaMore($count_horas,$minutos,$segundos);
						
						}else{
							$horas = $prazoHorasdiferencaTemp->h ;
							$this->addTime1DiaMore($horas,$minutos,$segundos);
							
						}
					
					}//Se for maior que a saida
					
				}
	  
			   
		   }//Se for o ultimo dia, acrescenta as horas e os minutos; 
		   
	   }//Contagem dos Dias A partir do tempo restante de prazo
		
	}

	$this->verifyFeriadoFinalSemana();  //Faz a verificação p saber se é dia de feriado ou final de semana
	return $this->getDiaMore(); //F

	}//Fecha a função
	
}
