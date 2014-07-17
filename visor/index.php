<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>FALP - Trazabilidad</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
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

                PAPER.setViewBox(0, 0, w, h, true);
                PAPER.canvas.setAttribute("preserveAspectRatio", "xMinYMin");
                
                var make = new MAKER();
                make.module('Sala de espera', 1, 'waiting-room');
                make.module('Informaciones', 100, 'info', 'top', '#fff', 20);
                make.module('Informaciones', 101, 'payment', 'left', '#fff', 1);
                make.module('Informaciones', 102, 'info', 'bot', '#fff', 18);
                make.module('Informaciones', 103, 'info', 'right', '#fff', 7);
                make.module('Informaciones', 104, 'info', 'top-left', '#fff', 1);
//                make.module('Informaciones', 105, 'info', 'top-right');
//                make.module('Informaciones', 106, 'info', 'bot-left');
//                make.module('Informaciones', 107, 'info', 'bot-right');                
                
                console.log(MODULES);
            });
        </script>
    </head>
    <body>
        <div id="workspace"></div>
    </body>
</html>
