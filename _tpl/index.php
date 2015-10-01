
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

                    <ul>
                        <li>Height of row indicates number of tasks.</li>
                        <li> Different color of cells indicates different projects.</li>
                        <li> Click cells to go to the task.</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" class="user-col">User</th>
                                <th colspan="<?= ($days+1) ?>"> <?php echo date('F Y', mktime(0,0,0, $month, 1, $year)) ?></th>
                            </tr>
                            <tr>
                                <?php foreach (range(1, $days) as $day): ?>
                                    <th style="width:30px;"><?= str_pad($day, 2, '0', STR_PAD_LEFT) ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr id="row-<?= $user['phid'] ?>">
                                    <td valign="top">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img style="height:20px;" src="<?php echo $user['image'] ?>">
                                            </div>
                                            <div class="col-md-10">
                                                <?= $user['realName'] ?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php foreach (range(1, $days) as $day): ?>
                                        <td <?php if(mktime(0,0,0, $month, $day, $year) == mktime(0,0,0) ): ?>
                                                style="background:#e1e1e1"
                                            <?php endif ?>  class="day day-<?php echo mktime(0,0,0, $month, $day, $year) ?>">
                                        </td>
                                    <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            
        <script type="text/javascript" src="<?= $base_path ?>/assets/plugins/jquery/dist/jquery.js"></script>
        <script type="text/javascript" src="<?= $base_path ?>/assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= $base_path ?>/assets/js/app.js"></script>
        <script type="text/javascript">

            var baseUrl = "<?= $base_path ?>";

            var days = [];
            <?php foreach (range(1, $days) as $day): ?>
                days.push(<?= mktime(0,0,0, $month, $day, $year) ?>);
            <?php endforeach ?>

            App.init(baseUrl, days);
        </script>
    </body>
</html>
