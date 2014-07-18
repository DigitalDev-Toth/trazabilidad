<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>FALP - Trazabilidad</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/raphael-min.js"></script>
        <script src="js/module.js"></script>
        <script src="js/submodule.js"></script>
        <script src="js/maker.js"></script>
        <script type="text/javascript">
            var MODULES = {},
                PATIENTS = [],
                PAPER;
            
            $(function () {

                PAPER = Raphael('workspace', '100%', '100%');
                var w = $(window).width();
                var h = $(window).height();

                PAPER.setViewBox(0, 0, 1920, 997, true);
                PAPER.canvas.setAttribute("preserveAspectRatio", "xMinYMin");
                
                var make = new MAKER();

                $.get("../services/zoneInfo.php?zone=1",function(data, status){
                    if(status === 'success') {
                        console.log(JSON.parse(data));
                    }
                });
                make.module('Sala de espera', 1, 'waiting-room', 'center', '#818878');
                make.module('Informaciones', 100, 'info', 'top', '#8f8', 3);
                make.module('tothem', 101, 'payment', 'left', '#f88', 5);
                make.module('Consultas', 102, 'info', 'bot', '#88f', 18);
                make.module('Admision', 103, 'info', 'right', '#ff8', 7);
                make.module('Facturacion', 104, 'info', 'top-left', '#8ff', 3);
                make.module('Informaciones', 105, 'info', 'top-right', '#8ff', 3);
                make.module('Informaciones', 106, 'info', 'bot-left', '#8ff', 1);
                make.module('Informaciones', 107, 'info', 'bot-right', '#8ff', 1);                
                
                //console.log(MODULES);
            });
        </script>
    </head>
    <body>
        <div id="workspace"></div>
    </body>
</html>
