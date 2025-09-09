                <!-- SMALL LEFT NAV BAR -->
                <!-- SMALL LEFT NAV BAR -->
                
                <!-- Toggler -->
                <button
                    class="navbar-toggler ms-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidenavSmallCollapse"
                    aria-controls="sidenavSmallCollapse"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenavSmallCollapse">
                    <!-- Search -->
                    <div class="input-group d-xl-none my-4 my-xl-0">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" aria-describedby="sidenavSmallSearchMobile" />
                        <span class="input-group-text" id="sidenavSmallSearchMobile">
                            <span class="material-symbols-outlined">search</span>
                        </span>
                    </div>
                
                    <!-- Nav -->
                    <nav class="navbar-nav nav-pills">
                        <div class="nav-item dropend">
                            <a class="nav-link active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined">space_dashboard</span>
                                <span class="ms-3 d-xl-none">Home</span>
                            </a>
                            <div class="dropdown-menu ms-xl-3">
                                <h6 class="dropdown-header d-none d-xl-block">Dashboards</h6>
                                <a class="dropdown-item active" href="<?= PROOT; ?>172.06.84.0/index">Default</a>
                                <a class="dropdown-item " href="<?= PROOT; ?>172.06.84.0/election">Elections</a>
                                <a class="dropdown-item " href="<?= PROOT; ?>172.06.84.0/">...</a>
                            </div>
                        </div>
                        <div class="nav-item dropend">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/election">
                                <span class="material-symbols-outlined" title="Elections">how_to_vote</span>
                                <span class="ms-3 d-xl-none">Election</span>
                            </a>
                        </div>
                        <div class="nav-item dropend">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/positions">
                                <span class="material-symbols-outlined" title="Positions">move_selection_up</span>
                                <span class="ms-3 d-xl-none">Positions</span>
                            </a>
                        </div>
                        <div class="nav-item dropend">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/contestants">
                                <span class="material-symbols-outlined" title="Contestants">tooltip</span>
                                <span class="ms-3 d-xl-none">Contestants</span>
                            </a>
                        </div>
                        <div class="nav-item dropend">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/registrar">
                                <span class="material-symbols-outlined" title="Voters">crowdsource</span>
                                <span class="ms-3 d-xl-none">Voters</span>
                            </a>
                        </div>
                        <div class="nav-item dropend">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/election">
                                <span class="material-symbols-outlined" title="Elections">how_to_vote</span>
                                <span class="ms-3 d-xl-none">Election</span>
                            </a>
                        </div>
                    </nav>
                
                    <!-- Divider -->
                    <hr class="my-4" />

                    <!-- Nav -->
                    <nav class="navbar-nav nav-pills">
                        <div class="nav-item dropend">
                            <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined">code</span>
                                <span class="ms-3 d-xl-none">Docs</span>
                            </a>
                            <div class="dropdown-menu ms-xl-3">
                                <h6 class="dropdown-header d-none d-xl-block">Documentation</h6>
                                <a class="dropdown-item " href="./docs/getting-started.html">Getting started</a>
                                <a class="dropdown-item " href="./docs/components.html">Components</a>
                                <a class="dropdown-item d-flex " href="./docs/changelog.html"
                                >Changelog <span class="badge text-bg-primary ms-auto">1.0.6</span></a
                                >
                            </div>
                        </div>
                    </nav>
                
                    <!-- Divider -->
                    <hr class="my-4 d-xl-none" />
                
                    <!-- Nav -->
                    <nav class="navbar-nav nav-pills mt-auto">
                        <div class="nav-item">
                            <a
                                class="nav-link"
                                href="<?= PROOT; ?>index"
                                target="_blank"
                                data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                data-bs-title="Go to website"
                            >
                                <span class="material-symbols-outlined">globe</span> <span class="d-xl-none ms-3">Go to website</span>
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="mailto:info@puubu.com" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Contact us">
                                <span class="material-symbols-outlined">alternate_email</span> <span class="d-xl-none ms-3">Contact us</span>
                            </a>
                        </div>
                        <div class="nav-item dropend">
                            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" data-bs-settings-switcher aria-expanded="false">
                                <span class="material-symbols-outlined">settings</span> <span class="d-xl-none ms-3">Settings</span>
                            </a>
                            <div class="dropdown-menu top-auto bottom-0 ms-xl-3">
                                <!-- Color mode -->
                                <h6 class="dropdown-header">Color mode</h6>
                                <a class="dropdown-item d-flex" data-bs-theme-value="light" href="#" role="button"> <span class="material-symbols-outlined me-2">light_mode</span> Light </a>
                                <a class="dropdown-item d-flex" data-bs-theme-value="dark" href="#" role="button"> <span class="material-symbols-outlined me-2">dark_mode</span> Dark </a>
                                <a class="dropdown-item d-flex" data-bs-theme-value="auto" href="#" role="button"> <span class="material-symbols-outlined me-2">contrast</span> Auto </a>
                        
                                <!-- Navigation position -->
                                <hr class="dropdown-divider" />
                                <h6 class="dropdown-header">Navigation position</h6>
                                <a class="dropdown-item d-flex" data-bs-navigation-position-value="sidenav" href="#" role="button">
                                    <span class="material-symbols-outlined me-2">keyboard_tab_rtl</span> Sidenav
                                </a>
                                <a class="dropdown-item d-flex" data-bs-navigation-position-value="topnav" href="#" role="button">
                                    <span class="material-symbols-outlined me-2">vertical_align_top</span> Topnav
                                </a>
                        
                                <!-- Sidenav sizing -->
                                <div class="sidenav-sizing">
                                    <hr class="dropdown-divider" />
                                    <h6 class="dropdown-header">Sidenav sizing</h6>
                                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="base" href="#" role="button">
                                        <span class="material-symbols-outlined me-2">density_large</span> Base
                                    </a>
                                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="md" href="#" role="button">
                                        <span class="material-symbols-outlined me-2">density_medium</span> Medium
                                    </a>
                                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="sm" href="#" role="button">
                                        <span class="material-symbols-outlined me-2">density_small</span> Small
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </nav>
    </aside>
    


    <!-- Sidenav (md) -->
    <aside class="aside aside-md sidenav-md">
        <nav class="navbar navbar-expand-xl navbar-vertical">
            <div class="container-lg">
                <!-- Brand -->
                <a class="navbar-brand fs-5 fw-bold text-xl-center mb-xl-4" href="<?= PROOT; ?>172.06.84.0/index">
                    <i class="fs-4 text-secondary" data-duoicon="box-2"></i> <span class="d-xl-none ms-1">Dashbrd</span>
                </a>
            
                <!-- User -->
                <div class="d-flex ms-auto d-xl-none">
                    <div class="dropdown my-n2">
                    <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                        <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                        </span>
                        <span class="d-none d-xl-block"><?= $fullName; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= PROOT; ?>172.06.84.0/account">Account</a></li>

                        <li><a class="dropdown-item" href="<?= PROOT; ?>172.06.84.0/password-reset" target="_blank">Change password</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="<?= PROOT; ?>172.06.84.0/logout">Sign out</a></li>
                    </ul>
                </div>
            
                <!-- Divider -->
                <div class="vr align-self-center bg-dark mx-2"></div>
            
                <!-- Notifications -->
                <div class="dropdown ">
                    <button class="btn btn-link" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <span class="material-symbols-outlined scale-125">notifications</span>
                        <span class="position-absolute top-0 end-0 m-3 p-1 bg-warning rounded-circle">
                            <span class="visually-hidden">New notifications</span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 350px">
                        <!-- Header -->
                        <div class="row">
                            <div class="col">
                                <h6 class="dropdown-header me-auto">Notifications</h6>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined me-1">done_all</span> Mark all as read</button>
                                <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined">settings</span></button>
                            </div>
                        </div>
                
                        <!-- Items -->
                        <div class="list-group list-group-flush px-4">
                            <div class="list-group-item border-style-dashed px-0">
                                <div class="row gx-3">
                                    <div class="col-auto">
                                        <div class="avatar avatar-sm">
                                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="text-body mb-2">
                                            <span class="fw-semibold">Emily T.</span> commented on your post <br /><small class="text-body-secondary">5 minutes ago</small>
                                        </p>
                                        <div class="card">
                                            <div class="card-body p-3">Love the new dashboard layout! Super clean and easy to navigate 🔥</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-style-dashed px-0">
                                <div class="row gx-3">
                                    <div class="col-auto">
                                        <div class="avatar avatar-sm">
                                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="text-body mb-2">
                                            <span class="fw-semibold">Michael J.</span> requested changes on your post <br />
                                            <small class="text-body-secondary">10 minutes ago</small>
                                        </p>
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <p class="mb-2">Could you update the revenue chart with the latest data? Thanks!</p>
                                                <p class="mb-0">
                                                    <button class="btn btn-sm btn-light" type="button">Update now</button>
                                                    <button class="btn btn-sm btn-link">Dismiss</button>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-style-dashed px-0">
                                <div class="row gx-3 align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar">
                                            <span class="material-symbols-outlined">error</span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p class="text-body mb-0">
                                            <span class="fw-semibold">System alert</span> - Build failed <br />
                                            <small class="text-body-secondary">1 hour ago</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>






        
            <!-- Toggler -->
            <button
                class="navbar-toggler ms-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidenavMediumCollapse"
                aria-controls="sidenavMediumCollapse"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenavMediumCollapse">
                <!-- Search -->
                <div class="input-group d-xl-none my-4 my-xl-0">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search" aria-describedby="sidenavMediumSearchMobile" />
                <span class="input-group-text" id="sidenavMediumSearchMobile">
                    <span class="material-symbols-outlined">search</span>
                </span>
                </div>
        
                <!-- Nav -->
                <nav class="navbar-nav nav-pills">
                <div class="nav-item dropend">
                    <a
                    class="nav-link flex-xl-column active"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                  <span class="material-symbols-outlined">space_dashboard</span>
                  <span class="ms-3 ms-xl-0 mt-xl-1 d-xl-block align-self-stretch fs-xl-sm text-xl-center text-truncate">Home</span>
                </a>
                <div class="dropdown-menu ms-xl-3">
                  <a class="dropdown-item active" href="<?= PROOT; ?>172.06.84.0/index">Default</a>
                  <a class="dropdown-item " href="<?= PROOT; ?>172.06.84.0/election">Election</a>
                  <a class="dropdown-item " href="javascript:;">...</a>
                </div>
              </div>
              <div class="nav-item dropend">
                <a
                  class="nav-link flex-xl-column "
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  data-bs-auto-close="outside"
                  aria-expanded="false"
                >
                  <span class="material-symbols-outlined">auto_stories</span>
                  <span class="ms-3 ms-xl-0 mt-xl-1 d-xl-block align-self-stretch fs-xl-sm text-xl-center text-truncate">Pages</span>
                </a>
                <ul class="dropdown-menu ms-xl-3">
                  <li class="dropend">
                    <a
                      class="dropdown-item d-flex "
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      Customers <span class="material-symbols-outlined ms-auto">chevron_right</span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item " href="./customers/customers.html">Customers</a>
                      <a class="dropdown-item " href="./customers/customer.html">Customer details</a>
                      <a class="dropdown-item " href="./customers/customer-new.html">New customer</a>
                    </div>
                  </li>
                  <li class="dropend">
                    <a
                      class="dropdown-item d-flex "
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      Projects <span class="material-symbols-outlined ms-auto">chevron_right</span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item " href="./projects/projects.html">Projects</a>
                      <a class="dropdown-item " href="./projects/project.html">Project overview</a>
                      <a class="dropdown-item " href="./projects/project-new.html">New project</a>
                    </div>
                  </li>
                  <li class="dropend">
                    <a
                      class="dropdown-item d-flex "
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      Account <span class="material-symbols-outlined ms-auto">chevron_right</span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item " href="./account/account.html">Account overview</a>
                      <a class="dropdown-item " href="./account/account-settings.html">Account settings</a>
                    </div>
                  </li>
                  <li class="dropend">
                    <a
                      class="dropdown-item d-flex "
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      E-commerce <span class="material-symbols-outlined ms-auto">chevron_right</span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item " href="./ecommerce/products.html">Products</a>
                      <a class="dropdown-item " href="./ecommerce/orders.html">Orders</a>
                      <a class="dropdown-item " href="./ecommerce/invoice.html">Invoice</a>
                      <a class="dropdown-item " href="./ecommerce/pricing.html">Pricing</a>
                    </div>
                  </li>
                  <li class="dropend">
                    <a
                      class="dropdown-item d-flex "
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      Posts <span class="material-symbols-outlined ms-auto">chevron_right</span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item " href="./posts/categories.html">Categories</a>
                      <a class="dropdown-item " href="./posts/posts.html">Posts</a>
                      <a class="dropdown-item " href="./posts/post-new.html">New post</a>
                    </div>
                  </li>
                  <li class="dropend">
                    <a class="dropdown-item d-flex" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                      Authentication <span class="material-symbols-outlined ms-auto">chevron_right</span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="./auth/sign-in.html" target="_blank">Sign in</a>
                      <a class="dropdown-item" href="./auth/sign-up.html" target="_blank">Sign up</a>
                      <a class="dropdown-item" href="./auth/password-reset.html" target="_blank">Password reset</a>
                      <a class="dropdown-item" href="./auth/verification-code.html" target="_blank">Verification code</a>
                      <a class="dropdown-item" href="./auth/error.html" target="_blank">Error</a>
                    </div>
                  </li>
                  <li class="dropend">
                    <a
                      class="dropdown-item d-flex "
                      href="#"
                      role="button"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      Misc <span class="material-symbols-outlined ms-auto">chevron_right</span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item " href="./other/calendar.html">Calendar</a>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="nav-item dropend">
                <a class="nav-link flex-xl-column" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="material-symbols-outlined">email</span>
                  <span class="ms-3 ms-xl-0 mt-xl-1 d-xl-block align-self-stretch fs-xl-sm text-xl-center text-truncate">Emails</span>
                </a>
                <div class="dropdown-menu ms-xl-3">
                  <a class="dropdown-item" href="./emails/account-confirmation.html" target="_blank">Account confirmation</a>
                  <a class="dropdown-item" href="./emails/new-post.html" target="_blank">New post</a>
                  <a class="dropdown-item" href="./emails/order-confirmation.html" target="_blank">Order confirmation</a>
                  <a class="dropdown-item" href="./emails/password-reset.html" target="_blank">Password reset</a>
                  <a class="dropdown-item" href="./emails/product-update.html" target="_blank">Product update</a>
                </div>
              </div>
              <div class="nav-item dropend">
                <a class="nav-link flex-xl-column" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="material-symbols-outlined">tooltip</span>
                  <span class="ms-3 ms-xl-0 mt-xl-1 d-xl-block align-self-stretch fs-xl-sm text-xl-center text-truncate">Modals</span>
                </a>
                <div class="dropdown-menu ms-xl-3">
                  <a class="dropdown-item" href="#productModal" data-bs-toggle="offcanvas" aria-controls="productModal">Product</a>
                  <a class="dropdown-item" href="#orderModal" data-bs-toggle="offcanvas" aria-controls="orderModal">Order</a>
                  <a class="dropdown-item" href="#eventModal" data-bs-toggle="modal" aria-controls="eventModal">Event</a>
                </div>
              </div>
            </nav>
    
            <!-- Divider -->
            <hr class="my-4" />
    
            <!-- Nav -->
            <nav class="navbar-nav nav-pills">
              <div class="nav-item dropend">
                <a
                  class="nav-link flex-xl-column "
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <span class="material-symbols-outlined">code</span>
                  <span class="ms-3 ms-xl-0 mt-xl-1 d-xl-block align-self-stretch fs-xl-sm text-xl-center text-truncate">Docs</span>
                </a>
                <div class="dropdown-menu ms-xl-3">
                  <h6 class="dropdown-header d-none d-xl-block">Documentation</h6>
                  <a class="dropdown-item " href="./docs/getting-started.html">Getting started</a>
                  <a class="dropdown-item " href="./docs/components.html">Components</a>
                  <a class="dropdown-item d-flex " href="./docs/changelog.html"
                    >Changelog <span class="badge text-bg-primary ms-auto">1.0.6</span></a
                  >
                </div>
              </div>
            </nav>
    
            <!-- Divider -->
            <hr class="my-4 d-xl-none" />
    
            <!-- Nav -->
            <nav class="navbar-nav nav-pills mt-auto">
              <div class="nav-item">
                <a
                  class="nav-link flex-xl-column"
                  href="https://themes.getbootstrap.com/product/dashbrd/"
                  target="_blank"
                  data-bs-toggle="tooltip"
                  data-bs-placement="right"
                  data-bs-title="Go to product page"
                >
                  <span class="material-symbols-outlined">local_mall</span> <span class="d-xl-none ms-3">Go to product page</span>
                </a>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link flex-xl-column"
                  href="mailto:yevgenysim+simpleqode@gmail.com"
                  data-bs-toggle="tooltip"
                  data-bs-placement="right"
                  data-bs-title="Contact us"
                >
                  <span class="material-symbols-outlined">alternate_email</span> <span class="d-xl-none ms-3">Contact us</span>
                </a>
              </div>
              <div class="nav-item dropend">
                <a class="nav-link flex-xl-column" href="#" role="button" data-bs-toggle="dropdown" data-bs-settings-switcher aria-expanded="false">
                  <span class="material-symbols-outlined">settings</span> <span class="d-xl-none ms-3">Settings</span>
                </a>
                <div class="dropdown-menu top-auto bottom-0 ms-xl-3">
                  <!-- Color mode -->
                  <h6 class="dropdown-header">Color mode</h6>
                  <a class="dropdown-item d-flex" data-bs-theme-value="light" href="#" role="button"> <span class="material-symbols-outlined me-2">light_mode</span> Light </a>
                  <a class="dropdown-item d-flex" data-bs-theme-value="dark" href="#" role="button"> <span class="material-symbols-outlined me-2">dark_mode</span> Dark </a>
                  <a class="dropdown-item d-flex" data-bs-theme-value="auto" href="#" role="button"> <span class="material-symbols-outlined me-2">contrast</span> Auto </a>
                
                  <!-- Navigation position -->
                  <hr class="dropdown-divider" />
                  <h6 class="dropdown-header">Navigation position</h6>
                  <a class="dropdown-item d-flex" data-bs-navigation-position-value="sidenav" href="#" role="button">
                    <span class="material-symbols-outlined me-2">keyboard_tab_rtl</span> Sidenav
                  </a>
                  <a class="dropdown-item d-flex" data-bs-navigation-position-value="topnav" href="#" role="button">
                    <span class="material-symbols-outlined me-2">vertical_align_top</span> Topnav
                  </a>
                
                  <!-- Sidenav sizing -->
                  <div class="sidenav-sizing">
                    <hr class="dropdown-divider" />
                    <h6 class="dropdown-header">Sidenav sizing</h6>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="base" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_large</span> Base
                    </a>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="md" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_medium</span> Medium
                    </a>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="sm" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_small</span> Small
                    </a>
                  </div>
                </div>
              </div>
            </nav>
          </div>
        </div>
      </nav>
    </aside>








    
    
    <!-- Sidenav (base) -->
    <aside class="aside aside-base sidenav-base">
        <nav class="navbar navbar-expand-xl navbar-vertical">
            <div class="container-lg">
                <!-- Brand -->
                <a class="navbar-brand d-flex align-items-center fs-5 fw-bold px-xl-3 mb-xl-4" href="<?= PROOT; ?>172.06.84.0/index">
                    <i class="fs-4 text-secondary me-2" data-duoicon="box-2"></i> Dashbrd
                </a>
            
                <!-- User -->
                <div class="d-flex ms-auto d-xl-none">
                    <div class="dropdown my-n2">
                        <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                                <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                                </span>
                            <span class="d-none d-xl-block"><?= $fullName; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= PROOT; ?>172.06.84.0/account">Account</a></li>
                            <li><a class="dropdown-item" href="<?= PROOT; ?>172.06.84.0/password-reset" target="_blank">Change password</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="<?= PROOT; ?>172.06.84.0/logout">Sign out</a></li>
                        </ul>
                    </div>
            
                    <!-- Divider -->
                    <div class="vr align-self-center bg-dark mx-2"></div>
            
                        <!-- Notifications -->
                        <div class="dropdown ">
                            <button class="btn btn-link" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <span class="material-symbols-outlined scale-125">notifications</span>
                                <span class="position-absolute top-0 end-0 m-3 p-1 bg-warning rounded-circle">
                                <span class="visually-hidden">New notifications</span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" style="width: 350px">
                                <!-- Header -->
                                <div class="row">
                                <div class="col">
                                    <h6 class="dropdown-header me-auto">Notifications</h6>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined me-1">done_all</span> Mark all as read</button>
                                    <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined">settings</span></button>
                                </div>
                            </div>
                    
                            <!-- Items -->
                            <div class="list-group list-group-flush px-4">
                                <div class="list-group-item border-style-dashed px-0">
                                    <div class="row gx-3">
                                        <div class="col-auto">
                                            <div class="avatar avatar-sm">
                                                <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <p class="text-body mb-2">
                                                <span class="fw-semibold">Emily T.</span> commented on your post <br /><small class="text-body-secondary">5 minutes ago</small>
                                            </p>
                                            <div class="card">
                                                <div class="card-body p-3">Love the new dashboard layout! Super clean and easy to navigate 🔥</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item border-style-dashed px-0">
                                    <div class="row gx-3">
                                        <div class="col-auto">
                                            <div class="avatar avatar-sm">
                                                <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <p class="text-body mb-2">
                                                <span class="fw-semibold">Michael J.</span> requested changes on your post <br />
                                                <small class="text-body-secondary">10 minutes ago</small>
                                            </p>
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <p class="mb-2">Could you update the revenue chart with the latest data? Thanks!</p>
                                                    <p class="mb-0">
                                                        <button class="btn btn-sm btn-light" type="button">Update now</button>
                                                        <button class="btn btn-sm btn-link">Dismiss</button>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item border-style-dashed px-0">
                                    <div class="row gx-3 align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar">
                                                <span class="material-symbols-outlined">error</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <p class="text-body mb-0">
                                                <span class="fw-semibold">System alert</span> - Build failed <br />
                                                <small class="text-body-secondary">1 hour ago</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Toggler -->
                <button
                    class="navbar-toggler ms-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidenavBaseCollapse"
                    aria-controls="sidenavBaseCollapse"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
            
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenavBaseCollapse">
                    <!-- Search -->
                    <div class="input-group d-xl-none my-4 my-xl-0">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" aria-describedby="sidenavBaseSearchMobile" />
                        <span class="input-group-text" id="sidenavBaseSearchMobile">
                            <span class="material-symbols-outlined">search</span>
                        </span>
                    </div>
            
                    <!-- Nav -->
                    <nav class="navbar-nav nav-pills mb-7">
                        <div class="nav-item">
                            <a
                                class="nav-link active"
                                href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#dashboards"
                                role="button"
                                aria-expanded="false"
                                aria-controls="dashboards"
                            >
                                <span class="material-symbols-outlined me-3">space_dashboard</span> Dashboards
                            </a>
                            <div class="collapse show" id="dashboards">
                                <nav class="nav nav-pills">
                                    <a class="nav-link active" href="<?= PROOT; ?>172.06.84.0/index">Home</a>
                                    <a class="nav-link " href="<?= PROOT; ?>172.06.84.0/election">Elections</a>
                                    <a class="nav-link " href="javascript:;">...</a>
                                </nav>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/positions">
                                <span class="material-symbols-outlined me-3">move_selection_up</span> Positions
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link " href="<?= PROOT; ?>172.06.84.0/election">
                                <span class="material-symbols-outlined me-3">how_to_vote</span> Elections
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link " href="#">
                                <span class="material-symbols-outlined me-3">person</span> Account
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/contestants">
                                <span class="material-symbols-outlined me-3">groups_2</span> Contestants
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link " href="<?= PROOT; ?>172.06.84.0/registrar">
                                <span class="material-symbols-outlined me-3">crowdsource</span> Registrar
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0/reports">
                                <span class="material-symbols-outlined me-3">bar_chart</span> Report
                            </a>
                        </div>
                    </nav>
    
                    <!-- Heading -->
                    <h3 class="fs-base px-3 mb-4">Documentation</h3>
            
                    <!-- Nav -->
                    <nav class="navbar-nav nav-pills mb-xl-7">
                        <div class="nav-item">
                            <a class="nav-link" href="<?= PROOT; ?>172.06.84.0">
                                <span class="material-symbols-outlined me-3">sticky_note_2</span> Getting started
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link " href="./docs/components.html">
                                <span class="material-symbols-outlined me-3">deployed_code</span> Documentation
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link " href="javascript:;">
                                <span class="material-symbols-outlined me-3">list_alt</span> Version
                                <span class="badge text-bg-primary ms-auto">1.0.6</span>
                            </a>
                        </div>
                    </nav>

                    heres
            
                <!-- Divider -->
                <hr class="my-4 d-xl-none" />
        
                <!-- Nav -->
                <nav class="navbar-nav nav-pills d-xl-none mb-7">
                    <div class="nav-item">
                        <a class="nav-link" href="https://themes.getbootstrap.com/product/dashbrd/" target="_blank">
                        <span class="material-symbols-outlined me-3">local_mall</span> Go to website
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" href="mailto:yevgenysim+simpleqode@gmail.com">
                        <span class="material-symbols-outlined me-3">alternate_email</span> Contact us
                        </a>
                    </div>
                    <div class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" data-bs-settings-switcher aria-expanded="false">
                    <span class="material-symbols-outlined me-3"> settings </span> Settings
                    </a>
                    <div class="dropdown-menu ">
                    <!-- Color mode -->
                    <h6 class="dropdown-header">Color mode</h6>
                    <a class="dropdown-item d-flex" data-bs-theme-value="light" href="#" role="button"> <span class="material-symbols-outlined me-2">light_mode</span> Light </a>
                  <a class="dropdown-item d-flex" data-bs-theme-value="dark" href="#" role="button"> <span class="material-symbols-outlined me-2">dark_mode</span> Dark </a>
                  <a class="dropdown-item d-flex" data-bs-theme-value="auto" href="#" role="button"> <span class="material-symbols-outlined me-2">contrast</span> Auto </a>
                
                  <!-- Navigation position -->
                  <hr class="dropdown-divider" />
                  <h6 class="dropdown-header">Navigation position</h6>
                  <a class="dropdown-item d-flex" data-bs-navigation-position-value="sidenav" href="#" role="button">
                    <span class="material-symbols-outlined me-2">keyboard_tab_rtl</span> Sidenav
                  </a>
                  <a class="dropdown-item d-flex" data-bs-navigation-position-value="topnav" href="#" role="button">
                    <span class="material-symbols-outlined me-2">vertical_align_top</span> Topnav
                  </a>
                
                  <!-- Sidenav sizing -->
                  <div class="sidenav-sizing">
                    <hr class="dropdown-divider" />
                    <h6 class="dropdown-header">Sidenav sizing</h6>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="base" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_large</span> Base
                    </a>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="md" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_medium</span> Medium
                    </a>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="sm" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_small</span> Small
                    </a>
                  </div>
                </div>
              </div>
            </nav>
    
            <!-- Card -->
            <div class="card mt-auto">
              <div class="card-body">
                <!-- Heading -->
                <h6>Need help?</h6>
    
                <!-- Text -->
                <p class="text-body-secondary mb-0">Feel free to reach out to us should you have any questions or suggestions.</p>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </aside>

    <!-- Topnav -->
    <!-- Topnav (base) -->
    <nav class="navbar navbar-expand-xl topnav-base">
      <div class="container-lg">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center fs-5 fw-bold" href="<?= PROOT; ?>172.06.84.0/index"
          ><i class="fs-4 text-secondary me-2" data-duoicon="box-2"></i>Dashbrd</a
        >
    
        <!-- User -->
        <div class="d-flex ms-auto d-xl-none">
          <div class="dropdown my-n2">
            <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
              </span>
              <span class="d-none d-xl-block"><?= $fullName; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="./account/account.html">Account</a></li>
              <li><a class="dropdown-item" href="./auth/password-reset.html" target="_blank">Change password</a></li>
              <li><hr class="dropdown-divider" /></li>
              <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
          </div>
    
          <!-- Divider -->
          <div class="vr align-self-center bg-dark mx-2"></div>
    
          <!-- Notifications -->
          <div class="dropdown ">
            <button class="btn btn-link" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
              <span class="material-symbols-outlined scale-125">notifications</span>
              <span class="position-absolute top-0 end-0 m-3 p-1 bg-warning rounded-circle">
                <span class="visually-hidden">New notifications</span>
              </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="width: 350px">
              <!-- Header -->
              <div class="row">
                <div class="col">
                  <h6 class="dropdown-header me-auto">Notifications</h6>
                </div>
                <div class="col-auto">
                  <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined me-1">done_all</span> Mark all as read</button>
                  <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined">settings</span></button>
                </div>
              </div>
          
              <!-- Items -->
              <div class="list-group list-group-flush px-4">
                <div class="list-group-item border-style-dashed px-0">
                  <div class="row gx-3">
                    <div class="col-auto">
                      <div class="avatar avatar-sm">
                        <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                      </div>
                    </div>
                    <div class="col">
                      <p class="text-body mb-2">
                        <span class="fw-semibold">Emily T.</span> commented on your post <br /><small class="text-body-secondary">5 minutes ago</small>
                      </p>
                      <div class="card">
                        <div class="card-body p-3">Love the new dashboard layout! Super clean and easy to navigate 🔥</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="list-group-item border-style-dashed px-0">
                  <div class="row gx-3">
                    <div class="col-auto">
                      <div class="avatar avatar-sm">
                        <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                      </div>
                    </div>
                    <div class="col">
                      <p class="text-body mb-2">
                        <span class="fw-semibold">Michael J.</span> requested changes on your post <br />
                        <small class="text-body-secondary">10 minutes ago</small>
                      </p>
                      <div class="card">
                        <div class="card-body p-3">
                          <p class="mb-2">Could you update the revenue chart with the latest data? Thanks!</p>
                          <p class="mb-0">
                            <button class="btn btn-sm btn-light" type="button">Update now</button>
                            <button class="btn btn-sm btn-link">Dismiss</button>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="list-group-item border-style-dashed px-0">
                  <div class="row gx-3 align-items-center">
                    <div class="col-auto">
                      <div class="avatar">
                        <span class="material-symbols-outlined">error</span>
                      </div>
                    </div>
                    <div class="col">
                      <p class="text-body mb-0">
                        <span class="fw-semibold">System alert</span> - Build failed <br />
                        <small class="text-body-secondary">1 hour ago</small>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    
        <!-- Toggler -->
        <button
          class="navbar-toggler ms-3"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#topnavBaseCollapse"
          aria-controls="topnavBaseCollapse"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
    
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="topnavBaseCollapse">
          <!-- Search -->
          <div class="input-group d-xl-none my-4 my-xl-0">
            <input class="form-control" id="topnavSearchInputMobile" type="search" placeholder="Search" aria-label="Search" aria-describedby="navbarSearchMobile" />
            <span class="input-group-text" id="navbarSearchMobile">
              <span class="material-symbols-outlined">search</span>
            </span>
          </div>
    
          <!-- Nav -->
          <nav class="navbar-nav nav-pills mx-auto">
            <div class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dashboards
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item active" href="<?= PROOT; ?>172.06.84.0/index">Default</a>
                <a class="dropdown-item " href="<?= PROOT; ?>172.06.84.0/election">Election</a>
                <a class="dropdown-item " href="javascript:;">...</a>
              </div>
            </div>
            <div class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle "
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
              >
                Pages
              </a>
              <ul class="dropdown-menu">
                <li class="dropend">
                  <a
                    class="dropdown-item d-flex "
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                  >
                    Customers <span class="material-symbols-outlined ms-auto">chevron_right</span>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item " href="./customers/customers.html">Customers</a>
                    <a class="dropdown-item " href="./customers/customer.html">Customer details</a>
                    <a class="dropdown-item " href="./customers/customer-new.html">New customer</a>
                  </div>
                </li>
                <li class="dropend">
                  <a
                    class="dropdown-item d-flex "
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                  >
                    Projects <span class="material-symbols-outlined ms-auto">chevron_right</span>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item " href="./projects/projects.html">Projects</a>
                    <a class="dropdown-item " href="./projects/project.html">Project overview</a>
                    <a class="dropdown-item " href="./projects/project-new.html">New project</a>
                  </div>
                </li>
                <li class="dropend">
                  <a
                    class="dropdown-item d-flex "
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                  >
                    Account <span class="material-symbols-outlined ms-auto">chevron_right</span>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item " href="./account/account.html">Account overview</a>
                    <a class="dropdown-item " href="./account/account-settings.html">Account settings</a>
                  </div>
                </li>
                <li class="dropend">
                  <a
                    class="dropdown-item d-flex "
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                  >
                    E-commerce <span class="material-symbols-outlined ms-auto">chevron_right</span>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item " href="./ecommerce/products.html">Products</a>
                    <a class="dropdown-item " href="./ecommerce/orders.html">Orders</a>
                    <a class="dropdown-item " href="./ecommerce/invoice.html">Invoice</a>
                    <a class="dropdown-item " href="./ecommerce/pricing.html">Pricing</a>
                  </div>
                </li>
                <li class="dropend">
                  <a
                    class="dropdown-item d-flex "
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                  >
                    Posts <span class="material-symbols-outlined ms-auto">chevron_right</span>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item " href="./posts/categories.html">Categories</a>
                    <a class="dropdown-item " href="./posts/posts.html">Posts</a>
                    <a class="dropdown-item " href="./posts/post-new.html">New post</a>
                  </div>
                </li>
                <li class="dropend">
                  <a class="dropdown-item d-flex" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    Authentication <span class="material-symbols-outlined ms-auto">chevron_right</span>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="./auth/sign-in.html" target="_blank">Sign in</a>
                    <a class="dropdown-item" href="./auth/sign-up.html" target="_blank">Sign up</a>
                    <a class="dropdown-item" href="./auth/password-reset.html" target="_blank">Password reset</a>
                    <a class="dropdown-item" href="./auth/verification-code.html" target="_blank">Verification code</a>
                    <a class="dropdown-item" href="./auth/error.html" target="_blank">Error</a>
                  </div>
                </li>
                <li class="dropend">
                  <a
                    class="dropdown-item d-flex "
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                  >
                    Misc <span class="material-symbols-outlined ms-auto">chevron_right</span>
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item " href="./other/calendar.html">Calendar</a>
                  </div>
                </li>
              </ul>
            </div>
            <div class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Emails</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="./emails/account-confirmation.html" target="_blank">Account confirmation</a>
                <a class="dropdown-item" href="./emails/new-post.html" target="_blank">New post</a>
                <a class="dropdown-item" href="./emails/order-confirmation.html" target="_blank">Order confirmation</a>
                <a class="dropdown-item" href="./emails/password-reset.html" target="_blank">Password reset</a>
                <a class="dropdown-item" href="./emails/product-update.html" target="_blank">Product update</a>
              </div>
            </div>
            <div class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Modals</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#productModal" data-bs-toggle="offcanvas" aria-controls="productModal">Product</a>
                <a class="dropdown-item" href="#orderModal" data-bs-toggle="offcanvas" aria-controls="orderModal">Order</a>
                <a class="dropdown-item" href="#eventModal" data-bs-toggle="modal" aria-controls="eventModal">Event</a>
              </div>
            </div>
            <div class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle "
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Documentation
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item " href="./docs/getting-started.html">Getting started</a>
                <a class="dropdown-item " href="./docs/components.html">Components</a>
                <a class="dropdown-item d-flex " href="./docs/changelog.html"
                  >Changelog <span class="badge text-bg-primary ms-auto">1.0.6</span></a
                >
              </div>
            </div>
          </nav>
    
          <!-- Divider -->
          <hr class="my-4 d-xl-none" />
    
          <!-- Nav -->
          <nav class="navbar-nav nav-pills">
            <div class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" data-bs-settings-switcher aria-expanded="false">
                <span class="material-symbols-outlined">settings</span><span class="d-xl-none ms-3">Settings</span>
              </a>
              <div class="dropdown-menu ">
                <!-- Color mode -->
                <h6 class="dropdown-header">Color mode</h6>
                <a class="dropdown-item d-flex" data-bs-theme-value="light" href="#" role="button"> <span class="material-symbols-outlined me-2">light_mode</span> Light </a>
                <a class="dropdown-item d-flex" data-bs-theme-value="dark" href="#" role="button"> <span class="material-symbols-outlined me-2">dark_mode</span> Dark </a>
                <a class="dropdown-item d-flex" data-bs-theme-value="auto" href="#" role="button"> <span class="material-symbols-outlined me-2">contrast</span> Auto </a>
              
                <!-- Navigation position -->
                <hr class="dropdown-divider" />
                <h6 class="dropdown-header">Navigation position</h6>
                <a class="dropdown-item d-flex" data-bs-navigation-position-value="sidenav" href="#" role="button">
                  <span class="material-symbols-outlined me-2">keyboard_tab_rtl</span> Sidenav
                </a>
                <a class="dropdown-item d-flex" data-bs-navigation-position-value="topnav" href="#" role="button">
                  <span class="material-symbols-outlined me-2">vertical_align_top</span> Topnav
                </a>
              
                <!-- Sidenav sizing -->
                <div class="sidenav-sizing">
                  <hr class="dropdown-divider" />
                  <h6 class="dropdown-header">Sidenav sizing</h6>
                  <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="base" href="#" role="button">
                    <span class="material-symbols-outlined me-2">density_large</span> Base
                  </a>
                  <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="md" href="#" role="button">
                    <span class="material-symbols-outlined me-2">density_medium</span> Medium
                  </a>
                  <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="sm" href="#" role="button">
                    <span class="material-symbols-outlined me-2">density_small</span> Small
                  </a>
                </div>
              </div>
            </div>
            <div class="nav-item">
              <a class="nav-link" href="https://themes.getbootstrap.com/product/dashbrd/" target="_blank">
                <span class="material-symbols-outlined">local_mall</span><span class="d-xl-none ms-3">Go to product page</span>
              </a>
            </div>
            <div class="nav-item">
              <a class="nav-link" href="mailto:yevgenysim+simpleqode@gmail.com">
                <span class="material-symbols-outlined">alternate_email</span><span class="d-xl-none ms-3">Contact us</span>
              </a>
            </div>
          </nav>
        </div>
      </div>
    </nav>
    
    <!-- Topnav (toolbar) -->
    <nav class="navbar topnav-toolbar d-none d-xl-flex px-xl-6">
      <div class="container flex-column align-items-stretch">
        <div class="row">
          <div class="col">
            <!-- Search -->
            <div class="input-group" style="max-width: 400px">
              <input class="form-control" id="topnavSearchInput" type="search" placeholder="Search" aria-label="Search" aria-describedby="navbarSearch" />
              <span class="input-group-text" id="navbarSearch">
                <kbd class="badge bg-body-secondary text-body">⌘</kbd>
                <kbd class="badge bg-body-secondary text-body ms-1">K</kbd>
              </span>
            </div>
          </div>
          <div class="col-auto d-flex">
            <!-- User -->
            <div class="dropdown my-n2">
              <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                  <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                </span>
                <span class="d-none d-xl-block"><?= $fullName; ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="./account/account.html">Account</a></li>
                <li><a class="dropdown-item" href="./auth/password-reset.html" target="_blank">Change password</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
              </ul>
            </div>
    
            <!-- Divider -->
            <div class="vr align-self-center bg-dark mx-2"></div>
    
            <!-- Notifications -->
            <div class="dropdown ">
              <button class="btn btn-link" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <span class="material-symbols-outlined scale-125">notifications</span>
                <span class="position-absolute top-0 end-0 m-3 p-1 bg-warning rounded-circle">
                  <span class="visually-hidden">New notifications</span>
                </span>
              </button>
              <div class="dropdown-menu dropdown-menu-end" style="width: 350px">
                <!-- Header -->
                <div class="row">
                  <div class="col">
                    <h6 class="dropdown-header me-auto">Notifications</h6>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined me-1">done_all</span> Mark all as read</button>
                    <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined">settings</span></button>
                  </div>
                </div>
            
                <!-- Items -->
                <div class="list-group list-group-flush px-4">
                  <div class="list-group-item border-style-dashed px-0">
                    <div class="row gx-3">
                      <div class="col-auto">
                        <div class="avatar avatar-sm">
                          <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                        </div>
                      </div>
                      <div class="col">
                        <p class="text-body mb-2">
                          <span class="fw-semibold">Emily T.</span> commented on your post <br /><small class="text-body-secondary">5 minutes ago</small>
                        </p>
                        <div class="card">
                          <div class="card-body p-3">Love the new dashboard layout! Super clean and easy to navigate 🔥</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item border-style-dashed px-0">
                    <div class="row gx-3">
                      <div class="col-auto">
                        <div class="avatar avatar-sm">
                          <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                        </div>
                      </div>
                      <div class="col">
                        <p class="text-body mb-2">
                          <span class="fw-semibold">Michael J.</span> requested changes on your post <br />
                          <small class="text-body-secondary">10 minutes ago</small>
                        </p>
                        <div class="card">
                          <div class="card-body p-3">
                            <p class="mb-2">Could you update the revenue chart with the latest data? Thanks!</p>
                            <p class="mb-0">
                              <button class="btn btn-sm btn-light" type="button">Update now</button>
                              <button class="btn btn-sm btn-link">Dismiss</button>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item border-style-dashed px-0">
                    <div class="row gx-3 align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <span class="material-symbols-outlined">error</span>
                        </div>
                      </div>
                      <div class="col">
                        <p class="text-body mb-0">
                          <span class="fw-semibold">System alert</span> - Build failed <br />
                          <small class="text-body-secondary">1 hour ago</small>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>