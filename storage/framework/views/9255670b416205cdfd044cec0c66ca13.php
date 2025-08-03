<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo thay đổi mật khẩu</title>
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
            background-color: #1a202c;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #2d3748;
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
            <p>Mật khẩu của bạn đã được thay đổi thành công. Bạn có thể sử dụng mật khẩu mới để đăng nhập vào tài khoản
                của mình.</p>
            <p><strong>Email: </strong> <?php echo e($user->email); ?></p>
            <p><strong>Mã tài khoản: </strong> <?php echo e($user->account_code); ?></p>
            <strong>Mật khẩu mới:</strong>
            <span style="color: #dadada"><?php echo e($new_password); ?></span>
            <p>Nếu bạn không thực hiện yêu cầu thay đổi mật khẩu này, vui lòng liên hệ ngay với <a 
                    style="color: blue; font-weight: bold"
                    href="https://www.facebook.com/profile.php?id=100069752143507" target="_blank">trang Thiếu Nhi</a>
                để bảo
                mật tài khoản của bạn.</p>
            <p>Khi bạn có bất kỳ thắc mắc nào, đừng ngần ngại liên hệ với chúng mình nhé.</p>
            <p>Thân chào!<br>MV</p>
            <div class="footer">
                <p>&copy; <?php echo e(date('Y')); ?> Đoàn TNTT Giáo Xứ Mỹ Vân.</p>
            </div>
        </div>
    </div>


</body>

</html>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/Mail/auth/notify-changed-password.blade.php ENDPATH**/ ?>