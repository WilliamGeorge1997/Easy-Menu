 <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
     id="layout-navbar">
     <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
         <ul class="navbar-nav flex-row align-items-center ms-auto">
             <!-- Language Switcher -->
              <li class="nav-item dropdown me-2">
                  <a class="nav-link dropdown-toggle hide-arrow p-0 d-flex align-items-center gap-1" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <i class="icon-base bx bx-globe icon-md"></i>
                      <span class="fw-semibold text-uppercase" style="font-size:13px;">{{ app()->getLocale() }}</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                          <form action="{{ route('admin.language.switch', 'en') }}" method="POST">
                              @csrf
                              <button type="submit" class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                                  🇬🇧 English
                              </button>
                          </form>
                      </li>
                      <li>
                          <form action="{{ route('admin.language.switch', 'ar') }}" method="POST">
                              @csrf
                              <button type="submit" class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                                  🇸🇦 العربية
                              </button>
                          </form>
                      </li>
                  </ul>
              </li>
              <!-- /Language Switcher -->

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                      data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                          <span class="avatar-initial rounded-circle bg-label-primary">
                              <i class="icon-base bx bx-user icon-md"></i>
                          </span>
                      </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                          <a class="dropdown-item" href="#">
                              <div class="d-flex">
                                  <div class="flex-shrink-0 me-3">
                                      <div class="avatar avatar-online">
                                          <span class="avatar-initial rounded-circle bg-label-primary">
                                              <i class="icon-base bx bx-user icon-md"></i>
                                          </span>
                                      </div>
                                  </div>
                                  <div class="flex-grow-1">
                                      <h6 class="mb-0">John Doe</h6>
                                      <small class="text-body-secondary">Admin</small>
                                  </div>
                              </div>
                          </a>
                      </li>
                      <li>
                          <div class="dropdown-divider my-1"></div>
                      </li>
                      <li>
                          <a class="dropdown-item" href="#">
                              <i class="icon-base bx bx-user icon-md me-3"></i><span>My Profile</span>
                          </a>
                      </li>
                      <li>
                          <a class="dropdown-item" href="#">
                              <i class="icon-base bx bx-cog icon-md me-3"></i><span>Settings</span>
                          </a>
                      </li>
                      <li>
                          <div class="dropdown-divider my-1"></div>
                      </li>
                      <li>
                          <a class="dropdown-item" href="javascript:void(0);">
                              <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
                          </a>
                      </li>
                  </ul>
              </li>
              <!--/ User -->
          </ul>
      </div>
  </nav>
