// ASSET TYPE
const LIST_MEASURE = {
    1: 'Chiếc',
    2: 'Cái',
    3: 'Bộ',
    4: 'Bình',
    5: 'Cuộn',
    6: 'Hộp',
    7: 'Túi',
    8: 'Lọ',
    9: 'Thùng',
    10: 'Đôi',
}

//APPENDIX
const STATUS_APPENDIX = {
    1: 'Chờ duyệt',
    2: 'Đã duyệt',
    3: 'Hủy'
}

//CONTRACT
const STATUS_CONTRACT = {
    1: 'Chờ duyệt',
    2: 'Đã duyệt',
    3: 'Hủy'
}

const TYPE_CONTRACT = {
    1: 'Hợp đồng mua bán',
    2: 'Hợp đồng nguyên tắc',
}

// ORGANIZATION
const ID_ORGANIZATION_TCKT = 3
const ID_ORGANIZATION_NSHC = 36

// SHOPPING_PLAN_COMPANY
const STATUS_SHOPPING_PLAN_COMPANY_NEW = 1
const STATUS_SHOPPING_PLAN_COMPANY_REGISTER = 2
const STATUS_SHOPPING_PLAN_COMPANY_PENDING_ACCOUNTANT_APPROVAL = 3
const STATUS_SHOPPING_PLAN_COMPANY_PENDING_MANAGER_APPROVAL = 4
const STATUS_SHOPPING_PLAN_COMPANY_APPROVAL = 5
const STATUS_SHOPPING_PLAN_COMPANY_CANCEL = 6
const STATUS_SHOPPING_PLAN_COMPANY_HR_HANDLE = 7
const STATUS_SHOPPING_PLAN_COMPANY_HR_SYNTHETIC = 8
const STATUS_SHOPPING_PLAN_COMPANY = {
    1: 'Mới tạo',
    2: 'Mở đăng ký',
    3: 'Chờ TCKT duyệt',
    4: 'Chờ TGĐ duyệt',
    5: 'TGĐ đã duyệt',
    6: 'TGĐ từ chối',
    7: 'Đang xử lý',
    8: 'Đang tổng hợp',
}
const TYPE_SHOPPING_PLAN_COMPANY_YEAR = 1;
const TYPE_SHOPPING_PLAN_COMPANY_QUARTER = 2;
const TYPE_SHOPPING_PLAN_COMPANY_WEEK = 3;
const GENERAL_TYPE_APPROVAL_COMPANY = 'approval';
const GENERAL_TYPE_DISAPPROVAL_COMPANY = 'disapproval';

// SHOPPING_PLAN_ORGANIZATION
const STATUS_SHOPPING_PLAN_ORGANIZATION_OPEN_REGISTER = 1
const STATUS_SHOPPING_PLAN_ORGANIZATION_REGISTERED = 2
const STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_ACCOUNTANT_APPROVAL = 3
const STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNTANT_REVIEWED = 4
const STATUS_SHOPPING_PLAN_ORGANIZATION_PENDING_MANAGER_APPROVAL = 5
const STATUS_SHOPPING_PLAN_ORGANIZATION_APPROVAL = 6
const STATUS_SHOPPING_PLAN_ORGANIZATION_CANCEL = 7
const STATUS_SHOPPING_PLAN_ORGANIZATION_ACCOUNT_CANCEL = 8
const STATUS_SHOPPING_PLAN_ORGANIZATION_HR_HANDLE = 9
const STATUS_SHOPPING_PLAN_ORGANIZATION_HR_SYNTHETIC = 10
const STATUS_SHOPPING_PLAN_ORGANIZATION = {
    1: 'Mở đăng ký',
    2: 'Đã đăng ký',
    3: 'Chờ TCKT duyệt',
    4: 'TCKT đang đánh giá',
    5: 'Chờ TGĐ duyệt',
    6: 'TGĐ đã duyệt',
    7: 'TGĐ từ chối',
    8: 'TCKT từ chối',
    9: 'Đang xử lý',
    10: 'Đang tổng hợp',
}
const ORGANIZATION_TYPE_APPROVAL = 'approval';
const ORGANIZATION_TYPE_DISAPPROVAL = 'disapproval';

//COMMENT
const TYPE_COMMENT_SHOPPING_PLAN_COMPANY = 1;
const TYPE_COMMENT_SHOPPING_PLAN_ORGANIZATION = 2;

// USERS
const DEPT_IDS_FOLLOWERS = [7, 8, 9, 10, 11, 12, 41, 42, 149, 160]

// OTHER
const LIST_QUARTER = {
    1: 'Quý 1',
    2: 'Quý 2',
    3: 'Quý 3',
    4: 'Quý 4',
}

const LIST_WEEK = {
    1: 'Tuần 1',
    2: 'Tuần 2',
    3: 'Tuần 3',
    4: 'Tuần 4',
}

const LIST_MONTHS = {
    1: 'Tháng 1',
    2: 'Tháng 2',
    3: 'Tháng 3',
    4: 'Tháng 4',
    5: 'Tháng 5',
    6: 'Tháng 6',
    7: 'Tháng 7',
    8: 'Tháng 8',
    9: 'Tháng 9',
    10: 'Tháng 10',
    11: 'Tháng 11',
    12: 'Tháng 12',
}

const LIST_ACTION_SHOPPING_ASSET = {
    1: 'Mua mới',
    2: 'Luân chuyển'
}
