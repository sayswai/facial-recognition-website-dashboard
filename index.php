<html>
  <head>
    <meta charset="utf-8">
    <meta httpequiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Computer Vision Face Detection Pipeline</title>

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
  <body>
    <!-- Navbar -->
    <div id="nav"></div> <!-- For TODO see navbar.html -->


    <!-- Header -->

    <!-- Login Form -->
    <div class="modal fade" id="logForm">
      <div class="modal-dialogue">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h1 class="modal-title">Log In</h1>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-2" for="user">Username:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="user" placeholder="Enter Username">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2" for="pw">Password:</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="pw" placeholder="Enter Password">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label><input type="checkbox"> Keep me logged in</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-default">Submit</button>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery -->
    <script src="lib/jquery/jquery-3.1.1.min.js"></script>

    <!-- Bootstrap js -->
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>

    <!-- Javascript Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Custom js -->
    <script src="js/view.js"></script> <!-- TODO create min.js once finished -->
  </body>
</html>
