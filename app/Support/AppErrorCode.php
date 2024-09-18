<?php

namespace App\Support;

class AppErrorCode
{
    public const CODE_1000 = 1000; // Có lỗi xảy ra, vui lòng liên hệ admin !
    public const CODE_2000 = 2000; // Tạo nhóm tài sản không thành công !
    public const CODE_2001 = 2001; // Đã tồn tại nhóm tài sản cùng tên !
    public const CODE_2002 = 2002; // Nhóm tài sản không tồn tại !
    public const CODE_2003 = 2003; // Xóa nhóm tài sản thất bại !
    public const CODE_2004 = 2004; // Loại tài sản không tồn tại !
    public const CODE_2005 = 2005; // Xóa loại tài sản thất bại !
    public const CODE_2006 = 2006; // Tạo loại tài sản thất bại !
    public const CODE_2007 = 2007; //  Đã tồn tại loai tài sản cùng tên !
    public const CODE_2008 = 2008; //  Cập nhật loai tài sản thất bại !
}
