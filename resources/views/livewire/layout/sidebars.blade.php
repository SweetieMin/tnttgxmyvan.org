<div>
    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="{{ route('admin.dashboard') }}">
                <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'LOGO_default.png' }}"
                    alt=" Logo Đoàn TNTT Giáo Xứ Mỹ Vân" class="dark-logo site_logo" />
                <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'LOGO_default.png' }}"
                    alt=" Logo Đoàn TNTT Giáo Xứ Mỹ Vân" class="light-logo site_logo" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">

                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="dropdown-toggle no-arrow {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <span class="micon bi bi-house"></span><span class="mtext">Trang Chủ</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.score') }}"
                            class="dropdown-toggle no-arrow {{ Route::is('admin.score') ? 'active' : '' }}">
                            <span class="micon fa fa-graduation-cap"></span><span class="mtext">Xem Điểm</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <div class="sidebar-small-cap">Hộp thư</div>
                    </li>

                    <li>
                        <a href="{{ route('admin.support.feedback') }}"
                            class="dropdown-toggle no-arrow {{ Route::is('admin.support.feedback') ? 'active' : '' }}">
                            <span class="micon dw dw-email-1"></span><span class="mtext">Góp Ý</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.support.complaint') }}"
                            class="dropdown-toggle no-arrow {{ Route::is('admin.support.complaint') ? 'active' : '' }}">
                            <span class="micon dw dw-warning-1"></span><span class="mtext">Khiếu Nại</span>
                        </a>
                    </li>

                    @if ($hasResolvePermission)
                        <li>
                            <a href="{{ route('admin.support.resolve') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.support.resolve') ? 'active' : '' }}">
                                <span class="micon dw dw-checked"></span><span class="mtext">Giải Quyết</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasAssignedPermission)
                        <li>
                            <a href="{{ route('admin.support.assigned') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.support.assigned') ? 'active' : '' }}">
                                <span class="micon dw dw-group"></span><span class="mtext">Quản lý</span>
                            </a>
                        </li>
                    @endif
                    
                    @if ($isShowAttendanceMenu)
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <div class="sidebar-small-cap">ĐIỂM DANH</div>
                        </li>
                    @endif

                    @if ($hasRewardPermission)
                        <li>
                            <a href="{{ route('admin.attendance.reward') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.attendance.reward') ? 'active' : '' }}">
                                <span class="micon bi bi-award"></span><span class="mtext">Khen thưởng</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasDisciplinePermission)
                        <li>
                            <a href="{{ route('admin.attendance.discipline') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.attendance.discipline') ? 'active' : '' }}">
                                <span class="micon bi bi-exclamation-triangle"></span><span class="mtext">Kỷ
                                    luật</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasConfirmPermission)
                        <li>
                            <a href="{{ route('admin.attendance.confirm') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.attendance.confirm') ? 'active' : '' }}">
                                <span class="micon bi bi-journal-check"></span><span class="mtext">Xét Duyệt</span>
                            </a>
                        </li>
                    @endif

                    @if ($isShowPersonnelMenu)
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <div class="sidebar-small-cap">NHÂN SỰ</div>
                        </li>
                    @endif

                    @if ($hasScouterPermission)
                        <li>
                            <a href="{{ route('admin.personnel.scouter') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.personnel.scouter') ? 'active' : '' }}">
                                <span class="micon fa fa-user-md"></span><span class="mtext">Huynh Trưởng</span>
                            </a>
                        </li>
                    @endif



                    @if ($hasChildrenPermission)
                        <li>
                            <a href="{{ route('admin.personnel.children') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.personnel.children') ? 'active' : '' }}">
                                <span class="micon fa fa-child"></span><span class="mtext">Thiếu Nhi</span>
                            </a>
                        </li>
                    @endif


                    @if ($isShowManageMenu)
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <div class="sidebar-small-cap">QUẢN LÝ</div>
                        </li>
                    @endif


                    @if ($hasCoursePermission)
                        <li>
                            <a href="{{ route('admin.management.course') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.course') ? 'active' : '' }}">
                                <span class="micon dw dw-open-book-2"></span><span class="mtext">Lớp Giáo Lý</span>
                            </a>
                        </li>
                    @endif



                    @if ($hasSectorPermission)
                        <li>
                            <a href="{{ route('admin.management.sector') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.sector') ? 'active' : '' }}">
                                <span class="micon fa fa-users"></span><span class="mtext">Ngành Sinh Hoạt</span>
                            </a>
                        </li>
                    @endif


                    @if ($hasBiblePermission)
                        <li>
                            <a href="{{ route('admin.management.bible') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.bible') ? 'active' : '' }}">
                                <span class="micon dw dw-book2"></span><span class="mtext">Câu Kinh Thánh</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasRegulationPermission)
                        <li>
                            <a href="{{ route('admin.management.regulation') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.regulation') ? 'active' : '' }}">
                                <span class="micon dw dw-open-book"></span><span class="mtext">Nội Quy</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasSchedulePermission)
                        <li>
                            <a href="{{ route('admin.management.schedule') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.schedule') ? 'active' : '' }}">
                                <span class="micon bi bi-calendar3"></span><span class="mtext">Xếp Lịch Điểm
                                    Danh</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasTransactionPermission)
                        <li>
                            <a href="{{ route('admin.management.transaction') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.transaction') ? 'active' : '' }}">
                                <span class="micon bi bi-bank"></span><span class="mtext">Tiền Quỹ</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasNoticePermission)
                        <li>
                            <a href="{{ route('admin.management.notice') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.notice') ? 'active' : '' }}">
                                <span class="micon fa fa-bullhorn"></span><span class="mtext">Thông Báo</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasRolePermission)
                        <li>
                            <a href="{{ route('admin.management.role') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.role') ? 'active' : '' }}">
                                <span class="micon dw dw-user-13"></span><span class="mtext">Chức Vụ</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasManagerPermission)
                        <li>
                            <a href="{{ route('admin.management.permission') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.permission') ? 'active' : '' }}">
                                <span class="micon bi bi-shield-lock"></span><span class="mtext">Quyền Truy
                                    Cập</span>
                            </a>
                        </li>
                    @endif

                    @if ($hasActivityLogsPermission)
                        <li>
                            <a href="{{ route('admin.management.activity-logs') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.management.activity-logs') ? 'active' : '' }}">
                                <span class="micon bi bi-layout-text-sidebar-reverse"></span><span class="mtext">Nhật
                                    Ký</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <div class="sidebar-small-cap">CÀI ĐẶT</div>
                    </li>

                    <li>
                        <a href="{{ route('admin.profile') }}"S
                            class="dropdown-toggle no-arrow  {{ Route::is('admin.profile') ? 'active' : '' }}">
                            <span class="micon dw dw-user1"></span><span class="mtext">Tài Khoản</span>
                        </a>
                    </li>
                    @if ($hasGeneralSettingsPermission)
                        <li>
                            <a href="{{ route('admin.settings') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.settings') ? 'active' : '' }}">
                                <span class="micon bi bi-display "></span><span class="mtext">Trang Web</span>
                            </a>
                        </li>
                    @endif



                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li class="mt-2 text-center">
                        <span class="micon text-secondary fs-6">{{ config('app.version') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
