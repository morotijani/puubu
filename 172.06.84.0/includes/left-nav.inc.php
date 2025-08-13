<!-- LEFT SIDE NAVIGATION -->

<nav class="col-md-2 d-none d-md-block sidebar" style="background: #4d4239;">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="index">
                    <span data-feather="home"></span>
                    Dashboard <span class="sr-only">(current)</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="election">
                    <span data-feather="list"></span>
                    Election
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="positions">
                    <span data-feather="bar-chart-2"></span>
                    Positions
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="contestants">
                    <span data-feather="users"></span>
                    Contestants
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="registrar">
                    <span data-feather="user-check"></span>
                    Voters
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="reports">
                    <span data-feather="flag"></span>
                    Reports
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Admin Details</span>
            <a class="d-flex align-items-center text-muted" href="javascript:;" aria-label="Add a new report">
                <img src="media/admin.jpg" class="rounded-circle" style="width: 25px; height: 25px;">
            </a>
        </h6>

        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="javascript:;">
                    <span data-feather="user"></span>
                    <?= $fullName; ?>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="details.php">
                    <span data-feather="more-horizontal"></span>
                    Details
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="settings.php">
                    <span data-feather="settings"></span>
                    Settings
                </a>
            </li>

            <hr>

            <li class="nav-item">
                <a class="nav-link" href="documentation">
                    <span data-feather="file-text"></span>
                    Documentation
                </a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="background-color: rgb(70, 60, 54);">


