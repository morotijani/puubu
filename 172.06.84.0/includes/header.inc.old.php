<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport'>
    <title>Puubu | Dashboard</title>
    <link rel="icon" type="image/png" href="<?= PROOT; ?>media/puubu.favicon.png">

    <link rel="stylesheet" type="text/css"  href="<?= PROOT; ?>172.06.84.0/media/files/bootstrap.css" media="all">
    <link rel="stylesheet" type="text/css"  href="<?= PROOT; ?>172.06.84.0/media/files/admin.css" media="all">
    <link rel="stylesheet" type="text/css" href="<?= PROOT; ?>172.06.84.0/media/files/morris.css" media="all">
    <link rel="stylesheet" type="text/css"  href="<?= PROOT; ?>172.06.84.0/media/files/dashboard.css" media="all">
    <style type="text/css">
        * {
            font-family: monospace;
        }
        @keyframes chartjs-render-animation {
            from {
                opacity: .99
            }

            to {
                opacity: 1
            }
        }

        .chartjs-render-monitor {
            animation: chartjs-render-animation 1ms;
        }
        .chartjs-size-monitor, .chartjs-size-monitor-expand, .chartjs-size-monitor-shrink {
            position:absolute;direction:ltr;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1
        }
        .chartjs-size-monitor-expand > div {
            position:absolute;width:1000000px;height:1000000px;left:0;top:0
        }
        .chartjs-size-monitor-shrink > div {
                position:absolute;width:200%;height:200%;left:0;top:0
        }
    </style>
</head>
<body style="background-color: rgb(70, 60, 54);">