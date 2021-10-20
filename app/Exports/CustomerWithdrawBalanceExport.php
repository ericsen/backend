<?php

namespace App\Exports;

use App\Models\Admin\Hg_customer_withdraw;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerWithdrawBalanceExport implements FromCollection
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
        $this->Hg_customer_withdraw = new Hg_customer_withdraw();
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
            trans('admin.customer_apply_balance.status'),
            trans('admin.agent_list.agent_name'),
            trans('admin.account'),
            trans('admin.user_name'),
            trans('admin.customer_apply_balance.apply_money'),
            trans('admin.customer_apply_balance.deposite_money'),
            trans('admin.customer_apply_balance.withdraw_money'),
            trans('admin.customer_apply_balance.customer_money'),
            trans('admin.customer_apply_balance.apply_time'),
            trans('admin.updated_at'),
        ];
        $getList = $this->data;
        if($getList['count'] > 0){
            foreach($getList['list'] as $k => $v){
                $result[] = array(
                    "\t" .$v['status_name']. "\t",
                    "\t" .$v['agent_account']. "\t",
                    "\t" .$v['account']. "\t",
                    "\t" .$v['user_name']. "\t",
                    "\t" .$v['account']."&".$v['customer_level_name']."&".$v['nickname']. "\t",
                    "\t" .NFormat($v['apply_money']). "\t",
                    "\t" .NFormat($v['deposite_money']). "\t",
                    "\t" .NFormat($v['withdraw_money']). "\t",
                    "\t" .NFormat($v['customer_money']). "\t",
                    "\t" .$v['apply_time']. "\t",
                    "\t" .$v['admin_updated_at']. "\t",
                );
            }
        }
        return $result;
    }
}
