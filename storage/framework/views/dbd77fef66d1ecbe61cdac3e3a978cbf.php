<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
            color: #2d3748;
        }

        .wrapper {
            background-color: #f8fafc;
            padding: 40px 0;
        }

        .container {
            width: 100%;
            padding: 20px;
            background-color: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            padding: 10px 0;
            text-align: center;
            background-color: #f5f8fa;
            border-bottom: 1px solid #e2e8f0;
        }

        .header img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 50%;
            /* nếu muốn bo tròn tuyệt đối */
            display: block;
            margin: 0 auto;
        }

        h1 {
            color: #2d3748;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        p {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background-color: #0d6efd;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;

        }

        .btn:hover {
            background-color: #0b5ed7;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #718096;
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            margin: 0;
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <a href="https://www.facebook.com/profile.php?id=100069752143507" target="_blank">
                    <img src="https://tnttgxmyvan.org/images/site/FAVICON_default.png"
                        alt="Đoàn TNTT giáo xứ Mỹ Vân">
                </a>
            </div>
            <h1>Xin chào <?php echo e($user->full_name); ?>, </h1>
            <p>Chúng mình đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Nếu bạn muốn đặt lại mật khẩu,
                hãy
                nhấp vào nút bên dưới:</p>
            <div class="text-center"><a href="<?php echo e($actionLink); ?>" class="btn" style="color: #ffffff">Đặt lại mật
                    khẩu</a></div>
            <p>Xin lưu ý rằng liên kết này sẽ hết hạn sau 15 phút.</p>
            <p>Nếu bạn không gửi yêu cầu này, hãy bỏ qua email này.</p>
            <p>Thân chào!<br>MV</p>
            <div class="footer">
                <p>&copy; <?php echo e(date('Y')); ?> Đoàn TNTT Giáo Xứ Mỹ Vân.</p>
            </div>
        </div>
    </div>
</body>

</html><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/Mail/auth/forgot-password.blade.php ENDPATH**/ ?>