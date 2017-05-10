<?php session_start(); ?>
<html>
    <head>
      <meta charset="utf-8">
      <meta httpequiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <title>Computer Vision Face Detection Pipeline</title>

      <link href="favicon.ico" rel="icon" type="image/x-icon" />

      <!-- Bootstrap CSS -->
      <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

      <!-- Bootstrap Fonts -->


      <!-- Extra CSS -->


      <!-- Custom CSS -->
      <link href="css/view.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    </head>
    <?php if(isset($_SESSION['username'])){?>
        <body onload="createVid()">
    <?php }else{ ?>
        <body>
        <?php } ?>
      <!-- Navbar -->
      <!-- TODOS: Add auto-scrolling feature, finish profile link, add links once page is further designed, test for visual design, separate navbar and other common html code and import when necessary(reusability) -->
      <nav id="navbar" class="navbar navbar-toggleable-md navbar-inverse" role="navigation">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
       <font color ="#ff1493"> <a class="navbar-brand" href="#">5 GUYS 1 CODE</a> </font>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active disabled"> <!-- First item active by default, unfinished so also disabled (ironic). Add main page link, and auto-scroll functionality -->
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span><!-- Used for accessibility (sr stands for screen reader, used for blind/visually impaired individuals) --></a>
            </li>
            <li class="nav-item disabled"> <!-- Not yet finished, therefor disabled for now. Add link to scroll to, and auto-scroll functionality -->
              <a class="nav-link" href="#">Videos</a>
            </li>
            <li class="nav-item"> <!-- Not yet finished, therefor disabled for now. Add link to scroll to, and auto-scroll functionality -->
                <a class="nav-link" data-toggle="modal" data-target="#uploadForm" href="#">Upload</a>
            </li>
            <!--<li class="nav-item disabled"> NOT IMPLEMENTED AND NEEDS FURTHER DESIGN WORK; NEED METHOD FOR DETERMING USER LOGIN STATUS, AND TOGGLE VIIBILITY WHEN LOGGED IN (OFF FOR NEW USERS< ON FOR LOGGED IN USERS)
              <a class="nav-link" href="#">Profile</a>
            </li> -->
              <?php if(!isset($_SESSION['username'])){?>
              <li class="nav-item"> <!-- Not yet implemented, therefor disabled for now. Create popup form for account management -->
                  <a class="nav-link" data-toggle="modal" data-target="#logForm" href="#">Login or Sign Up</a>
              </li>
              <?php }?>
              <?php if(isset($_SESSION['username'])){?>
              <li class="nav-item">
                  <a class="nav-link" href="" id="logOff">Logout</a>
              </li>
              <?php }?>
          </ul>
        </div>
      </nav>

      <!-- Login Form -->
      <div class="modal hide fade in" id="logForm" tabindex="-1">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title">Log In</h1>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                      <form class="form-horizontal" role="form" method="" action="" id="loginForm">
                          <div class="form-group">
                              <label for="userr">Username:</label>
                              <input type="text" class="form-control" name="userr" placeholder="Enter Username" id="userr">
                          </div>
                          <div class="form-group">
                              <label for="passs">Password:</label>
                              <input type="password" class="form-control" name="passs" placeholder="Enter Password" id="passs">
                          </div>
                          <div class="form-group">
                              <div class="checkbox">
                                  <label><input type="checkbox"> Keep me logged in</label>
                              </div>
                          </div>
                          <button type="submit" class="btn btn-default col-sm-offset-2" id="loginSubmit">Login</button>
                      </form>
                      <!--Login information output is pumped here-->
                      <div class="text-right" id="loginOutput"></div>
                  </div>
                  <div class="modal-footer">
                      <p>Need an account?</p>
                      <button class="btn btn-primary" data-toggle="modal" data-target="#signForm" data-dismiss="modal">Sign Up</button>
                  </div>
              </div>
          </div>
      </div>

      <!-- Signup Form -->
      <div class="modal hide fade in" id="signForm" tabindex="-1">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title">Sign Up</h1>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                      <form class="form-horizontal" role="form" method="" action="php-functions/user.php" id="signUpForm">
                          <div class="form-group">
                              <label for="fname">First Name:</label>
                              <input type="text" class="form-control" name="fname"  placeholder="Enter First Name" id="fname">
                          </div>
                          <div class="form-group">
                              <label for="lname">Last Name:</label>
                              <input type="text" class="form-control" name="lname" placeholder="Enter Last Name" id="lname">
                          </div>
                          <div class="form-group">
                              <label for="uname">Username:</label>
                              <input type="text" class="form-control" name="uname" placeholder="Enter Desired Username" id="uname">
                          </div>
                          <div class="form-group">
                              <label for="pas">Password:</label>
                              <input type="password" class="form-control" name="pas" placeholder="Enter Desired Password" id="pas">
                          </div>
                          <div class="form-group">
                              <label for="repas">Repeat Password:</label>
                              <input type="password" class="form-control" name="repas" placeholder="Repeat Password" id="repas">
                          </div>
                          <div class="form-group">
                              <div class="g-recaptcha" data-sitekey="6LejPCAUAAAAAIUohV4ruRvyb5Ci-b9O2ys8nX68"></div>
                          </div>
                          <button type="submit" name="insert" class="btn btn-default col-sm-offset-2" id="signUpSubmit">Sign Up</button>
                      </form>
                      <div class="text-right" id="signUpOutput">
                      </div>
                  </div>
              </div>
          </div>
      </div>

        <!-- Upload Form -->
      <div class="modal hide fade in" id="uploadForm" tabindex="-1">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title">File Upload</h1>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                      <?php if(isset($_SESSION['username'])){?>
                      <div id="show">
                          <div class="col text-black" id="instructions">
                              <h2 class="text-center">Upload Rules</h2><br>
                              <ol>
                                  <li>Files are limited to 10MB (Until more powerful servers arrive)</li>
                                  <li>Accepted formats: mp4, mpg, mov, mpeg, avi, wmv</li>
                                  <li>Avoid changing extension names</li>
                                  <li>Each user is limited to 3 video uploads</li>
                                  <div id="outputWrapper" style="display:none;">
                                      <br>
                                      <u><b>Output</b></u><br>
                                      <div style="font-weight: bold;" id="realOutput">
                                      </div>
                                  </div>
                              </ol>
                          </div>
                          <p>Select your video: </p>
                            <div class="text-right">
                                <div id="uploadName"></div>
                                <a href="index.php"><button type="submit" class="btn btn-default" id='goToVideos' name="goToVideos" style="display: none;">My Videos</button></a>
                                <button type="submit" class="btn btn-default" id='newUpload' name="newUpload" style="display: none;">New Upload</button>
                                <span class="btn btn-primary btn-file">
                                    Browse <input type="file" name="userFile" id="userFile"/>
                                </span>
                                <button type="submit" class="btn btn-default" id='submitNow' name="submitNow">Upload</button>
                            </div>
                          <br>
                        <div class="modal-footer">
                          <div id="uploadResult"></div>
                          <div id="uploadPercent" class="col text-black"></div>
                          <div id="progressBar" class="progress"></div>
                        </div>
                      <?php }else{?>
                      <div id="noshow">
                          <p>Please <a href="#logForm" data-dismiss="modal" data-toggle="modal">login</a> </p>
                      </div>
                      <?php } ?>
                  </div>
                </div>
              </div>
          </div>
      </div>



      <!-- Main View -->
        <div class="row">
          <div class="col-10" id="videos">
            <div class="row">
              <div class="col" id="videos-left">

              </div>
              <div class="col" id="videos-center">
              </div>
              <div class="col" id="videos-right">

              </div>
            </div>
          </div>

          <div class="col-2" id="blank">
              <!--Welcome user-->
              <?php if(isset($_SESSION['username'])){?>
                  <br> <font color ="#ff1493">Hi there, <?php echo $_SESSION['firstname'];?>!</font>
              <?php } ?> <!-- Add auto-scroll functionality, link will be main page -->
          </div>
        </div>
        <div id="log"></div

      <!-- jQuery -->
      <script src="lib/jquery/jquery-3.1.1.min.js"></script>

      <!-- Javascript Plugin -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

      <!-- Bootstrap js -->
      <!--<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>-->
      <script src="lib/bootstrap/js/bootstrap.min.js"></script>

      <!-- Custom js -->
      <script type="text/javascript" src="js/view.js"></script> <!-- TODO create min.js once finished -->
      <script type="text/javascript" src="js/functions.js"></script>
      <script src="https://www.google.com/recaptcha/api.js"></script>
    </body>
  </html>
