<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

namespace Wanzhong;

use GuzzleHttp\Client;

class Yuntongxun
{
  /**
   * @var string $appId
   * @var string $accountSid
   * @var string $accountToken
   */
  private $appId, $accountSid, $accountToken;

  /**
   * @var Client $http
   */
  private $http;

  /**
   * 构造函数
   *
   * @param string $accountSid
   * @param string $accountToken
   */
  public function __construct($appId, $accountSid, $accountToken)
  {
    $this->appId = $appId;
    $this->accountSid = $accountSid;
    $this->accountToken = $accountToken;

    $this->http = new Client(['base_uri' => "https://app.cloopen.com:8883"]);
  }

  /**
   * 发送模板短信
   * @param to 短信接收彿手机号码集合,用英文逗号分开
   * @param datas 内容数据
   * @param $templateId 模板Id
   */
  function sendTemplateSMS($to, $datas, $templateId)
  {
    $body = compact('to', 'datas', 'templateId');
    $body['appId']= $this->appId;
    return $this->request('TemplateSMS', $body);
  }

  private function request($api, $json)
  {
    $timestamp = date("YmdHis");
    $sig = strtoupper(md5($this->accountSid . $this->accountToken . $timestamp));
    //
    $url = "/2013-12-26/Accounts/$this->accountSid/SMS/$api?sig=$sig";
    // 生成授权：主帐户Id + 英文冒号 + 时间戳。
    $authorization = base64_encode($this->accountSid . ":" . $timestamp);

    $headers = [
      'Accept' => 'application/json',
      'Authorization' => $authorization
    ];

    return $this->http->post($url, compact('headers', 'json'));
  }
}
