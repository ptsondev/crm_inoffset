<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at']; // other fields is fillablle

    public static function getListSource()
    {
        return array(
            '1' => 'Khách Cũ',
            '2' => 'Khách Quay Lại',
            '3' => 'Giới Thiệu',
            '4' => 'Từ Google ads',
            '5' => 'Từ Facebook',
            '6' => 'Từ Shopee',
            '7' => 'Từ Lazada',
            '8' => 'Từ Tiki',
            '10' => 'Khác',
        );
    }

    public static function getListStatus()
    {
        return array(
            '0' => 'Đã Huỷ',
            '1' => 'Mới',
            '2' => 'Đã báo giá',
            '3' => 'Đã ký',
            '4' => 'Duyêt in',
            '5' => 'Đã thiết kế',
            '6' => 'Đã Bình File',
            '7' => 'Đã In',
            '8' => 'Đã Thành Phẩm',
            '9' => 'Đã Giao Hàng',
            '10' => 'Đã Hoàn Thành',
        );
    }

}
