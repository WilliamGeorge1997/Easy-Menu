 <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
     id="layout-navbar">
    @php
        $admin = auth('admin')->user();
        $adminRole = $admin?->roles?->first();
    @endphp
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
                                  🇬🇧 {{ __('dashboard/common.language_en') }}
                              </button>
                          </form>
                      </li>
                      <li>
                          <form action="{{ route('admin.language.switch', 'ar') }}" method="POST">
                              @csrf
                              <button type="submit" class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                                  🇸🇦 {{ __('dashboard/common.language_ar') }}
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
                        @if(!empty($admin?->image))
                            <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="w-px-40 h-px-40 rounded-circle object-fit-cover">
                         @else
                             <span class="avatar-initial rounded-circle bg-label-primary">
                                 <i class="icon-base bx bx-user icon-md"></i>
                             </span>
                         @endif
                      </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                         <div class="dropdown-item">
                              <div class="d-flex">
                                  <div class="flex-shrink-0 me-3">
                                      <div class="avatar avatar-online">
                                         @if(!empty($admin?->image))
                                             <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="w-px-40 h-px-40 rounded-circle object-fit-cover">
                                          @else
                                              <span class="avatar-initial rounded-circle bg-label-primary">
                                                  <i class="icon-base bx bx-user icon-md"></i>
                                              </span>
                                          @endif
                                      </div>
                                  </div>
                                  <div class="flex-grow-1">
                                      <h6 class="mb-0">{{ $admin?->name ?? __('dashboard/common.admin') }}</h6>
                                      <small class="text-body-secondary">{{ $adminRole?->display ?? $adminRole?->name ?? __('dashboard/common.admin') }}</small>
                                  </div>
                              </div>
                         </div>
                      </li>
                      <li>
                          <div class="dropdown-divider my-1"></div>
                      </li>
                      <li>
                          <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                              <i class="icon-base bx bx-user icon-md me-3"></i><span>{{ __('dashboard/admins.my_profile') }}</span>
                          </a>
                      </li>
                      <li>
                          <div class="dropdown-divider my-1"></div>
                      </li>
                      <li>
                         <form action="{{ route('admin.logout') }}" method="POST">
                             @csrf
                             <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start">
                                 <i class="icon-base bx bx-power-off icon-md me-3"></i><span>{{ __('dashboard/common.log_out') }}</span>
                             </button>
                         </form>
                      </li>
                  </ul>
              </li>
              <!--/ User -->
          </ul>
      </div>
  </nav>
