<?php

const PAGAMENTO = 1; //percentual
const VERMELHO = 2; // par
const PRETO = 1; // ímpar
const VERDE = 0;

const APOSTA_COR = VERMELHO;
const APOSTA_QTDE = 1000;
const APOSTA_VALOR = 1;
const PERCENTUAL_RETORNO_ESPERADO = 0.1;



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

$vezesSimulacao = 10000;
$rodadasSimulacao = 0;
$valorMaximo = 0;
for ($simulacoes = 0; $simulacoes <= $vezesSimulacao; $simulacoes++) {
    $valoresInvestidos = 0;
    $caixa = 0;



    $valorAposta = APOSTA_VALOR;
    $rodada = 0;

    //echo "==================================================\n";
    //echo 'Rodada:                   ' . $rodada."\n"; 
    //echo 'Valor Investido:          ' . $caixa."\n"; 
    //echo 'Valor Total Investido:    ' . $valoresInvestidos."\n"; 

    $proxValorInvestir = APOSTA_QTDE*APOSTA_VALOR;
    //$valoresInvestidos=$proxValorInvestir;
    $valorObjetivo = $proxValorInvestir*PERCENTUAL_RETORNO_ESPERADO;
    //$valor = 0;
    while ($caixa < $valorObjetivo) {
        $rodada++;
        $rodadasSimulacao++;
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
        usleep(10);
        //echo "\n\n\nXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
        //echo "Cx: ".$caixa."\n";
        //echo "Vo: ".$valorObjetivo."\n";
    }

    echo "=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=\n";
    echo 'Rodada:                   ' . $rodada."\n"; 
    echo 'Valor Total Investido:    ' . $valoresInvestidos."\n";
    echo 'Caixa:                    ' . $caixa."\n";
    echo "=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=\n";

    if ($valoresInvestidos > $valorMaximo) {
        $valorMaximo = $valoresInvestidos;
    }
}

echo "=E=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=E=\n";
echo 'Rodadas:                   ' . number_format($rodadasSimulacao,0,',','.')."\n"; 
echo 'Valor Máximo Investido:    ' . number_format($valorMaximo,2,',','.')."\n";
echo 'Valor Ganho:               ' . number_format($valorObjetivo*$vezesSimulacao,2,',','.') ."\n";
echo "=E=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=W=E=\n";

