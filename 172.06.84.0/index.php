<?php

require_once("../connection/conn.php");

if (!cadminIsLoggedIn()) {
    cadminLoginErrorRedirect();
}


include ('includes/header.inc.php');
include ('includes/top-nav.inc.php');
include ('includes/left-nav.inc.php');

include ('includes/main-topbar.inc.php');

?>



    
    
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
                  <a class="dropdown-item active" href="./index.html">Default</a>
                  <a class="dropdown-item " href="./dashboards/crypto.html">Crypto</a>
                  <a class="dropdown-item " href="./dashboards/saas.html">SaaS</a>
                </div>
              </div>
              <div class="nav-item dropend">
                <a
                  class="nav-link "
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  data-bs-auto-close="outside"
                  aria-expanded="false"
                >
                  <span class="material-symbols-outlined">auto_stories</span>
                  <span class="ms-3 d-xl-none">Pages</span>
                </a>
                <ul class="dropdown-menu ms-xl-3">
                  <li>
                    <h6 class="dropdown-header d-none d-xl-block">Pages</h6>
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
                  <span class="ms-3 d-xl-none">Emails</span>
                </a>
                <div class="dropdown-menu ms-xl-3">
                  <h6 class="dropdown-header d-none d-xl-block">Emails</h6>
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
                  <span class="ms-3 d-xl-none">Modals</span>
                </a>
                <div class="dropdown-menu ms-xl-3">
                  <h6 class="dropdown-header d-none d-xl-block">Modals</h6>
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
                <a class="nav-link" href="mailto:yevgenysim+simpleqode@gmail.com" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Contact us">
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
          <a class="navbar-brand fs-5 fw-bold text-xl-center mb-xl-4" href="./index.html">
            <i class="fs-4 text-secondary" data-duoicon="box-2"></i> <span class="d-xl-none ms-1">Dashbrd</span>
          </a>
    
          <!-- User -->
          <div class="d-flex ms-auto d-xl-none">
            <div class="dropdown my-n2">
              <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                  <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                </span>
                <span class="d-none d-xl-block">John Williams</span>
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
                  <a class="dropdown-item active" href="./index.html">Default</a>
                  <a class="dropdown-item " href="./dashboards/crypto.html">Crypto</a>
                  <a class="dropdown-item " href="./dashboards/saas.html">SaaS</a>
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
          <a class="navbar-brand d-flex align-items-center fs-5 fw-bold px-xl-3 mb-xl-4" href="./index.html">
            <i class="fs-4 text-secondary me-2" data-duoicon="box-2"></i> Dashbrd
          </a>
    
          <!-- User -->
          <div class="d-flex ms-auto d-xl-none">
            <div class="dropdown my-n2">
              <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                  <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                </span>
                <span class="d-none d-xl-block">John Williams</span>
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
                    <a class="nav-link active" href="./index.html">Default</a>
                    <a class="nav-link " href="./dashboards/crypto.html">Crypto</a>
                    <a class="nav-link " href="./dashboards/saas.html">SaaS</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#customers"
                  role="button"
                  aria-expanded="false"
                  aria-controls="customers"
                >
                  <span class="material-symbols-outlined me-3">group</span> Customers
                </a>
                <div class="collapse " id="customers">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="./customers/customers.html">Customers</a>
                    <a class="nav-link " href="./customers/customer.html">Customer details</a>
                    <a class="nav-link " href="./customers/customer-new.html">New customer</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#projects"
                  role="button"
                  aria-expanded="false"
                  aria-controls="projects"
                >
                  <span class="material-symbols-outlined me-3">list_alt</span> Projects
                </a>
                <div class="collapse " id="projects">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="./projects/projects.html">Projects</a>
                    <a class="nav-link " href="./projects/project.html">Project overview</a>
                    <a class="nav-link " href="./projects/project-new.html">New project</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#account"
                  role="button"
                  aria-expanded="false"
                  aria-controls="account"
                >
                  <span class="material-symbols-outlined me-3">person</span> Account
                </a>
                <div class="collapse " id="account">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="./account/account.html">Account overview</a>
                    <a class="nav-link " href="./account/account-settings.html">Account settings</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#ecommerce"
                  role="button"
                  aria-expanded="false"
                  aria-controls="ecommerce"
                >
                  <span class="material-symbols-outlined me-3">shopping_cart</span> E-commerce
                </a>
                <div class="collapse " id="ecommerce">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="./ecommerce/products.html">Products</a>
                    <a class="nav-link " href="./ecommerce/orders.html">Orders</a>
                    <a class="nav-link " href="./ecommerce/invoice.html">Invoice</a>
                    <a class="nav-link " href="./ecommerce/pricing.html">Pricing</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#posts"
                  role="button"
                  aria-expanded="false"
                  aria-controls="posts"
                >
                  <span class="material-symbols-outlined me-3">text_fields</span> Posts
                </a>
                <div class="collapse " id="posts">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="./posts/categories.html">Categories</a>
                    <a class="nav-link " href="./posts/posts.html">Posts</a>
                    <a class="nav-link " href="./posts/post-new.html">New post</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link"
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#authentication"
                  role="button"
                  aria-expanded="false"
                  aria-controls="authentication"
                >
                  <span class="material-symbols-outlined me-3">login</span> Authentication
                </a>
                <div class="collapse" id="authentication">
                  <nav class="nav nav-pills">
                    <a class="nav-link" href="./auth/sign-in.html" target="_blank">Sign in</a>
                    <a class="nav-link" href="./auth/sign-up.html" target="_blank">Sign up</a>
                    <a class="nav-link" href="./auth/password-reset.html" target="_blank">Password reset</a>
                    <a class="nav-link" href="./auth/verification-code.html" target="_blank">Verification code</a>
                    <a class="nav-link" href="./auth/error.html" target="_blank">Error</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#other"
                  role="button"
                  aria-expanded="false"
                  aria-controls="other"
                >
                  <span class="material-symbols-outlined me-3">category</span> Misc
                </a>
                <div class="collapse " id="other">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="./other/calendar.html">Calendar</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#emails" role="button" aria-expanded="false" aria-controls="emails">
                  <span class="material-symbols-outlined me-3">mail</span> Emails
                </a>
                <div class="collapse" id="emails">
                  <nav class="nav nav-pills">
                    <a class="nav-link" href="./emails/account-confirmation.html" target="_blank">Account confirmation</a>
                    <a class="nav-link" href="./emails/new-post.html" target="_blank">New post</a>
                    <a class="nav-link" href="./emails/order-confirmation.html" target="_blank">Order confirmation</a>
                    <a class="nav-link" href="./emails/password-reset.html" target="_blank">Password reset</a>
                    <a class="nav-link" href="./emails/product-update.html" target="_blank">Product update</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#mdoals" role="button" aria-expanded="false" aria-controls="mdoals">
                  <span class="material-symbols-outlined me-3">tooltip</span> Modals
                </a>
                <div class="collapse" id="mdoals">
                  <nav class="nav nav-pills">
                    <a class="nav-link" href="#productModal" data-bs-toggle="offcanvas" aria-controls="productModal">Product</a>
                    <a class="nav-link" href="#orderModal" data-bs-toggle="offcanvas" aria-controls="orderModal">Order</a>
                    <a class="nav-link" href="#eventModal" data-bs-toggle="modal" aria-controls="eventModal">Event</a>
                  </nav>
                </div>
              </div>
            </nav>
    
            <!-- Heading -->
            <h3 class="fs-base px-3 mb-4">Documentation</h3>
    
            <!-- Nav -->
            <nav class="navbar-nav nav-pills mb-xl-7">
              <div class="nav-item">
                <a class="nav-link " href="./docs/getting-started.html">
                  <span class="material-symbols-outlined me-3">sticky_note_2</span> Getting started
                </a>
              </div>
              <div class="nav-item">
                <a class="nav-link " href="./docs/components.html">
                  <span class="material-symbols-outlined me-3">deployed_code</span> Components
                </a>
              </div>
              <div class="nav-item">
                <a class="nav-link " href="./docs/changelog.html">
                  <span class="material-symbols-outlined me-3">list_alt</span> Changelog
                  <span class="badge text-bg-primary ms-auto">1.0.6</span>
                </a>
              </div>
            </nav>
    
            <!-- Divider -->
            <hr class="my-4 d-xl-none" />
    
            <!-- Nav -->
            <nav class="navbar-nav nav-pills d-xl-none mb-7">
              <div class="nav-item">
                <a class="nav-link" href="https://themes.getbootstrap.com/product/dashbrd/" target="_blank">
                  <span class="material-symbols-outlined me-3">local_mall</span> Go to product page
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
        <a class="navbar-brand d-flex align-items-center fs-5 fw-bold" href="./index.html"
          ><i class="fs-4 text-secondary me-2" data-duoicon="box-2"></i>Dashbrd</a
        >
    
        <!-- User -->
        <div class="d-flex ms-auto d-xl-none">
          <div class="dropdown my-n2">
            <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
              </span>
              <span class="d-none d-xl-block">John Williams</span>
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
                <a class="dropdown-item active" href="./index.html">Default</a>
                <a class="dropdown-item " href="./dashboards/crypto.html">Crypto</a>
                <a class="dropdown-item " href="./dashboards/saas.html">SaaS</a>
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
                <span class="d-none d-xl-block">John Williams</span>
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

    <!-- Main -->
    <main class="main px-lg-6">
      <!-- Content -->
      <div class="container-lg">
        <!-- Page content -->
        <div class="row align-items-center">
          <div class="col-12 col-md-auto order-md-1 d-flex align-items-center justify-content-center mb-4 mb-md-0">
            <div class="avatar text-info me-2">
              <i class="fs-4" data-duoicon="world"></i>
            </div>
            San Francisco, CA –&nbsp;<span>8:00 PM</span>
          </div>
          <div class="col-12 col-md order-md-0 text-center text-md-start">
            <h1>Hello, John</h1>
            <p class="fs-lg text-body-secondary mb-0">Here's a summary of your account activity for this week.</p>
          </div>
        </div>

        <!-- Divider -->
        <hr class="my-8" />

        <!-- Stats -->
        <div class="row mb-8">
          <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
            <div class="card bg-body-tertiary border-transparent">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <!-- Heading -->
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Earned</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">$1,250</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="credit-card"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
            <div class="card bg-body-tertiary border-transparent">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <!-- Heading -->
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Hours logged</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">35.5 hrs</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="clock"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-md-0">
            <div class="card bg-body-tertiary border-transparent">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <!-- Heading -->
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Avg. time</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">2:55 hrs</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="slideshow"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xxl-3">
            <div class="card bg-body-tertiary border-transparent">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    <!-- Heading -->
                    <h4 class="fs-sm fw-normal text-body-secondary mb-1">Weekly growth</h4>

                    <!-- Text -->
                    <div class="fs-4 fw-semibold">14.5%</div>
                  </div>
                  <div class="col-auto">
                    <!-- Avatar -->
                    <div class="avatar avatar-lg bg-body text-primary">
                      <i class="fs-4" data-duoicon="discount"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-xxl-8">
            <!-- Performance -->
            <div class="card mb-6">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Performance</h3>
                  </div>
                  <div class="col-auto fs-sm me-n3">
                    <span class="material-symbols-outlined text-primary me-1">circle</span>
                    Total
                  </div>
                  <div class="col-auto fs-sm">
                    <span class="material-symbols-outlined text-dark me-1">circle</span>
                    Tracked
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas class="chart-canvas" id="userPerformanceChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Projects -->
            <div class="card mb-6 mb-xxl-0">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Active projects</h3>
                  </div>
                  <div class="col-auto my-n3 me-n3">
                    <a class="btn btn-sm btn-link" href="./projects/projects.html">
                      Browse all
                      <span class="material-symbols-outlined">arrow_right_alt</span>
                    </a>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead>
                    <th class="fs-sm">Title</th>
                    <th class="fs-sm">Status</th>
                    <th class="fs-sm">Author</th>
                    <th class="fs-sm">Team</th>
                  </thead>
                  <tbody>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-1.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Filters AI</div>
                            <div class="fs-sm text-body-secondary">Updated on Apr 10, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          Michael Johnson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Michael Johnson">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-2.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Design landing page</div>
                            <div class="fs-sm text-body-secondary">Created on Mar 05, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                          Emily Thompson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar text-primary">
                            <i class="fs-4" data-duoicon="book-3"></i>
                          </div>
                          <div class="ms-4">
                            <div>Update documentation</div>
                            <div class="fs-sm text-body-secondary">Created on Jan 22, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary">In Testing</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                          </div>
                          Michael Johnson
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Emily Thompson">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar">
                            <img class="avatar-img" src="./assets/img/projects/project-3.png" alt="..." />
                          </div>
                          <div class="ms-4">
                            <div>Update Touche</div>
                            <div class="fs-sm text-body-secondary">Updated on Apr 14, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-success-subtle text-success">Ready to Ship</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                          Jessica Miller
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Olivia Davis">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Jessica Miller">
                            <img class="avatar-img" src="./assets/img/photos/photo-5.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr onclick="window.location.href='./projects/project.html'" role="link" tabindex="0">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar text-primary">
                            <i class="fs-4" data-duoicon="box"></i>
                          </div>
                          <div class="ms-4">
                            <div>Add Transactions</div>
                            <div class="fs-sm text-body-secondary">Created on Apr 25, 2024</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-light text-body-secondary">Backlog</span>
                      </td>
                      <td>
                        <div class="d-flex align-items-center text-nowrap">
                          <div class="avatar avatar-xs me-2">
                            <img class="avatar-img" src="./assets/img/photos/photo-4.jpg" alt="..." />
                          </div>
                          Olivia Davis
                        </div>
                      </td>
                      <td>
                        <div class="avatar-group">
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Robert Garcia">
                            <img class="avatar-img" src="./assets/img/photos/photo-3.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="John Williams">
                            <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                          </div>
                          <div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-title="Emily Thompson">
                            <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-12 col-xxl-4">
            <!-- Goals -->
            <div class="card mb-6">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="fs-6 mb-0">Goals</h3>
                  </div>
                  <div class="col-auto my-n3 me-n3">
                    <button class="btn btn-sm btn-link" type="button">+ Add</a>
                  </div>
                </div>
              </div>
              <div class="card-body py-3">
                <div class="list-group list-group-flush">
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-primary"
                            role="progressbar"
                            aria-label="Increase monthly revenue"
                            aria-valuenow="75"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="75%"
                            style="--bs-progress-circle-value: 75"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Increase monthly revenue</h6>
                        <span class="fs-sm text-body-secondary">$10,000</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Mar 15</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-secondary"
                            role="progressbar"
                            aria-label="Launch new feature"
                            aria-valuenow="50"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="50%"
                            style="--bs-progress-circle-value: 50"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Launch new feature</h6>
                        <span class="fs-sm text-body-secondary">Dark mode</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Oct 01</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-danger"
                            role="progressbar"
                            aria-label="Grow user base"
                            aria-valuenow="45"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="45%"
                            style="--bs-progress-circle-value: 45"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Grow user base</h6>
                        <span class="fs-sm text-body-secondary">75%</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Dec 12</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-primary"
                            role="progressbar"
                            aria-label="Improve customer satisfaction"
                            aria-valuenow="60"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="60%"
                            style="--bs-progress-circle-value: 60"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Improve customer satisfaction</h6>
                        <span class="fs-sm text-body-secondary">85%</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Dec 15</span>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item px-0">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <div
                            class="progress progress-circle text-success"
                            role="progressbar"
                            aria-label="Reduce response time"
                            aria-valuenow="100"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            data-bs-toggle="tooltip"
                            data-bs-title="100%"
                            style="--bs-progress-circle-value: 100"
                          ></div>
                        </div>
                      </div>
                      <div class="col ms-n2">
                        <h6 class="fs-base fw-normal mb-0">Reduce response time</h6>
                        <span class="fs-sm text-body-secondary">1hr</span>
                      </div>
                      <div class="col-auto">
                        <span class="text-body-secondary">Jan 01</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Activity -->
            <div class="card">
              <div class="card-header">
                <h3 class="fs-6 mb-0">Recent activity</h3>
              </div>
              <div class="card-body">
                <ul class="activity">
                  <li data-icon="thumb_up">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1hr ago</span></h6>
                      <p class="mb-0">Liked a post by @john_doe</p>
                    </div>
                  </li>
                  <li data-icon="chat_bubble">
                    <div>
                      <h6 class="fs-base mb-1">Jessica Miller <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Commented on a photo</p>
                    </div>
                  </li>
                  <li data-icon="share">
                    <div>
                      <h6 class="fs-base mb-1">Emily Thompson <span class="fs-sm fw-normal text-body-secondary ms-1">3hr ago</span></h6>
                      <p class="mb-0">Shared an article: "Top 10 Travel Destinations"</p>
                    </div>
                  </li>
                  <li data-icon="person_add">
                    <div>
                      <h6 class="fs-base mb-1">You <span class="fs-sm fw-normal text-body-secondary ms-1">1 day ago</span></h6>
                      <p class="mb-0">Started following @jane_smith</p>
                    </div>
                  </li>
                  <li data-icon="account_circle">
                    <div>
                      <h6 class="fs-base mb-1">Olivia Davis <span class="fs-sm fw-normal text-body-secondary ms-1">2 days ago</span></h6>
                      <p class="mb-0">Updated profile picture</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    





<div class="card mt-4" style='background-color: #37404a;'>
    <div class="card-body">  
        <h4 class='header-title mb-3 text-left' style='color:rgb(170, 184, 197);'>General Overview</h4><hr>
        <p>To start a fresh new election, go to the <a href="election" class="text-secondary">Add Elections</a> tab.</p>
        <p>To setup the <u>positions</u> under their respective elections, go to the <a href="positions" class="text-secondary">Manage Positions & Elections</a> tab.</p>
        <p>For the adding up of the <u>candidates</u>, head to the <a href="contestants" class="text-secondary">Add Contestants</a> tab. </p>
        <p>Go to <a href="contestants" class="text-secondary">Manage Contestants</a> tab to setup the contestants.</p>
        <p>Registrars you wish to add to allow them to vote can be managed at the <a href="registrar" class="text-secondary">Voters</a> tab.</p>
        <p>It is highly recommended to change <b>admin</b>'s  password at the <a href="settings.php?cp=1" class="text-secondary">Change Password</a> tab before conducting an election.</p>
    </div>
</div>


<div class="modal fade" id="electionModal" tabindex="-1" role="dialog" aria-labelledby="electionLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark border-secondary">

            <div class="modal-header">
                <h5 class="modal-title" id="electionLabel">Start an election</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form id="submitElectionSession" method="POST">
                <div class="modal-body">

                    <?php
                        $query = "SELECT * FROM election WHERE session = ?";
                        $statement = $conn->prepare($query);
                        $statement->execute([0]);
                        $not_started_election_result = $statement->fetchAll();
                        $not_started_election_count = $statement->rowCount();
                        if ($not_started_election_count > 0) {
                    ?>

                        <label class="control-label" for="election-session">Elections</label>
                        <select class="form-control form-control-sm form-control-dark" name="election-session" id="election-session" required="required">
                            <option>Select Election</option> 
                            <?php foreach ($not_started_election_result as $row): ?>
                                <option value="<?= $row["eid"]; ?>"><?= ucwords($row["election_name"]); ?> / <?= ucwords($row["election_by"]); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <br>
                        <div class="form-group">
                            <label class="control-label">Create voting end time session.</label>
                            <input type="datetime-local" name="ctimer" id="ctimer" class="form-control form-control-sm form-control-dark" required>
                        </div>
                    <?php } else { ?>
                        <div class='well'>There aren't any election available to start. You can <a href='election'>add one</a></div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-outline-info">Start!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
    include ('includes/main-footer.inc.php');
    include ('includes/footer.inc.php');
?>

<script type="text/javascript">
    feather.replace();
</script>