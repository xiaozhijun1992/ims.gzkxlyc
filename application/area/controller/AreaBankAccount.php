<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-07
 * Time: 18:06
 */

namespace app\area\controller;


use app\common\controller\AreaBase;
use app\common\model\BankList;
use think\Exception;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;

class AreaBankAccount extends AreaBase
{
    public function index(){
        $this->assign("nav_list",["区域代理","我的财务","结算信息"]);
        $account = \app\common\model\AreaBankAccount::where("area_id",Session::get("area_info.id"))->find();
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
                    $account = \app\common\model\AreaBankAccount::where("area_id",Session::get("area_info.id"))->find();
                    if($account){
                        $account->save($data);
                    }else {
                        $data["area_id"] = Session::get("area_info.id");
                        $account = new \app\common\model\AreaBankAccount();
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