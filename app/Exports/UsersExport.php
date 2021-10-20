<?php

namespace App\Exports;

use App\Models\Admin\Hg_customer;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function collection()
    {
        return Hg_customer::all();
    }*/

    protected $data;

    //建構函式傳值
    public function __construct($data)
    {
        $this->data = $data;
        $this->Hg_customer = new Hg_customer();
    }
    //陣列轉集合
    public function collection()
    {
        return collect($this->createData());
    }
    //業務程式碼
    public function createData()
    {
        $result[] = [
            trans('admin.agent_list.agent_name'),
            trans('admin.account'),
            trans('admin.user_name'),
            trans('admin.tel'),
            trans('admin.customer.level'),
            trans('admin.customer.profit_loss'),
            trans('admin.customer.deposit_info'),
            trans('admin.customer.withdraw_info'),
            trans('admin.customer.money'),
            trans('admin.customer.point'),
            trans('admin.customer.created_at'),
            trans('admin.customer.status'),
            trans('admin.bank_name'),
            trans('admin.bank_account_name'),
            trans('admin.bank_account'),
        ];
        // echo "<pre>";
        // print_r($this->data);
        // exit();
        $getList = $this->data;
        if($getList['count'] > 0){
            foreach($getList['list'] as $k => $v){
                $result[] = array(
                    "\t" .$v['agent_account']. "\t",
                    "\t" .$v['account']."&".$v['customer_level_name']."&".$v['nickname']. "\t",
                    "\t" .$v['user_name']. "\t",
                    "\t" .$v['mobile']. "\t",
                    "\t" .$v['customer_level']. "\t",
                    "\t" .NFormat($v['deposit_sum']-$v['withdraw_sum']). "\t",
                    "\t" .NFormat($v['deposit_sum'])."(".$v['deposit_cnt'].")". "\t",
                    "\t" .NFormat($v['withdraw_sum'])."(".$v['withdraw_cnt'].")". "\t",
                    "\t" .NFormat($v['money']). "\t",
                    "\t" .NFormat($v['point']). "\t",
                    "\t" .$v['created_at']. "\t",
                    "\t" .$v['status_name']. "\t",
                    "\t" .$v['bank_name']. "\t",
                    "\t" .$v['bank_account_name']. "\t",
                    "\t" .$v['bank_account']. "\t"
                );
            }
        }
        return $result;
    }
}
