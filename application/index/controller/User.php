<?php
namespace app\index\controller;
use think\Request;
use app\index\model\User as UserModel;
use think\Session;

class User extends Base
{
    //登录界面
    public function login()
    {
        $this->alreadyLogin();  //防止重复登录
        return $this -> view -> fetch();
    }
    //验证登录
    //$this -> validate($data, $rule, $msq)
    public function checkLogin(Request $request)
    {

        if (!empty(USER_ID)){
            return ['status'=> 0,'message'=> '用户已经登录请勿重新登录','data' =>''];
        }
        else{
            //初始化返回参数
            $status = 0;
            $result = '';
            $data = $request -> param();
//      验证规则
            $rule = [
                'name|用户名' => 'require',
                'password|密码' => 'require',
                'verify|验证码' => 'require|captcha',
            ];
//      自定义验证失败时的提示信息
            $msg = [
                'name' => ['require' => '用户名不能为空，请检查'],
                'password' => ['require' => '密码不能为空，请检查'],
                'verify' => ['require' => '验证码不能为空，请检查',
                    'captcha' => '验证码错误']
            ];
            //只会返回两种值，如果是true，则正确，如果是字符串，则是用户定义的错误提示
            $result = $this -> validate($data, $rule, $msg);

            if ($result === true){
                $query = [
                    'name' => $data['name'],
                    'password' => md5($data['password'])
                ];
                $user = UserModel::get($query);
                if ($user == null){
                    $result = '查询失败';
                }else{
                    $status = 1;
                    $result = '验证通过，点击[确定]进入';
                    Session::set('user_id', $user->id);
                    Session::set('user_info', $user->getData());
                }
            }
            return ['status'=> $status,'message'=>$result,'data' =>$data];
        }
    }

    //退出登录
    public function logout()
    {
        Session::delete('user_id');
        Session::delete('user_info');
        $this -> success('注销登录成功，正在返回登录界面...', 'user/login');
    }
}
