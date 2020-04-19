<?php
namespace app\index\controller;

use app\common\model\SmsRecord;
use app\common\model\Store;
use app\common\model\StoreApplication;
use app\common\model\User;
use think\captcha\Captcha;
use think\Controller;
use think\exception\DbException;
use think\facade\Request;
use think\Validate;


class Index extends Controller
{
    public function index()
    {
        $this->redirect('/store/index/login');
    }

    public function join()
    {
        if(Request::isPost()){
            $data = Request::param();
            $validate = new Validate();
            $validate->rule([
                "name|姓名" => 'require|chs|length:2,6',
                "phone|手机号" => 'require|mobile',
                "title|商家名称" => 'require',
                "address|联系地址" => 'require'
            ]);

            if(!$validate->check($data)){
                return result([],1,$validate->getError());
            }

            $application = new StoreApplication();
            if($application->save($data)){
                return result([],0,"提交成功，工作人员将在3个工作日内与您联系，请保持电话畅通");
            }else{
                return result([],1,"提交失败");
            }
        }
        return $this->fetch();
    }

    public function about(){
        return $this->fetch();
    }

    public function service(){
        return $this->fetch();
    }

    // 验证码图片
    public function verify()
    {
        $captcha = new Captcha();
        return $captcha->entry();
    }

    // 获取短信验证码
    public function getCode(){
        $captcha = new Captcha();

        $validate = Validate::make([
            "phone" => 'require|mobile'
        ]);

        if(!$validate->check(Request::param())){
            return result([],1,"手机号码格式不正确，请重新输入");
        }

        $phone = Request::param("phone");
        try {
            $user = User::where(["phone" => $phone,"status" => 1])->find();
            if(!$user){
                return result([],1,"手机号码未绑定，请先在商城个人中心中绑定");
            }

            $id = $user->id;
            $store = Store::where(["user_id"=>$id,"status"=> 1])->find();
            if(!$store){
                return result([],1,"手机号码未绑定店铺，不能获取验证码");
            }
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }

        if(!Request::has("captchaCode") || Request::param("captchaCode") == ''){
            return result([],1,"验证码不能为空");
        }
        if( !$captcha->check(Request::param("captchaCode"))) {
            return result([],1,"验证码错误，请重新输入");
        }
        $code = newSmscode();
        $content = "您正在使用手机验证码登录商家后台，验证码" . $code .",该验证码5分钟内有效，请勿泄露于他人";
        $phone = Request::param("phone");
        try {
            $res = sendSms($phone,$content);
        } catch (\Exception $e) {
            return result([],1,$e->getMessage());
        }
        $no = newVerificationCode();
        $data = [
            "code" => $code,
            "phone" => $phone,
            "no" => $no,
            "content" => $content,
            "status" => $res,
        ];

        $smsRecord = new SmsRecord();
        $smsRecord->save($data);
        // 发送成功
        if(strpos($res,"OK:") === 0){
            return result($no,0,"短信发送成功");
        }else {
            return result([],1,"短信发送失败");
        }
    }


}
