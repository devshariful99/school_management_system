<?php

namespace App\Http\Traits;

trait DetailsCommonDataTrait
{
    private function MorphedAuditColumnsData($data)
    {
        $data->creating_time = timeFormat($data->created_at);
        $data->updating_time = $data->created_at != $data->updated_at ? timeFormat($data->updated_at) : 'null';
        $data->created_by = creater_name($data->creater);
        $data->updated_by = updater_name($data->updater);
    }
    private function AdminAuditColumnsData($data)
    {
        $data->creating_time = timeFormat($data->created_at);
        $data->updating_time = $data->created_at != $data->updated_at ? timeFormat($data->updated_at) : 'null';
        $data->created_by = creater_name($data->created_admin);
        $data->updated_by = updater_name($data->updated_admin);
    }
    private function statusColumnData($data)
    {
        $data->statusBadgeBg = $data->status ? $data->getStatusBadgeBg() : '';
        $data->statusBadgeTitle = $data->status ? $data->getStatusBadgeTitle() : '';
        $data->statusBtnBg = $data->status ? $data->getStatusBtnBg() : '';
        $data->statusBtnTitle = $data->status ? $data->getStatusBtnTitle() : '';
    }
}