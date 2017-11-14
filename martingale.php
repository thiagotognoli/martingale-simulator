<?php

const PAGAMENTO = 1; //percentual
const VERMELHO = 2; // par
const PRETO = 1; // ímpar
const VERDE = 0;

const APOSTA_COR = VERMELHO;
const APOSTA_QTDE = 1000;
const APOSTA_VALOR = 1;



function roda() {
    return rand(0,36);
}

function rodaCor() {
    $numero = roda();
    if ($numero == 0) {
        return VERDE;
    } else if ($numero%2 == 0) {
        return VERMELHO;
    } else {
        return VERDE;
    }
}

function apostaCor($cor, $valor) {
    if ($cor == rodaCor())
        return $valor + $valor*PAGAMENTO;
    return 0;
}

function rodadaApostaCor($cor, $valorApostado, $valorTotal, $valorStop) {
    $valorTotalInicial = $valorTotal;
    $valorGanho = 0;
    $numApostas = 0;
    $numApostasGanhas = 0;


    while ($valorTotal-$valorApostado >= 0) {
        if ($valorTotal+$valorGanho >= $valorStop) {
            echo "==================================================\n";
            echo "Limite Atingido :)\n";
            echo "==================================================\n";
            break;
        }
        $valorTotal -= $valorApostado;
        $numApostas++;
        $valorGanhoAposta = apostaCor($cor, $valorApostado);
        if ($valorGanhoAposta > 0) {
            $numApostasGanhas++;
        }
        $valorGanho += $valorGanhoAposta;
    }
    echo 'Valor das Apostas:        ' . $valorApostado."\n"; 
    echo 'Número de Apostas:        ' . $numApostas."\n"; 
    echo 'Número de Apostas Ganhas: ' . $numApostasGanhas."\n"; 
    echo 'Valor Inicial:            ' . $valorTotalInicial."\n"; 
    echo 'Valor Ganho:              ' . $valorGanho."\n"; //trocar para saldo
    echo 'Valor Final:              ' . ($valorTotal+$valorGanho)."\n"; 
    return array('valor'=>$valorTotal+$valorGanho,'num_apostas_perdidas'=>$numApostas-$numApostasGanhas);
}

$valoresInvestidos = 0;
$caixa = 0;;



$valorAposta = APOSTA_VALOR;
$rodada = 0;

//echo "==================================================\n";
//echo 'Rodada:                   ' . $rodada."\n"; 
//echo 'Valor Investido:          ' . $caixa."\n"; 
//echo 'Valor Total Investido:    ' . $valoresInvestidos."\n"; 

$proxValorInvestir = APOSTA_QTDE*APOSTA_VALOR;
//$valoresInvestidos=$proxValorInvestir;
$valorObjetivo = $proxValorInvestir;
//$valor = 0;
while ($caixa < $valorObjetivo) {
    $rodada++;
    $caixa -= $proxValorInvestir;
    $valor = $proxValorInvestir;
    if ($caixa < 0 && (($caixa*-1) >= $valoresInvestidos)) { 
        $valoresInvestidos = $caixa*-1;
    }
    echo "==================================================\n";
    echo 'Rodada:                   ' . $rodada."\n";
    echo 'Caixa:                    ' . $caixa."\n";
    echo 'Valor Investido:          ' . $valor."\n";
    echo 'Valor Máximo Investido:   ' . $valoresInvestidos."\n";
    echo "==================================================\n";
    $rodadaRet = rodadaApostaCor(VERMELHO, $valorAposta, $valor, ($valorObjetivo+($caixa*-1)));
    $caixa += $rodadaRet['valor'];
    $valorAposta *= 2;
    $proxValorInvestir = $valorAposta*$rodadaRet['num_apostas_perdidas'];
    sleep(1);
    //echo "\n\n\nXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
    //echo "Cx: ".$caixa."\n";
    //echo "Vo: ".$valorObjetivo."\n";
}

echo "==================================================\n";
echo 'Rodada:                   ' . $rodada."\n"; 
echo 'Valor Total Investido:    ' . $valoresInvestidos."\n";
echo 'Caixa:                    ' . $caixa."\n";
echo "==================================================\n";
