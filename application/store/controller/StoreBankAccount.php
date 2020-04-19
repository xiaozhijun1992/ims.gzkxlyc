<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-07
 * Time: 18:06
 */

namespace app\store\controller;


use app\common\controller\StoreBase;
use app\common\model\BankList;
use think\Exception;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;

class StoreBankAccount extends StoreBase
{
    public function index(){
        $this->assign("nav_list",["店铺首页","我的财务","结算信息"]);
        $account = \app\common\model\StoreBankAccount::where("store_id",Session::get("store_info.id"))->find();
        $this->assign("account",$account);
        $bankList = BankList::all();
        $this->assign("bankList",$bankList);
        return $this->fetch();
    }

    public function edit(){
        if(Request::isPost()){
            $validate = Validate::rule([
                "bank|收款银行" => 'require',
                "name|收款人姓名" => 'require',
                "account|收款人账号" => 'require'
            ]);
            $data = Request::param();
            if(!Validate::check($data)){
                return result([],1,$validate->getError());
            }else {

                try {
                    $account = \app\common\model\StoreBankAccount::where("store_id",Session::get("store_info.id"))->find();
                    if($account){
                        $account->save($data);
                    }else {
                        $data["store_id"] = Session::get("store_info.id");
                        $account = new \app\common\model\StoreBankAccount();
                        $account->save($data);
                    }

                    return result([],0,"修改或新增成功");
                } catch (Exception $e) {
                    return result([],1,$e->getMessage());
                }

            }
        }else {
            return result([],1,"非法提交");
        }
    }

}