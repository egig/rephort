
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" type="text/css" href="<?= $base_path ?>/assets/plugins/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?= $base_path ?>/assets/css/style.css">
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Tasks Assignment Diagram</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="chart-container"></div>
                </div>
            </div>
        </div>
            
        <script type="text/javascript" src="<?= $base_path ?>/assets/plugins/jquery/dist/jquery.js"></script>
        <script type="text/javascript" src="<?= $base_path ?>/assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= $base_path ?>/assets/plugins/highcharts/highcharts.js"></script>
        <script type="text/javascript" src="<?= $base_path ?>/assets/plugins/highcharts/highcharts-more.js"></script>
        <script type="text/javascript">
            $(function () {

                $('#chart-container').highcharts({

                    chart: {
                        type: 'columnrange',
                        inverted: true,
                        zoomType: 'y'
                    },

                    title: {
                        text: 'Task'
                    },

                    xAxis: {
                        categories: <?php echo json_encode($categories); ?>
                    },

                    yAxis: {
                        title: {
                            text: 'Waktu'
                        },
                        tipe: 'datetime',
                        labels: {
                            formatter: function() {
                                var d = new Date(this.value);

                                return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear()+' '+d.getHours()+':'+d.getMinutes();
                            }
                        },
                    },

                    tooltip: {
                        formatter: function() {

                            function format(time) {

                                var d = new Date(time);
                                return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear()+' '+d.getHours()+':'+d.getMinutes();
                            }

                            return this.x+' '+format(this.point.low)+' s/d '+format(this.point.low);
                        }
                    },

                    legend: {
                        enabled: false
                    },

                    series: [{
                        name: 'Waktu',
                        data: <?php echo json_encode($data); ?>,
                        pointWidth: 20
                    }]

                });

            });
        </script>
    </body>
</html>
