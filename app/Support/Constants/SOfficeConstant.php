<?php

namespace App\Support\Constants;

class SOfficeConstant
{
    // ORGANIZATION
    public const ORGANIZATION_ACCOUNTING_FINANCE_ID   = 3;
    public const ORGANIZATION_ADMINISTRATIVE_STAFF_ID = 36;
    public const ORGANIZATION_STATUS_ACTIVE           = 1;
    public const ORGANIZATION_PARENT_MAIN             = 1;

    //users
    public const GENERAL_MANAGER_ID                   = 8;

    // Comment
    public const TYPE_PLAN_LIQUIDATION = 4;

    // Comment reaction types
    public const CM_REACT_LIKE  = 1;
    public const CM_REACT_HEART = 2;
    public const CM_REACT_LAUGH = 3;
    public const CM_REACT_WOW   = 4;
    public const CM_REACT_SAD   = 5;
    public const CM_REACT_ANGRY = 6;

    // Comment action post
    public const CM_CREATE_TYPE   = 1;
    public const CM_EDIT_TYPE     = 2;
    public const CM_DEL_TYPE      = 3;
    public const CM_REACTION_TYPE = 4;
    //file upload config
    public const FILE_TYPE_ALLOW = ['jpg', 'jpeg', 'gif', 'png', 'svg', 'docx', 'doc', 'xlsx', 'pdf', 'zip', 'exe', 'pptx'];
    public const FILE_TYPE_PATH  = ['docx' => '/images/thumbnail-file/word.png', 'doc' => '/images/thumbnail-file/word.png', 'xlsx' => '/images/thumbnail-file/excel.png', 'pdf' => '/images/thumbnail-file/pdf.png', 'zip' => '/images/thumbnail-file/zip.png', 'exe' => '/images/thumbnail-file/exe-file.png', 'pptx' => '/images/thumbnail-file/powerpoint.png'];
    public const FILE_SIZE_ALLOW = 10 * 1024 * 1024; //10MB

    public const CM_LIMIT = 3;
}
