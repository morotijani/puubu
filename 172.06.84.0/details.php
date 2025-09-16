<?php

  // CONNECTION TO DATABASE
    require_once("../connection/conn.php");
    if (!cadminIsLoggedIn()) {
        cadminLoginErrorRedirect();
    }

       // REQUIREMENT OF EXTERNAL FILES
    include ('includes/header.inc.php');
    include ('includes/top-nav.inc.php');
    include ('includes/left-nav.inc.php');

      // OUTPUT ERRORS
    $message = '';



?>
    <!-- Main -->
    <main class="main px-lg-6">
        <!-- Content -->
        <div class="container-lg">
            <!-- Page header -->
            <div class="row align-items-center mb-7">
                <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-xl rounded text-primary">
                        <i class="fs-2" data-duoicon="app"></i>
                    </div>
                </div>
                <div class="col">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Documentation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Documentation</li>
                        </ol>
                    </nav>

                    <!-- Heading -->
                    <h1 class="fs-4 mb-0">Documentation</h1>
                </div>
                <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                    <!-- Action -->
                <!-- <a class="btn btn-secondary d-block" href="javascript:;">
                    <span class="material-symbols-outlined me-1">export_notes</span> Export
                </a> -->
            </div>
        </div>

        <!-- Page content -->
        <div class="row">
            <div class="col-12">
                <!-- Filters -->
                <div class="card card-line bg-body-tertiary border-transparent mb-7">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="text-body-secondary">Documentation to guide you.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg">
                                <div class="row gx-3  ">
                                    <div class="col col-lg-auto ms-auto">
                                        <!-- <div class="input-group bg-body">
                                            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search" />
                                            <span class="input-group-text" id="search">
                                                <span class="material-symbols-outlined">search</span>
                                            </span>
                                        </div> -->
                                    </div>

                                    <div class="col-auto">
                                        <a class="btn btn-dark px-3" href="<?= ADROOT; ?>settings?cp=1">
                                            Change password
                                        </a>
                                    </div>

                                    <div class="col-auto ms-n2">
                                        <a class="btn btn-dark px-3" href="<?= goBack(); ?>">
                                            Go back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div>

            <div class="card mb-6 mb-xxl-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="fs-6 mb-0">Profile</h3>
                        </div>
                        <div class="col-auto my-n3 me-n3">
                            <a class="btn btn-sm btn-link" href="<?= ADROOT; ?>settings">
                                Update
                                <span class="material-symbols-outlined">arrow_right_alt</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body py-3">
                    <?= get_admin_profile($admin_id); ?>
                </div>
            </div>
        
<?php include ('includes/footer.inc.php');?>
