<?php
    /* Initializations */
    require_once '../resources/config.php'; // this loads our specific configuration
    require_once(LIBRARIES_PATH . "/Prismic.php"); // this loads our Prismic helpers, which include the prismic.io PHP kit
    $ctx = Prismic::context(); // getting the context built from our configuration (like the Api object it initializes)
    global $linkResolver; // getting the linkResolver object (more on this later)

    /* Controller */
    try {
        // API calls
        $homepage = Prismic::getDocument($ctx->getApi()->bookmark('homepage'));
        $skills = $ctx->getApi()->forms()->skills->ref($ctx->getRef())->submit();
        $works = $ctx->getApi()->forms()->works->ref($ctx->getRef())->submit();
        $stuffido = $ctx->getApi()->forms()->{"stuff-i-do"}->ref($ctx->getRef())->submit();
        $kindsofwork = $ctx->getApi()->forms()->everything->query('[[:d = at(document.type, "kind-of-work")]]')->ref($ctx->getRef())->submit();
    } catch (Guzzle\Http\Exception\BadResponseException $e) {
        Prismic::handlePrismicException($e); // We need to catch any network issue, and render it properly
    }
?><!DOCTYPE html>
<html lang="en-US" class="no-js">
    <head>

        <!-- ==============================================
        Title and Meta Tags
        =============================================== -->
        <meta charset="utf-8">
        <title>prismic.io's Bootstrap3 example</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- ==============================================
        Favicons
        =============================================== -->
        <link rel="shortcut icon" href="assets/favicon.ico">
        <link rel="apple-touch-icon" href="assets/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/apple-touch-icon-114x114.png">

        <!-- ==============================================
        CSS
        =============================================== -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.css">
        <link rel="stylesheet" href="css/flexslider.css">
        <link rel="stylesheet" href="css/meflat-light-green.css">


        <!-- ==============================================
        Fonts
        =============================================== -->
        <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,300italic,200,400italic,600,600italic' rel='stylesheet' type='text/css'>

        <!-- ==============================================
        JS
        =============================================== -->

        <!--[if lt IE 9]>
            <script src="js/respond.min.js"></script>
        <![endif]-->

        <script type="text/javascript" src="js/libs/modernizr.min.js"></script>


    </head>

    <body data-spy="scroll" data-target="#main-nav" data-offset="400">
        <!-- ==============================================
        MAIN NAV
        =============================================== -->
        <div id="main-nav" class="navbar navbar-fixed-top">
            <div class="container">

                <div class="navbar-header">

                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#site-nav">
                        <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                    </button>

                    <!-- ======= LOGO (for small screens)========-->
                    <a class="navbar-brand visible-xs scrollto" href="#home"><?php echo $homepage->getStructuredText('homepage.website_title')->asText() ?></a>

                </div>

                <div id="site-nav" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="#services" class="scrollto"><?php echo $homepage->getStructuredText('homepage.whatido_title')->asText() ?></a>
                        </li>
                        <li>
                            <a href="#about" class="scrollto"><?php echo $homepage->getStructuredText('homepage.about_title')->asText() ?></a>
                        </li>
                        <li id="logo">
                            <a href="#home" class="scrollto">
                                <h1><?php echo substr($homepage->getStructuredText('homepage.website_title')->asText(), 0, 1) ?><span><?php echo substr($homepage->getStructuredText('homepage.website_title')->asText(), 1) ?></span></h1>
                            </a>
                        </li>
                        <li>
                            <a href="#portfolio" class="scrollto"><?php echo $homepage->getStructuredText('homepage.works_title')->asText() ?></a>
                        </li>
                        <li>
                            <a href="#contact" class="scrollto"><?php echo $homepage->getStructuredText('homepage.contact_title')->asText() ?></a>
                        </li>
                    </ul>
                </div><!--End navbar-collapse -->

            </div><!--End container -->

        </div><!--End main-nav -->

        <!-- ==============================================
        HEADER
        =============================================== -->
        <header id="home" class="jumbotron">

            <div class="container">

                <div class="row">

                    <div class="col-sm-6 text-col">

                        <h1><?php echo $homepage->getStructuredText('homepage.page_title')->asText() ?></h1>
                        <?php echo $homepage->getStructuredText('homepage.page_lede')->asHtml($linkResolver) ?>

                    </div>

                    <div class="col-sm-6">
                        <div class="imac-frame">
                            <img class="img-responsive img-center" src="assets/imac.png" alt=""/>
                            <div class="imac-screen flexslider">
                                <ul class="slides">
                  <?php
                    // looping through the "Group" fragment of the carrousel images on the homepage
                    foreach ($homepage->getGroup('homepage.introduction_carrousel')->getArray() as $elem) {
                      echo '<li>';
                      echo $elem['image']->getView('regular')->asHtml($linkResolver);
                      echo '</li>';
                    }
                  ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </header><!--End header -->

        <!-- ==============================================
        SERVICES
        =============================================== -->
        <section id="services" class="white-bg padding-top-bottom">

            <div class="container">

                <header class="section-header text-center">

                    <h1 class="scrollimation scale-in"><?php echo $homepage->getStructuredText('homepage.whatido_title')->asText() ?></h1>
                    <?php echo $homepage->getStructuredText('homepage.whatido_lede')->asHtml($linkResolver) ?>

                </header>

                <div class="row services">

          <?php
            // looping on "Stuff I Do" documents
            for($i = 0 ; $i < count($stuffido) ; ++$i) {
              $delay = ($i==0) ? '' : ' d'.$i; // to put a nice different delay on each one
              echo '<div class="col-md-3 col-sm-6 item scrollimation fade-up'.$delay.'">';
              echo '  <div class="icon">';
              echo '    <img class="img-responsive img-center" src="'.$stuffido[$i]->getImageView('stuff-i-do.image', 'regular')->getUrl().'" alt="">';
              echo '  </div>';
              echo '  <h2>'.$stuffido[$i]->getStructuredText('stuff-i-do.title')->asText().'</h2>';
              echo $stuffido[$i]->getStructuredText('stuff-i-do.lede')->asHtml($linkResolver);
              echo '</div>';
            }
          ?>

                </div>

            </div>

        </section>

        <!-- ==============================================
        FEATURED PROJECT
        =============================================== -->
        <section id="feat-project" class="gray-bg padding-top">

            <div class="container">

                <header class="section-header text-center">

                    <h1 class="scrollimation scale-in"><?php echo $homepage->getStructuredText('homepage.featured_title')->asText() ?></h1>
                    <?php echo $homepage->getStructuredText('homepage.featured_lede')->asHtml($linkResolver) ?>

                </header>

                <div class="scrollimation fade-up">
                    <img class="img-responsive img-center" src="assets/chrome-top.png" alt="" />

                    <div class="img-wrapper">

                        <img class="img-responsive img-center" src="<?php echo $homepage->getImageView('homepage.featured_image', 'regular')->getUrl(); ?>" alt="" />

                        <p class="text-center on-hover"><a class="btn btn-meflat icon-right" href="<?php echo $homepage->get('homepage.featured_link')->getUrl(); ?>" target="_blank">Visit Website<i class="fa fa-arrow-right"></i></a></p>

                    </div>
                </div>

            </div>

        </section>


        <!-- ==============================================
        ABOUT
        =============================================== -->
        <section id="about" class="dark-bg light-typo padding-top-bottom">

            <div class="container">

                <header class="section-header text-center">

                    <h1 class="scrollimation scale-in"><?php echo $homepage->getStructuredText('homepage.about_title')->asText() ?></h1>

                </header>

                <div class="row">

                    <div class="col-sm-8 col-sm-offset-2">

                        <img class="img-responsive img-center img-circle scrollimation fade-left" src="<?php echo $homepage->getImageView('homepage.about_photo', 'regular')->getUrl() ?>" alt="" />

                        <p class="text-center scrollimation fade-in"><?php echo $homepage->getStructuredText('homepage.about_lede')->asText() ?></p>

                    </div>

                </div>



                <p class="text-center"><a class="btn btn-meflat scrollto white icon-left" href="#contact"><i class="fa fa-arrow-down"></i><?php echo $homepage->getText('homepage.about_cta_label') ?></a></p>

            </div>

        </section>
        <!-- ==============================================
        SKILLS
        =============================================== -->
        <section id="skills" class="white-bg">

            <div class="container">

                <div class="row skills">

                    <h1 class="text-center scrollimation fade-in"><?php echo $homepage->getStructuredText('homepage.skills_title')->asText() ?></h1>

          <?php
            // looping through "Skill" documents
            foreach($skills as $skill) {
              echo '<div class="col-sm-6 col-md-3 text-center">';
              echo '  <span class="chart" data-percent="'.$skill->getText('skills.amount').'"><span class="percent">'.$skill->getText('skills.amount').'</span></span>';
              echo '  <h2 class="text-center">'.$skill->getStructuredText('skills.title')->asText().'</h2>';
              echo '</div>';
            }
          ?>

                </div><!--End row -->

            </div>


        </section>
        <!-- ==============================================
        PORTFOLIO
        =============================================== -->
        <section id="portfolio" class="gray-bg padding-top-bottom">

            <div class="container">

                <header class="section-header text-center">

                    <h1 class="scrollimation scale-in">My Works</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br/> Aenean purus felis, condimentum et tempor in, commodo id nibh. Fusce a lacus arcu.</p>

                </header>

                <!--==== Portfolio Filters ====-->
                <div id="filter-works">
                    <ul>
                        <li class="active scrollimation fade-right d1">
                            <a href="#" data-filter="*">All</a>
                        </li>
            <?php
              foreach($kindsofwork as $kindofwork) {
                echo '<li class="scrollimation fade-left">';
                // cool stuff: I can use the "kind of work" document's slug as the CSS class to filter on
                echo '  <a href="#" data-filter=".'.$kindofwork->getSlug().'">'.$kindofwork->getStructuredText('kind-of-work.name')->asText().'</a>';
                echo '</li>';
              }
            ?>
                    </ul>
                </div><!--End portfolio filters -->

            </div><!--End portfolio header -->

            <div class="container masonry-wrapper scrollimation fade-in">

                <div id="projects-container">

          <?php foreach($works as $work) { ?>

                    <article class="project-item <?php echo $work->get('work.kind')->getSlug() ?>">

                        <img class="img-responsive project-image" src="<?php echo $work->getImageView('work.image', 'regular')->getUrl() ?>"  alt=""><!--Project thumb -->

                        <div class="hover-mask">
                            <h2 class="project-title"><?php echo $work->getStructuredText('work.title')->asText() ?></h2><!--Project Title -->
                            <p><?php echo $work->getStructuredText('work.subtitle')->asText() ?></p><!--Project Subtitle -->
                        </div>

                        <!--==== Project Preview HTML ====-->

                        <div class="sr-only project-description"
                            data-category="<?php echo $work->get('work.kind')->getSlug() ?>"
                            data-date="<?php echo $work->getText('work.date') ?>"
                            data-client="<?php echo $work->getText('work.client') ?>"
                <?php $link = $work->get('work.link')->getUrl() // just because we'll have to repeat it twice right after ?>
                            data-link="<?php echo $link ?>,<?php echo $link ?>"
                            data-descr="<?php echo $work->getStructuredText('work.small_description')->asText() ?>"
                <?php
                  // building the comma-separated list of the URLs of the images in the carrousel
                  $bigimagelist = [];
                  foreach($work->getGroup('work.carrousel')->getArray() as $bigimage) {
                    array_push($bigimagelist, $bigimage['image']->getView('regular')->getUrl());
                  }
                ?>
                            data-images="<?php echo implode(',', $bigimagelist) ?>"
                        >
                            <?php echo $work->getStructuredText('work.description')->asHtml($linkResolver) ?>
                            <p class="text-right"><a class="btn btn-meflat icon-right" href="<?php echo $link ?>" target="_blank">Visit Website<i class="fa fa-arrow-right"></i></a></p>
                        </div>

                    </article>

          <?php } ?>

                </div><!-- End projects -->

            </div><!-- End container -->

            <!-- ==============================================
            PROJECT PREVIEW MODAL (Do not alter this markup)
            =============================================== -->
            <div id="project-modal" class="modal fade">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <div class="container">

                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h1 id="hdr-title" class="text-center"></h1>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="image-wrapper">
                                            <img class="img-responsive" src="assets/chrome.png" alt="">
                                            <div class="loader"></div>
                                            <div class="screen"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div id="project-sidebar" class="col-md-3">
                                        <h2 id="sdbr-title"></h2>
                                        <p id="sdbr-category"></p>
                                        <p id="sdbr-date"></p>
                                        <p id="sdbr-client"></p>
                                        <p id="sdbr-link"><a href="#link" target="_blank"></a></p>
                                        <p id="sdbr-descr"></p>
                                    </div>
                                    <div id="project-content" class="col-md-8 col-md-offset-1">
                                    </div>
                                </div>

                            </div>
                        </div><!-- End modal-body -->

                    </div><!-- End modal-content -->

                </div><!-- End modal-dialog -->

            </div><!-- End modal -->

        </section>
        <!-- ==============================================
        CONTACT
        =============================================== -->
        <section id="contact" class="dark-bg light-typo padding-top">

            <div class="container">

                <header class="section-header text-center">

                    <h1 class="scrollimation scale-in"><?php echo $homepage->getStructuredText('homepage.contact_title')->asText() ?></h1>
                    <?php echo $homepage->getStructuredText('homepage.contact_lede')->asHtml($linkResolver) ?>

                </header>

                <form  id="contact-form" class="bl_form text-center" action="contact.php" method="post" novalidate>
                    <span class="field-wrap scrollimation fade-right">
                        <label class="control-label" for="contact-name">Name</label>
                        <input id="contact-name" name="contactName" type="text" class="label_better requiredField" data-new-placeholder="Name" placeholder="Name" data-error-empty="*Enter your name">
                    </span>
                    <span class="field-wrap scrollimation fade-in">
                        <label class="control-label" for="contact-mail">Email</label>
                        <input id="contact-mail" name="email" type="email" class="label_better requiredField" data-new-placeholder="Email Address" placeholder="Email Address" data-error-empty="*Enter your email" data-error-invalid="x Invalid email address">
                    </span>
                    <span class="field-wrap scrollimation fade-left">
                        <label class="control-label" for="contact-message">Message</label>
                        <textarea id="contact-message" name="comments" rows="1" class="label_better requiredField" data-new-placeholder="Message" placeholder="Message" data-error-empty="*Enter your message"></textarea>
                    </span>

                    <p class="text-center"><button  name="submit" type="submit" class="btn btn-meflat icon-left" data-error-message="Error!" data-sending-message="Sending..." data-ok-message="Message Sent"><i class="fa fa-location-arrow"></i>Send Message</button></p>
                    <input type="hidden" name="submitted" id="submitted" value="true" />

                </form>

            </div>

        </section>

        <!-- ==============================================
        FOOTER
        =============================================== -->

        <footer id="main-footer" class="dark-bg light-typo">

            <div class="container">

                <hr>

                <div class="row">

                    <div class="col-sm-6">
                        <ul class="social-links">
              <?php
                // we check that the fragment exists before echoing the link and image; that way, if you're not on Facebook, the Facebook link doesn't show, for instance
                if ($homepage->get('homepage.twitter')) {
                  echo '<li class="scrollimation fade-right d4"><a target="_blank" href="'.$homepage->get('homepage.twitter')->getUrl().'"><i class="fa fa-twitter fa-fw"></i></a></li>';
                }
                if ($homepage->get('homepage.facebook')) {
                  echo '<li class="scrollimation fade-right d3"><a target="_blank" href="'.$homepage->get('homepage.facebook')->getUrl().'"><i class="fa fa-facebook fa-fw"></i></a></li>';
                }
                if ($homepage->get('homepage.google_plus')) {
                  echo '<li class="scrollimation fade-right d2"><a target="_blank" href="'.$homepage->get('homepage.google_plus')->getUrl().'"><i class="fa fa-google-plus fa-fw"></i></a></li>';
                }
                if ($homepage->get('homepage.dribbble')) {
                  echo '<li class="scrollimation fade-right d1"><a target="_blank" href="'.$homepage->get('homepage.dribbble')->getUrl().'"><i class="fa fa-dribbble fa-fw"></i></a></li>';
                }
                if ($homepage->get('homepage.linkedin')) {
                  echo '<li class="scrollimation fade-right"><a target="_blank" href="'.$homepage->get('homepage.linkedin')->getUrl().'"><i class="fa fa-linkedin fa-fw"></i></a></li>';
                }
              ?>
                        </ul>
                    </div>

                </div>

            </div>

        </footer>


        <!-- ==============================================
        SCRIPTS
        =============================================== -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/libs/jquery-1.8.2.min.js">\x3C/script>')</script>

        <script src="js/libs/bootstrap.min.js"></script>
        <script src='js/jquery.easing.1.3.min.js'></script>
        <script src='js/jquery.scrollto.js'></script>
        <script src="js/jquery.fittext.js"></script>
        <script src='js/jquery.flexslider.min.js'></script>
        <script src='js/jquery.masonry.js'></script>
        <script src='js/twitterFetcher_v10_min.js'></script>
        <script src="js/waypoints.min.js"></script>
        <script src="js/jquery.label_better.min.js"></script>
        <script src="js/jquery.easypiechart.js"></script>
        <script src="js/grid.js"></script>
        <script src="js/contact.js"></script>
        <script src="js/meflat.js"></script>

    </body>

</html>
