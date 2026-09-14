<div>
    
    <div class="user-info-dropdown">
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                <span class="user-icon">
                    <img src="{{ $user->picture }}" alt="Ảnh đại diện của {{ $user->full_name }}" />
                </span>
                <span class="user-name">{{ $user->lastName . ' ' . $user->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                <a class="dropdown-item" href="{{route('admin.profile')}}"><i class="dw dw-user1"></i> Tài khoản</a>
                <a class="dropdown-item disabled" href=""><i class="dw dw-help"></i> Trợ giúp</a>
                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="dw dw-logout"></i> Đăng xuất
                </a>
                <form action="{{ route('admin.logout') }}" id="logout-form" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

</div>
