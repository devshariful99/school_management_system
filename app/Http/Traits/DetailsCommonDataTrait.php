<?php

namespace App\Http\Traits;

trait DetailsCommonDataTrait
{
    private function MorphedAuditColumnsData($data)
    {
        $data->creating_time = timeFormat($data->created_at);
        $data->updating_time = $data->created_at != $data->updated_at ? timeFormat($data->updated_at) : 'null';
        $data->creater_name = creater_name($data->creater);
        $data->updater_name = updater_name($data->updater);
    }

    private function AdminAuditColumnsData($data)
    {
        $data->creating_time = timeFormat($data->created_at);
        $data->updating_time = $data->created_at != $data->updated_at ? timeFormat($data->updated_at) : 'null';
        $data->creater_name = creater_name($data->creater_admin);
        $data->updater_name = updater_name($data->updater_admin);
    }
    private function statusColumnData($data)
    {
        $data->statusBadgeBg = $data->status ? $data->getStatusBadgeBg() : '';
        $data->statusBadgeTitle = $data->status ? $data->getStatusBadgeTitle() : '';
        $data->statusBtnBg = $data->status ? $data->getStatusBtnBg() : '';
        $data->statusBtnTitle = $data->status ? $data->getStatusBtnTitle() : '';
    }
}