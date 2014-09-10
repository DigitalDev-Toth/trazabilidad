<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php"); header('Content-Type: text/html; charset=utf8');  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Selector</title>
	<script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jqwidgets/jqwidgets/jqx-all.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/2-col-portfolio.css" rel="stylesheet">
    <link href="js/jqwidgets/jqwidgets/styles/jqx.base.css" rel="stylesheet">
    <style type="text/css">
        #selectBox {
            margin: 15% auto;
            width: 500px;
        }
    </style>
    <script type="text/javascript">

        var userId = '<?php echo $_SESSION["UserId"]; ?>';
        
        $(document).ready(function() {
            getZone();
            getModule($("#listZones").jqxDropDownList("getSelectedItem").value);
            getSubModule($("#listModules").jqxDropDownList("getSelectedItem").value);

            $("#activeModule").click(function (e) {
                var submodule = $("#listSubModules").jqxDropDownList('getSelectedItem').value;
                console.log(submodule);
                if(submodule!=null){
                    $.post('phps/activeSubModule.php', {type: 'activo', user: userId, submodule: submodule}, function(data, textStatus, xhr) {
                        $.ajax({
                            url: '../../../visor/comet/backend.php',
                            type: 'GET',
                            dataType: 'default',
                            data: {msg: data},
                        });

                        $(location).attr('href','index.php?id='+submodule);
                    });
                }else{
                    alert("No ha seleccionado sub-módulo");
                }
            });
        });

        function getZone(){
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'id' },
                    { name: 'name' }
                ],
                url: "phps/getSelectors.php?type=zone&userId="+userId,
                async: false
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#listZones").jqxDropDownList({selectedIndex: 0, source: dataAdapter, displayMember: "name", valueMember: "id", width: 200, height: 30, autoDropDownHeight: true});
            $("#listZones").on('select', function (event) {
                getModule(event.args.item.value);//Id Zona
            });
        }

        function getModule(zone){
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'id' },
                    { name: 'name' }
                ],
                url: "phps/getSelectors.php?type=module&zone="+zone+"&userId="+userId,
                async: false
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#listModules").jqxDropDownList({selectedIndex: 0, source: dataAdapter, displayMember: "name", valueMember: "id", width: 200, height: 30, autoDropDownHeight: true});
            $("#listModules").on('select', function (event) {
                getSubModule(event.args.item.value);//Id Zona
            });
        }

        function getSubModule(module){
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'id' },
                    { name: 'name' }
                ],
                url: "phps/getSelectors.php?type=submodule&module="+module+"&userId="+userId,
                async: false
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#listSubModules").jqxDropDownList({selectedIndex: 0, source: dataAdapter, displayMember: "name", valueMember: "id", width: 200, height: 30, autoDropDownHeight: true});
        }


    </script>
</head>
<body>
<div class="container">
    <div class="row text-center">
        <br>
        <div class="panel panel-primary" id="selectBox">
            <div class="panel-heading">
                <h3 class="panel-title ">Fundación Arturo Lopez Rodriguez ~ FALP</h3>
            </div>
            <div class="panel-body">
                <p class="text-center">Seleccione zona, módulo y submódulo en que operará</p>
                <hr>
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="lisZones" class="col-sm-2 control-label">Zona:</label>
                        <div class="col-sm-10">
                            <div id="listZones"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="listModules" class="col-sm-2 control-label">Módulo:</label>
                        <div class="col-sm-10">
                            <div id="listModules"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="listSubModules" class="col-sm-2 control-label">Sub-Módulo:</label>
                        <div class="col-sm-10">
                            <div id="listSubModules"></div>
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="row">
                                <div class="col-md-3">                
                                    <input type="button"  class="btn btn-primary" id="activeModule" value="ACEPTAR"></input>
                                </div>
                                <div class="col-md-3">
                                    <a href="../../exit.php" class="btn btn-default">SALIR</a>  
                                </div>
                            </div>               
                        </div>
                    </div>
                </form>
                <br/>
            </div>
        </div>
    </div>
</div>

</body>
</html>