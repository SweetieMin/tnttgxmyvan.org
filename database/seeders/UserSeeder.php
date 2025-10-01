<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'holyName' => 'Giuse',
            'lastName' => 'Đặng Đình',
            'name' => 'Viên',
            'account_code' => 'MV21081010',
            'picture' => 'admin_default.png',
            'email' => 'tntt.myvan@gmail.com',
            'birthday' => '2010-08-21',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::create([
            'holyName' => 'Toma',
            'lastName' => 'Nguyễn Khắc',
            'name' => 'Huấn',
            'account_code' => 'MV19019797',
            'picture' => 'MV19019797-00.jpg',
            'token' => '6y5MgjYc3ThY2Bi5vwO3aC2LpOHHJUzWTWfZGIJeG7LWhuB6gO6grNcAsn1K',
            'email' => 'nguyenkhachuan1997@gmail.com',
            'birthday' => '1997-01-19',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::create([
            'holyName' => 'Toma',
            'lastName' => 'Vũ Minh',
            'name' => 'Đức',
            'account_code' => 'MV01109999',
            'picture' => 'MV01109999-00.jpg',
            'token' => 'TAspUngP54QLTWWyw32jua8qoseSzv1AjC5cmfxHdlNckhm2XkQZOaPEMigF',
            'email' => 'Paulminhduc99@gmail.com',
            'birthday' => '1999-10-01',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::create([
            'holyName' => 'Maria',
            'lastName' => 'Nguyễn Thị Thúy',
            'name' => 'Vy',
            'account_code' => 'MV11100101',
            'picture' => 'MV11100101-00.jpg',
            'token' => '6P8fE9fXg5y7LSVl1eKd2Wb01QAMPkJzR8IaQq83CBDP195SKEnY3oInAztL',
            'email' => 'nguyenttvy1110@gmail.com',
            'birthday' => '2001-10-11',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'holyName' => 'Monica',
            'lastName' => 'Nguyễn Hoàng Kim',
            'name' => 'Dung',
            'account_code' => 'MV26030101',
            'picture' => 'MV26030101-00.jpg',
            'token' => 'HfK0aSagV7D7ibjpEJQrfC3Q4wAWJ1pH2zdRTTqi5rIniWTNgemNJutkE1PI',
            'email' => 'monicakimdung2603@gmail.com',
            'birthday' => '2001-03-26',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::create([
            'holyName' => 'Teresa',
            'lastName' => 'Nguyễn Thị Ngọc',
            'name' => 'Vân',
            'account_code' => 'MV08199999',
            'picture' => 'MV08199999-00.jpg',
            'token' => 'fdRd4b89fV1nQCbmoqMDLdOZq0YFkgUWT4UIsvoq7Rendws7PEunhAMKSGXP',
            'email' => 'vannguyenthingoc73@gmail.com',
            'birthday' => '1999-08-19',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::create([
            'holyName' => 'Daminh',
            'lastName' => 'Lê Nguyễn Quang',
            'name' => 'Minh',
            'account_code' => 'MV10280202',
            'picture' => 'MV10280202-00.jpg',
            'token' => 'B6yJ11AMhfz0PKbBdCsY5d92nxNUa95RhFt9Ts0m68Ke5Ka0CDo3ihdrUKxY',
            'email' => 'coblustar@gmail.com',
            'birthday' => '2002-10-28',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::create([
            'holyName' => 'Giuse',
            'lastName' => 'Trịnh Minh',
            'name' => 'Nhật',
            'account_code' => 'MV20040404',
            'picture' => 'MV20040404-00.jpg',
            'token' => 'bIjPoc6JaXhtyIBOkkJjMGNZl6yaQaNyud4u62rm0O4nLm5MqRukBN1Ibdwb',
            'email' => 'trinhminhnhat1922004@gmail.com',
            'birthday' => '2004-04-20',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
        User::create([
            'holyName' => 'Vinh Sơn',
            'lastName' => 'Đoàn Trường',
            'name' => 'Nam',
            'account_code' => 'MV21109898',
            'picture' => 'MV21109898-00.jpg',
            'token' => 'GMNtV31QMVvLWQqxwSdvvIPd7h0inbrdZPGMwKW98hTCSGYZdz0RRldTpBKZ',
            'email' => 'namvn5555@gmail.com',
            'birthday' => '1998-10-21',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'holyName' => 'Toma',
            'lastName' => 'Nguyễn Văn',
            'name' => 'A',
            'account_code' => 'MV01012121',
            'picture' => '',
            'token' => 'Xn7pF4qLz9KdTVA3wRMuyHgcIQ8JmXNPbdtUZLs5WyCBoaeKvrE2TGHYsxMj',
            'email' => 'nguyenvana@gmail.com',
            'birthday' => '2021-01-01',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'holyName' => 'Toma',
            'lastName' => 'Nguyễn Thị',
            'name' => 'B',
            'account_code' => 'MV01012122',
            'picture' => '',
            'token' => 'Vt2dR7pWmL5nYqX9uK8sGvFd3jZQaTLoVz6qF9rYwC0jNmK1PzRGeWbUoXKZ',
            'email' => 'nguyenthib@gmail.com',
            'birthday' => '2021-01-01',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'holyName' => 'Anna',
            'lastName' => 'Trần Thị',
            'name' => 'C',
            'account_code' => 'MV02022222',
            'picture' => '',
            'token' => 'Yp3nF5qLz8KdTVA4wRMuyHgcIQ9JmXNPbdtUZLs6WyCBoaeKvrE3TGHYsxMj',
            'email' => 'tranthic@gmail.com',
            'birthday' => '2022-02-02',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'holyName' => 'Maria',
            'lastName' => 'Phạm Văn',
            'name' => 'D',
            'account_code' => 'MV03032323',
            'picture' => '',
            'token' => 'Zq4nF6qLz7KdTVA5wRMuyHgcIQ0JmXNPbdtUZLs7WyCBoaeKvrE4TGHYsxMj',
            'email' => 'phamvand@gmail.com',
            'birthday' => '2023-03-03',
            'password' => Hash::make('12345'),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
