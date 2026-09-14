@extends('layout.profile')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    <section class=" about-section py-5">
        <div class="container ">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary">Giới thiệu về Đoàn Thiếu Nhi Thánh Thể</h1>
                <p class="text-muted">Giáo xứ Mỹ Vân – Nơi yêu thương, phục vụ và đồng hành cùng thiếu nhi</p>
                <hr class="w-25 mx-auto">
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <p class="lead">
                            <strong>Đoàn Thiếu Nhi Thánh Thể Giáo xứ Mỹ Vân</strong> là tổ chức giáo dục đức tin và nhân bản
                            dành cho các em thiếu nhi trong giáo xứ. Với mục tiêu <em>“Yêu mến Chúa Giêsu Thánh Thể – Phục
                                vụ thiếu nhi – Làm chứng cho Tin Mừng”</em>, Đoàn luôn là nơi ươm mầm đức tin cho thế hệ trẻ
                            tại Mỹ Vân.
                        </p>

                        <h4 class="mt-4 text-secondary">🌱 Sứ mạng</h4>
                        <p>
                            Chúng tôi tổ chức các hoạt động như: học giáo lý, sinh hoạt đội nhóm, tham dự Thánh Lễ, trại hè,
                            tĩnh tâm... Tất cả nhằm giúp các em thiếu nhi sống gắn bó với Chúa và trưởng thành trong đức
                            tin.
                        </p>

                        <h4 class="mt-4 text-secondary">💡 Giá trị cốt lõi</h4>
                        <ul>
                            <li>Trung thành với giáo huấn Công Giáo</li>
                            <li>Yêu thương – Phục vụ – Hi sinh</li>
                            <li>Đào tạo Huynh Trưởng có trách nhiệm và đời sống đạo đức</li>
                        </ul>

                        <h4 class="mt-4 text-secondary">🌐 Trang web chính thức</h4>
                        <p>
                            Website <strong>tnttgxmyvan.org</strong> là nơi kết nối giữa Ban Điều Hành, Huynh Trưởng, thiếu nhi
                            và phụ huynh. Đây là công cụ cập nhật thông báo, quản lý điểm danh, chia sẻ hình ảnh và hoạt
                            động đoàn.
                        </p>

                        <div class="text-end mt-4">
                            <em>“Tất cả vì thiếu nhi – tất cả để yêu mến Chúa Giêsu Thánh Thể.”</em>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
