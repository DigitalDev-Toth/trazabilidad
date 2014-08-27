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
    <script type="text/javascript">

        $(document).ready(function() {
            getZone();
            getModule($("#listZones").jqxDropDownList("getSelectedItem").value);
            getSubModule($("#listModules").jqxDropDownList("getSelectedItem").value);
        });

        function getZone(){
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'id' },
                    { name: 'name' }
                ],
                url: "phps/getSelectors.php?type=zone&userId=9",
                async: false
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#listZones").jqxDropDownList({selectedIndex: 0, source: dataAdapter, displayMember: "name", valueMember: "id", width: 200, height: 25});
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
                url: "phps/getSelectors.php?type=module&zone="+zone+"&userId=9",
                async: false
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#listModules").jqxDropDownList({selectedIndex: 0, source: dataAdapter, displayMember: "name", valueMember: "id", width: 200, height: 25});
            $("#listModules").on('select', function (event) {
                getSubModule(event.args.item.value);//Id Zona
            });
        }

        function getSubModule(module){
            console.log("phps/getSelectors.php?type=submodule&module="+module+"&userId=9");
            var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'id' },
                    { name: 'name' }
                ],
                url: "phps/getSelectors.php?type=submodule&module="+module+"&userId=9",
                async: false
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            $("#listSubModules").jqxDropDownList({selectedIndex: 0, source: dataAdapter, displayMember: "name", valueMember: "id", width: 200, height: 25});
        }
    </script>
</head>
<body>
    <div>Seleccione zona, módulo y submódulo en que operará</div>
    <br/>
    <span>ZONA: </span>
    <br/>
    <div id="listZones"></div>
    <br/>
    <span>MÓDULO: </span>
    <br/>
    <div id="listModules"></div>
    <br/>
    <span>SUB-MÓDULO: </span>
    <br/>
    <div id="listSubModules"></div>
</body>
</html>