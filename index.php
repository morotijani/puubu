<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
  
    <!-- Favicon -->
    <link rel="shortcut icon" href="media/puubu.favicon.png" type="image/x-icon" />
  
    <!-- Libs CSS -->
    <link rel="stylesheet" href="dist/css/libs.bundle.css" />
  
    <!-- Main CSS -->
    <link rel="stylesheet" href="dist/css/index.bundle.css" />
    <link rel="stylesheet" href="dist/css/puubu.css" />
  
    <!-- Title -->
    <title>Hail to Puubu</title>
</head>
<body>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-sticky navbar-dark">
        <div class="container">
            <a href="index" class="navbar-brand"><img src="media/puubu.logo.png" alt="Logo"></a>
  
            <ul class="navbar-nav navbar-nav-secondary order-lg-3">
                <li class="nav-item">
                    <a class="nav-link nav-icon" data-bs-toggle="offcanvas" href="javascript:;">
                        <span class="bi bi-box-arrow-down-left"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
  
    <div class="offcanvas-wrap">

        <section class="inverted overflow-hidden position-relative" data-top-top="background-color: #FF9800" data-top-center="background-color: rgba(235,60,39,1)" data-top-bottom="background-color: #FF9800" style="background-color: #FF9800;">
            <div class="container d-flex flex-column foreground min-vh-100">
                <div class="row align-items-center justify-content-center my-auto">
                    <div class="col-lg-10 text-center">
                        <span class="eyebrow text-secondary mb-2">— Barack Obama</span>
                        <h1 class="display-3 fw-bold">There’s no such thing as a vote that doesn’t matter. It all matters.</h1>
                        <a href="signin" class="btn btn-white rounded-pill">Show your power!</a>
                    </div>
                </div>
            </div>

            <figure class="background">
                <svg width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle data-aos="fade-up" data-aos-delay="150" cx="125%" cy="-25%" r="35%" fill="white" fill-opacity=".05" data-top-top="@cy: -25%; @cx: 115%;" data-top-bottom="@cy: 0%; @cx: 105%;" />
                    <circle data-aos="fade-up" data-aos-delay="300" cx="90%" cy="125%" r="75%" fill="black" fill-opacity=".1" data-center-top="@r: 75%;" data-top-bottom="@r: 85%;" />
                    <circle data-aos="fade-up" data-aos-delay="450" cx="5%" cy="125%" r="50%" stroke="black" stroke-opacity=".2" data-center-top="@r: 50%;" data-center-bottom="@r: 70%;" />
                </svg>
            </figure>
            <span class="scroll-down"></span>
        </section>
    </div>

  <!-- javascript -->
  <script src="dist/js/vendor.bundle.js"></script>
  <script src="dist/js/index.bundle.js"></script>

</body>
</html>