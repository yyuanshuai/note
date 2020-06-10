

```php

    public function pay(Request $request, OrderService $orderService)
    {
        $params = $request->validate([
            'order_no' => 'required',
            'pay_client'  => ['required', Rule::in(PayClientEnum::ANDROID, PayClientEnum::IOS, PayClientEnum::XCX, PayClientEnum::JSAPI)]
        ]);
        $user = auth('api')->user();
        $order = Order::where('order_no', $params['order_no'])->firstOrFail();
        if ($order->pay_status == 1 || $order->order_status == 0) {
            return ResultVOUtil::error('-1', '该订单已支付或已取消');
        }
        if ($order->pay_type == PayTypeEnum::WXH5) {
            $app = Factory::payment(config('wechat.payment.wx_h5'));
            $result = $app->order->unify([
                'body' => '助残商城购买商品',
                'out_trade_no' => $order->order_no,
                // 'total_fee' => $order->order_amount * 100,
                'total_fee' => 0.01 * 100,
                'trade_type' => 'MWEB'
            ]);
            if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                \Log::info($result);
                return response_fail('微信支付失败');
            }
            return ResultVOUtil::success($result);
        } else if ($order->pay_type == PayTypeEnum::ZFBH5) {
            \Log::info('支付宝支付');
            $aop = new \AopClient ();
            $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
            $aop->appId = env('ALIPAY_APP_ID', '');
            $aop->rsaPrivateKey =  env('ALIPAY_RSA_PRIVATE_KEY'); //'请填写开发者私钥去头去尾去回车，一行字符串'
            $aop->format = "json"; // 仅支持json，非必须
            $aop->charset = "utf-8";
            $aop->signType = "RSA2";
            $aop->alipayrsaPublicKey = env('ALIPAY_RSA_PUBLIC_KEY');  //'请填写支付宝公钥，一行字符串'
            $request = new \AlipayTradeWapPayRequest ();
            $request->setReturnUrl(env('H5_URL')."/pages/myOrder/myOrder");
            $request->setNotifyUrl(env('APP_URL').'/api/frontend/order/aliPayNotify');
            $request->setBizContent(json_encode([
                // 'body' => '助残商城购买商品',
                'subject' => '助残商城购买商品',
                'out_trade_no' => $order->order_no,
                // 'total_amount' => $order->order_amount,
                'total_amount' => 0.01,
                'quit_url' => env('H5_URL')."/pages/myOrder/myOrder",
                'product_code' => 'QUICK_WAP_WAY'
            ]));
            $result = $aop->pageExecute($request);
            return response_success($result);
        } else if ($order->pay_type == PayTypeEnum::XCX) {
            if (!$user->wx_mini_openid) {
                return ResultVOUtil::error(40003, '请先授权小程序登录');
            }
            //向微信小程序发起订单
            $app = Factory::payment(config('wechat.payment.wx_mini'));

            $result = $app->order->unify([
                'body' => '助残商城购买商品',
                'out_trade_no' => $order->order_no,
                // 'total_fee' => $order->order_amount * 100,
                'total_fee' => 0.01 * 100,
                'trade_type' => 'JSAPI',
                'openid' => $user->wx_mini_openid
            ]);
            if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                \Log::info($result);
                return response_fail('微信订单服务创建失败');
            }
            return ResultVOUtil::success($app->jssdk->bridgeConfig($result['prepay_id'], false));
        } else if ($order->pay_type == PayTypeEnum::BDWX){
            //向微信小程序发起订单
            $app = Factory::payment(config('wechat.payment.wx_h5'));
            $result = $app->order->unify([
                'body' => '助残商城购买商品',
                'out_trade_no' => $order->order_no,
                // 'total_fee' => $order->order_amount * 100,
                'total_fee' => 0.01 * 100,
                'trade_type' => 'MWEB',
            ]);
            if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                return response_fail('微信订单服务创建失败');
            }
            $order_info = [
                "app_id" => env("TOUTIAO_PAYMENT_APP_ID"),
                "sign_type" => "MD5",
                "out_order_no" => $order->order_no,
                "merchant_id" => env("TOUTIAO_PAYMENT_MCH_ID"),
                "timestamp" => time(),
                "product_code" => "pay",
                "payment_type" => "direct",
                "total_amount" => (int)($order->order_amount * 100),
                "trade_type" => "H5",
                "uid" => $user->tt_openid,
                "version" => "2.0",
                "currency" => "CNY",
                "subject" => "华研支付业务_活动测试",
                "body" => "华研支付购买",
                "sign" => "",
                "trade_time" => time(),
                "valid_time" => "300",
                "notify_url" => env('APP_URL')."/api/frontend/order/wxH5Notify",
                "wx_url" => $result['mweb_url'],
                "alipay_url" => "",
                "wx_type" => "MWEB",
                "risk_info" => '{"ip":"127.0.0.1","device_id":"485737374363263"}'
            ];
            $TTPayService = new TouTiaoPayService();
            $sign = $TTPayService->getSign($order_info, env('TOUTIAO_PAYMENT_SECRET'));
            $order_info['sign'] = $sign;
            return response_success($order_info);
        } else if ($order->pay_type == PayTypeEnum::BDZFB){
            $aop = new \AopClient ();
            $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
            $aop->appId = env('ALIPAY_APP_ID', '');
            $aop->rsaPrivateKey =  env('ALIPAY_RSA_PRIVATE_KEY'); //'请填写开发者私钥去头去尾去回车，一行字符串'
            $aop->format = "json"; // 仅支持json，非必须
            $aop->charset = "utf-8";
            $aop->signType = "RSA2";
            $aop->alipayrsaPublicKey = env('ALIPAY_RSA_PUBLIC_KEY');  //'请填写支付宝公钥，一行字符串'
            $request = new \AlipayTradeAppPayRequest ();
            $request->setReturnUrl(env('H5_URL')."/pages/myOrder/myOrder");
            $request->setNotifyUrl(env('APP_URL').'/api/frontend/order/aliPayNotify');
            $request->setBizContent(json_encode([
                // 'body' => '助残商城购买商品',
                'subject' => '助残商城购买商品',
                'out_trade_no' => $order->order_no,
                // 'total_amount' => $order->order_amount,
                'total_amount' => 0.01,
                'quit_url' => env('H5_URL')."/pages/myOrder/myOrder",
                'product_code' => 'QUICK_MSECURITY_PAY'
            ]));
            $result = $aop->sdkExecute($request);
            $order_info = [
                "app_id" => env("TOUTIAO_PAYMENT_APP_ID"),
                "sign_type" => "MD5",
                "out_order_no" => $order->order_no,
                "merchant_id" => env("TOUTIAO_PAYMENT_MCH_ID"),
                "timestamp" => time(),
                "product_code" => "pay",
                "payment_type" => "direct",
                // "total_amount" => (int)($order->order_amount * 100),
                "total_amount" => 1,
                "trade_type" => "H5",
                "uid" => $user->tt_openid,
                "version" => "2.0",
                "currency" => "CNY",
                "subject" => "助残支付业务_活动测试",
                "body" => "助残支付购买",
                "sign" => "",
                "trade_time" => time(),
                "valid_time" => "300",
                "notify_url" => env('APP_URL')."/api/frontend/order/aliPayNotify",
                "wx_url" => "",
                "alipay_url" => $result,
                "wx_type" => "",
                "risk_info" => '{"ip":"127.0.0.1","device_id":"485737374363263"}'
            ];
            $TTPayService = new TouTiaoPayService();
            $sign = $TTPayService->getSign($order_info, env('TOUTIAO_PAYMENT_SECRET'));
            $order_info['sign'] = $sign;
            return response_success($order_info);
        }
    }
```

