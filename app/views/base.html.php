<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="Foursquare tutorial">
    <meta name="viewport" content="width=device-width">
    
    <title><?php $view['slots']->output('title', 'PPIv2 - Foursquare Tutorial') ?></title>
    
    <!-- CSS Stuff -->
    <link href="<?=$view['assets']->getUrl('css/libs/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?=$view['assets']->getUrl('css/main.css');?>" rel="stylesheet">
    <?php $view['slots']->output('include_css'); ?>
    <!-- /CSS Stuff -->
    
    <!-- JS Head Stuff -->
    <script src="<?=$view['assets']->getUrl('js/libs/modernizr-2.5.3.min.js');?>"></script>
    <script type="text/javascript">
        var ppi = {
            baseUrl: '<?=$view['router']->generate('Homepage');?>'
        }
    </script>
    <?php $view['slots']->output('include_js_head'); ?>
    <!-- /JS Head Stuff -->
    
</head>

<body>

    <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6. -->
    <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

    <div class="container" id="page-content">
        
        <!-- Begin Flash Message Injection -->
        <?php
        $flashNames = array(
            'info' => 'info',
            'success' => 'success',
            'error' => 'error',
            'warning' => 'block',
            'notice' => 'block'
        );
        $flashHeadings = array(
            'info' => 'Atención!',
            'error' => 'Oops!',
            'success' => 'Bien hecho!',
            'block' => 'Precaución!'
        );
        
        if($view['session']->hasFlashes()):
        ?>
        <div class="flashes">
        <?php
            foreach($view['session']->getFlashes() as $flashName => $flashes):
                $alertClass = isset($flashNames[$flashName]) ? $flashNames[$flashName] : 'info';
                foreach($flashes as $flash):
        ?>
                <div class="alert alert-<?=$alertClass?>">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="icon-info-sign"></i>
                    <strong class="alert-heading"><?=$flashHeadings[$alertClass];?></strong>
                    <span><?=$flash;?></span>
                </div>    
        <?php
                    endforeach;
            endforeach;
        ?>
        </div>
        <!-- End of Flash Message Injection -->
        <?php
        endif;
        ?>
        
        <!-- Begin dynamic page output -->
        <div id="action-content">
        <?php $view['slots']->output('_content'); ?>
        </div>
        <!-- End dynamic page output -->
        
    </div>

    <footer>
        (cc) 2013. PPI Framework v2. Developed by Alfredo Juarez <a href="http://twitter.com/alfrekjv">@alfrekjv</a>
        <br>
        <small>Use it, hack it and learn from it.</small>
    </footer>
    
    <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?=$view['assets']->getUrl('js/libs/jquery-1.8.0.min.js');?>"><\/script>')</script>
    
    <!-- JS Body Stuff -->
    <script src="<?=$view['assets']->getUrl('js/libs/bootstrap.min.js');?>"></script>
    <?php $view['slots']->output('include_js_body'); ?>
    <!-- /JS Body Stuff -->

</body>
</html>
