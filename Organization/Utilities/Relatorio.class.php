
  <?php 
  class Relatorio extends TWindow
{
/**
/**
 *  Carrega uma classe quando ela é necessária, 
 *  ou seja, quando ela é instancia pela primeira vez. 
 */ 
function __autoload($classe) 
{ 
    if (file_exists("app.ado/{$classe}.class.php")) 
    { 
        include_once "app.ado/{$classe}.class.php"; 
    }
    else if (file_exists("app.widgets/{$classe}.class.php")) 
    { 
        include_once "app.widgets/{$classe}.class.php"; 
    } 
 
}
}
try 
{ 
    $conn = TConnection::open('samples'); // abre uma conexão 
    
    // cria um estilo para o cabeçalho
    $estilo_cabecalho = new TStyle('cabecalho'); 
    $estilo_cabecalho->font_family     = 'arial,verdana,sans-serif'; 
    $estilo_cabecalho->font_style      = 'normal'; 
    $estilo_cabecalho->font_weight     = 'bold';
    $estilo_cabecalho->color           = '#ffffff';
    $estilo_cabecalho->text_decoration = 'none';
    $estilo_cabecalho->background_color= '#825046'; 
    $estilo_cabecalho->font_size       = '100%'; 
  

    $estilo_cabecalho->show();
    
    // cria um estilo para os dados
    
    $estilo_dados = new TStyle('dados'); 
    $estilo_dados->font_family     = 'arial,verdana,sans-serif'; 
    $estilo_dados->language = 'pt'; 
    $estilo_dados->font_style      = 'normal'; 
    $estilo_dados->color           = '#2D2D2D'; 
    $estilo_dados->text_decoration = 'none'; 
    $estilo_dados->font_size       = '100%'; 
    $estilo_dados->show();
    
    // define a consulta
    $sql = 'SELECT id, title, precoservico, precoproduto'.
           ' FROM calendar_event'.
           ' ORDER BY title'; 
    
    // executa a instrução SQL 
    $result = $conn->query($sql);
    
    // instancia objeto tabela 
    $tabela = new TTable; 
     $tabela->add('<a href="http://salaotaiti.com.br/asaosebast/"><p>Voltar para o sistema</a><p><a href="http://salaotaiti.com.br/galeriafotos/preco/tabela.php">Salão taiti Preços</a>'); 
    // define algumas propriedades da tabela
     
    $tabela->width = '100%'; 
    $tabela->border= 1;
    $tabela->style = "border-collapse:collapse";
    
    // instancia uma linha para o cabeçalho 
    $cabecalho = $tabela->addRow();
    
    // adiciona células 
    $cabecalho->addCell('Id'); 
    $cabecalho->addCell('title'); 
   // $cabecalho->addCell('cidade'); 
    //$cabecalho->addCell('Estoque');
    $cabecalho->addCell('Preço Serviço');
     $cabecalho->addCell('Preço Produto');
  //  $cabecalho->addCell('Venda');
    $cabecalho->class = 'cabecalho';
    
    // inicializa variáveis de controle e totalização
    $colore = FALSE;
     //$precoservico += 'precoservico' / 100 * 100;
    $precoservico = 0;
    
    
    ////
        // inicializa variáveis de controle e totalização
    $colore = FALSE;
     $precoservico = 'precoservico';
    $precoservico = 0;
    
    ///
      // percorre os resultados
    foreach ($result as $row)
    {
        // acumula preço de custo e de venda
       // $total_custo += $row['preco_venda'] / 100 * 30;
      //  $total_venda += $row['estoque'] * $row['preco_venda'] /100 *70;
      
       // acumula preço de custo e de venda
      //  $precoservico += $row['precoservico'] / 100 * 30;
      // $precoservico += $row['precoservico'] * $row['precoservico'] /100 *70;
      
            $precoservico += $row['precoservico']  ;
            
        // formata numericamente os preços
        $row['precoservico'] = number_format($row['precoservico'], 2, ',', '.');
        $row['precoproduto'] = number_format($row['precoproduto'], 2, ',', '.');
        
        // verifica qual cor irá utilizar para o fundo 
        $bgcolor = $colore ? '#d0d0d0' : '#ffffff';
        
        // adiciona uma linha para os dados 
        $linha = $tabela->addRow(); 
        $linha->bgcolor = $bgcolor;
        $linha->class = 'dados';
        
        // adiciona as células 
        $cell1 = $linha->addCell($row['id']);
        $cell2 = $linha->addCell($row['title']);
       // $cell3 = $linha->addCell($row['cidade']);
       // $cell4 = $linha->addCell($row['estoque']);
        $cell3 = $linha->addCell($row['precoservico']);
        $cell4 = $linha->addCell($row['precoservico']);
        
        // define o alinhamento das células
        $cell1->align = 'center';
        $cell2->align = 'left';
        //$cell3->align = 'center';
        //$cell4->align = 'right';
        $cell3->align = 'right';
        $cell4->align = 'right';
        
        // inverte cor de fundo
        $colore = !$colore;
    }
    
    // instancia uma linha para o totalizador 
    $total = $tabela->addRow();
    $total->class = 'cabecalho';
    
    // adiciona células 
    $celula= $total->addCell('Total'); 
   // $celula->colspan = 4; 
    $celula->colspan = 2; 
    
    $celula1 = $total->addCell(number_format($precoservico, 2, ',', '.'));
    $celula2 = $total->addCell(number_format($precoservico, 2, ',', '.')); 
    $celula1->align   = "right";
    $celula2->align   = "right";
    
    $tabela->show();
} 
catch (Exception $e) 
{ 
    // exibe a mensagem de erro 
    echo $e->getMessage(); 
} 
?>