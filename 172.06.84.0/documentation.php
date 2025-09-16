<?php

    require_once("../connection/conn.php");
    if (!cadminIsLoggedIn()) {
        cadminLoginErrorRedirect();
    }
    
    include ('includes/header.inc.php');
    include ('includes/top-nav.inc.php');
    include ('includes/left-nav.inc.php');


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
                                        <a class="btn btn-dark px-3" href="<?= ADROOT; ?>documentation">
                                            Refresh
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

            
            <!-- Industry news -->
            <div class="card mb-6 mb-xxl-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="fs-6 mb-0">General overview</h3>
                        </div>
                        <div class="col-auto my-n3 me-n3">
                            <a class="btn btn-sm btn-link" href="">
                                Browse all
                                <span class="material-symbols-outlined">arrow_right_alt</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body py-3">
                    <!-- List -->
                    <div class="list-group list-group-flush">

                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Add election</h3>
                                        <p class="text-body-secondary">To start a fresh new election, go to the <a href="<?= PROOT; ?>172.06.84.0/election">Add Elections</a> tab.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Positions & Elections</h3>
                                        <p class="text-body-secondary">To setup the positions under their respective elections, go to the <a href="<?= PROOT; ?>172.06.84.0/positions">Manage Positions & Elections tab</a>.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Add Contestants</h3>
                                        <p class="text-body-secondary">For the adding up of the candidates, head to the <a href="<?= PROOT; ?>172.06.84.0/contestants">Add Contestants</a> tab.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Manage Contestants</h3>
                                        <p class="text-body-secondary">Go to <a href="<?= PROOT; ?>172.06.84.0/contestants">Manage Contestants</a> tab to setup the contestants.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Voters</h3>
                                        <p class="text-body-secondary">Registrars you wish to add to allow them to vote can be managed at the <a href="<?= PROOT; ?>172.06.84.0/registrar">Voters</a> tab.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col">
                                    <a class="text-reset" href="#!">
                                        <h3 class="fs-base">Password</h3>
                                        <p class="text-body-secondary">It is highly recommended to change admin's password at the <a href="<?= PROOT; ?>172.06.84.0/settings.php?cp=1">Change Password</a> tab before conducting an election.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <?php include ('includes/footer.inc.php'); ?>

