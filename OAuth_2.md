## [OAuth 2.0](http://www.ruanyifeng.com/blog/2014/05/oauth_2_0.html) 是目前最流行的授权机制，用来授权第三方应用，获取用户数据。

> **OAuth 的核心就是向第三方应用颁发令牌**
>
> **OAuth 2.0 规定了四种获得令牌的流程。你可以选择最适合自己的那一种，向第三方应用颁发令牌。**

### 第一种: 授权码(最安全)

> **授权码（authorization code）方式，指的是第三方应用先申请一个授权码，然后再用该码获取令牌。**

##### 微信的步骤:

1. A网站提供一个按钮给用户跳转到微信授权登录的页面, 比如:

   ```javascript
   https://b.com/oauth/authorize?
     response_type=code&
     client_id=CLIENT_ID&
     redirect_uri=CALLBACK_URL&
     scope=read
   ```

2. 用户登录确定授权成功

3. 页面跳转到回调URL并带上授权码

   ```
   https://a.com/callback?code=AUTHORIZATION_CODE
   ```

4. A网站拿到授权码去微信获取令牌

   ```javascript
   https://b.com/oauth/token?
    client_id=CLIENT_ID&
    client_secret=CLIENT_SECRET&
    grant_type=authorization_code&
    code=AUTHORIZATION_CODE&
    redirect_uri=CALLBACK_URL
   ```

5. B 网站收到请求以后，就会颁发令牌。具体做法是向`redirect_uri`指定的网址，发送一段 JSON 数据。

   ```javascript
   {    
     "access_token":"ACCESS_TOKEN",
     "token_type":"bearer",
     "expires_in":2592000,
     "refresh_token":"REFRESH_TOKEN",
     "scope":"read",
     "uid":100101,
     "info":{...}
   }
   ```



### 第二种方式：隐藏式

### 第三种方式：密码式

### 第四种方式：凭证式