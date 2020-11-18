<!DOCTYPE html>
<?php
session_start();
include "default/conecta.php";
if ($_SESSION['COD_USER'] == 43){//verifica se é o admin que está logado
?>
<html style="overflow-y: hidden;">
<meta charset="utf-8">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <title>Dashboard</title>
<!-- Início dos links externos -->
<?php
include "default/bootstrap.php";
?>
<link rel="stylesheet" type="text/css" href="CSS/css.css">
<!-- Fim dos links externos -->
</head>
<body>
<!-- Início do header -->
<?php
include 'default/header.php';
?>
<!-- Fim do header -->
<div class="row">
<div class="col-md-8">
    <div class="row">
    <div class="col-md-4">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
        <?php
            $query = "SELECT SUM(qnt_compra_com) AS comprados FROM comprados";
            if ($result = $mysqli->query($query)) {
                while ($obj = $result->fetch_object()) {
                    printf ("<center><h1>%s</h1></center>", $obj->comprados);
                }
                $result->close();
            }
        ?>
        </div>
        <div class="col-md-1"></div>
    </div>
    </div>
    <div class="col-md-4">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
        <?php
            $query = "SELECT SUM(qnt_compra_com)*vl_shop AS comprados FROM comprados, shopin WHERE cd_shop = id_prod_com GROUP BY id_prod_com";
            if ($result = $mysqli->query($query)) {
                while ($obj = $result->fetch_object()) {
                    $resultado += $obj->comprados;
                }
                echo "<center><h1>R$ ".$resultado."</h1></center>";
                $result->close();
            }
        ?>
        </div>
        <div class="col-md-1"></div>
    </div>   
    </div>
    <div class="col-md-4">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
        <?php
            $query = "SELECT *, SUM(qnt_compra_com) AS comprados FROM comprados, shopin WHERE cd_shop = id_prod_com GROUP BY id_prod_com DESC LIMIT 1";
            if ($result = $mysqli->query($query)) {
                while ($obj = $result->fetch_object()) {
                    printf ("<center><h1>%s</h1></center>", $obj->nm_shop);
                }
                $result->close();
            }
        ?>
        </div>
        <div class="col-md-1"></div>
    </div> 
    </div>
    </div>
    <div class="row">
    <?php
        $id = $_POST['id'];
        $qnt = $_POST['qnt'];
        foreach ($id as $v => $code) {
            if ($result = $mysqli->query("SELECT * FROM cart WHERE cart_user = '".$_SESSION['COD_USER']."'")) {
                $row_cnt = $result->num_rows;
                if ($row_cnt == 0) {
                    echo "CCCCCCCCCCCCC"; 
                }
                else {
                    $query = "UPDATE cart SET qnt_cart = '".$qnt[$v]."' WHERE prod = '".$code."' AND cart_user = '".$_SESSION['COD_USER']."'"; //verifica se os dados do login conferem
                    if ($result = $mysqli->query($query)){
                    }
                }
            }
            else {
                echo "BBBBBBBBBBBBBBBBB".$_POST['qnt'];
            }
        }
        $query = "SELECT * FROM comprados, shopin WHERE cd_shop = id_prod_com GROUP BY id_prod_com ASC"; //verifica se os dados do login conferem
        if ($result = $mysqli->query($query)){
            while ($obj = $result->fetch_object()){
                $cd[] = $obj->cd_shop;
                $qnt[] = $obj->qnt_compra_com;
            }
            foreach ($cd as $v => $code) {   
                echo "array('x'=> '".$code."', 'y'=> '".$qnt[$v]."'),";
            }
        }
        $array[1] = array("x"=> 10, "y"=> 51);
        $array[2] = array("x"=> 20, "y"=> 35);
        $dataPoints = array(
    	    $array[1],
	        $array[2],
	        array("x"=> 30, "y"=> 50),
	        array("x"=> 40, "y"=> 45),
	        array("x"=> 50, "y"=> 52),
	        array("x"=> 60, "y"=> 68),
	        array("x"=> 70, "y"=> 38),
	        array("x"=> 80, "y"=> 71),
	        array("x"=> 90, "y"=> 52),
	        array("x"=> 100, "y"=> 60),
	        array("x"=> 110, "y"=> 36),
	        array("x"=> 120, "y"=> 49),
	        array("x"=> 130, "y"=> 41)
	   );
    ?>
    <script>
    window.onload = function () {   
    var chart = new CanvasJS.Chart("chartContainer", {
	    animationEnabled: true,
	    exportEnabled: true,
	    theme: "light1", // "light1", "light2", "dark1", "dark2"
	    title:{
		    text: "Gráfico Foda"
	    },
	    axisY:{
		    includeZero: true
	    },
	    data: [{
		    type: "column", //change type to bar, line, area, pie, etc
		    //indexLabel: "{y}", //Shows y value on all Data Points
		    indexLabelFontColor: "#5A5757",
		    indexLabelPlacement: "outside",   
		    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	    }]
    });
    chart.render();
    }
    </script>
    <div id="chartContainer" style="height: 500px; width: 100%; float: bottom;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </div>
    </div>
    <div class="col-md-4" id="scroll" style="padding-right: 50px; height: 665px;">
    <?php
        $query = "SELECT * FROM comprados, shopin WHERE id_prod_com = cd_shop";
        if ($result = $mysqli->query($query)) {
            while ($obj = $result->fetch_object()) {
                printf ("<div class='row'>
                        <div class='col-md-6'>
      			        Código da compra: %s<br> 
      			        Nome: %s<br> 
      			        Telefone: %s<br> 
      			        Endereço: %s, %s<br> 
      			        Comentário adicional: %s
      			        </div>
      			        <div class='col-md-6'>
      			        Código do produto: %s<br>
      			        Nome do produto: %s<br>
      			        Quantidade: %s
      		            </div></div>
      		            <hr>", $obj->cd_compra, $obj->nome, $obj->telefone, $obj->endereço, $obj->endereco_num, $obj->comment, $obj->id_prod_com, $obj->nm_shop, $obj->qnt_compra_com);
            }
            $result->close();
        }
    }
    else{
        echo "<center><h1> Você não deveria estar aqui! </h1><br> Retorne para o <a href='index.php'>site</a></center>";
    }
    ?>
</div>
</div>
<style type="text/css">
div#scroll { 
    margin:4px, 4px; 
    padding:4px;  
    width: 500px; 
    height: 61.5vh; 
    overflow-x: hidden; 
    overflow-x: auto;
    overflow-x: hidden; 
    overflow-x: none;
    text-align:justify; 
}
.col-md-10{
    background-color: lightgray;
    padding-top: 5%;
    padding-bottom: 5%;
}
hr{
	width: 100%;
	height: 1px;
	background-color: lightgray;
}
</style>
</body>
</html>