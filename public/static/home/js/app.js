"use strict";

/*
 * KuaiYu JavaScript Library
 * Copyright (c) 2017 p2cn.com
 */
(function (window) {
    var app = {};
    // app.apiUrl = "http://app181022.xianfengt.top:88";
    app.apiUrl = "http://app190624.xianfengt.top:88";
    //接口服务器地址
    // app.webUrl = "http://app181022.xianfengt.top:88";
    app.webUrl = "http://app190624.xianfengt.top:88";
    //前端服务器地址
    // app.socket = "ws://app181022.p2cn.com:2346";
    //socket地址

    //空函数
    app.shield = function () {
        return false;
    };

    /**初始化函数**/
    app.init = function () {
        document.addEventListener('touchstart', app.shield, false); //取消浏览器所有事件
        api.parseTapmode(); //解析元素TAPMODE
        document.oncontextmenu = app.shield; //屏蔽选择函数
    };

    /**
     * @description 安卓系统下点返回键，程序退到后台运行。
     * @return NULL
     * @author AllenLi(allelinc@gmail.com)
     */
    app.backCloseAPP = function () {
        if (api.systemType == "android") {
            api.addEventListener({
                name: 'keyback'
            }, function (ret, err) {
              api.toast({
                  msg: '再按一次返回键退出',
                  duration: 2000,
                  location: 'bottom'
              });
              api.addEventListener({
                  name: 'keyback'
              }, function(ret, err){
                  api.closeWidget({
                      silent: true
                  });
              });
              setTimeout(function () {
                app.backCloseAPP();
              }, 2000);
            });
        }
    };

    /**
     * @description 时间戳转换成几分钟前，几小时前，几天前
     * @param {Num} timespan 时间戳  php时间戳是秒  js时间戳是毫秒
     * @return 返回几分钟前，几小时前，几天前
     */
    app.formatMsgTime = function (timespan) {
        var dateTime = new Date(timespan);
        var year = dateTime.getFullYear();
        var month = dateTime.getMonth() + 1;
        var day = dateTime.getDate();
        var hour = dateTime.getHours();
        var minute = dateTime.getMinutes();
        var second = dateTime.getSeconds();
        var now = new Date();
        var now_new = Date.parse(now.toDateString());
        //typescript转换写法
        var milliseconds = 0;
        var timeSpanStr;
        milliseconds = now_new - timespan;
        if (milliseconds <= 1000 * 60 * 1) {
            timeSpanStr = '刚刚';
        } else if (1000 * 60 * 1 < milliseconds && milliseconds <= 1000 * 60 * 60) {
            timeSpanStr = Math.round(milliseconds / (1000 * 60)) + '分钟前';
        } else if (1000 * 60 * 60 * 1 < milliseconds && milliseconds <= 1000 * 60 * 60 * 24) {
            timeSpanStr = Math.round(milliseconds / (1000 * 60 * 60)) + '小时前';
        } else if (1000 * 60 * 60 * 24 < milliseconds && milliseconds <= 1000 * 60 * 60 * 24 * 15) {
            timeSpanStr = Math.round(milliseconds / (1000 * 60 * 60 * 24)) + '天前';
        } else if (milliseconds > 1000 * 60 * 60 * 24 * 15 && year == now.getFullYear()) {
            timeSpanStr = month + '-' + day + ' ' + hour + ':' + minute;
        } else {
            timeSpanStr = year + '-' + month + '-' + day + ' ' + hour + ':' + minute;
        }
        return timeSpanStr;
    };
    /**
     * @description 时间戳转换成几天前，几天后，昨天，今天，明天，后天，前天
     * @param {Num} timespan 时间戳  php时间戳是秒  js时间戳是毫秒
     * @return 返回几天前，几天后，昨天，今天，明天，后天，前天
     */
    app.GetRelativeDate = function (timestampstr) {
        var timestamp = parseInt(timestampstr);
        timestamp = isNaN(timestamp) ? 0 : timestamp;
        var thenT = new Date(timestamp);
        thenT.setHours(0);
        thenT.setMinutes(0);
        thenT.setSeconds(0);
        var nowtime = new Date();
        nowtime.setHours(0);
        nowtime.setMinutes(0);
        nowtime.setSeconds(0);
        var delt = Math.round((nowtime.getTime() - thenT.getTime()) / 1000 / 86400);
        var day_def = {
            '-2': '后天',
            '-1': '明天',
            '0': '今天',
            '1': '昨天',
            '2': '前天'
        }[delt.toString()] || (delt >= -30 && delt < 0 ? Math.abs(delt) + '天后' : delt > 0 && delt <= 30 ? delt + '天前' : GetDateString(timestamp));
        return day_def;

        function GetDateString(timestampstr, split) {
            var timestamp = parseInt(timestampstr);
            timestamp = isNaN(timestamp) ? 0 : timestamp;
            var datetime = new Date(timestamp);
            var month = datetime.getMonth() + 1;
            var date = datetime.getDate();
            if (split === undefined) split = '-';
            return datetime.getFullYear() + split + (month > 9 ? month : "0" + month) + split + (date > 9 ? date : "0" + date);
        }
    };

    /**
     * @description 时间戳转字符串
     * @param {Num} inputTime 时间戳
     * @return 返回时间字符 yyyy-MM-dd HH-mm-ss
     */
    app.formatDateTime = function (inputTime) {
        var date = new Date(inputTime);
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        m = m < 10 ? '0' + m : m;
        var d = date.getDate();
        d = d < 10 ? '0' + d : d;
        var h = date.getHours();
        h = h < 10 ? '0' + h : h;
        var minute = date.getMinutes();
        var second = date.getSeconds();
        minute = minute < 10 ? '0' + minute : minute;
        second = second < 10 ? '0' + second : second;
        return y + '/' + m + '/' + d + ' ' + h + ':' + minute + ':' + second;
    };

    /**
     * @description 时间戳转字符串
     * @param {Num} inputTime 时间戳
     * @return 返回时间字符 HH:mm
     */
    app.formatHourTime = function (inputTime) {
        var date = new Date(inputTime);
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        m = m < 10 ? '0' + m : m;
        var d = date.getDate();
        d = d < 10 ? '0' + d : d;
        var h = date.getHours();
        h = h < 10 ? '0' + h : h;
        var minute = date.getMinutes();
        var second = date.getSeconds();
        minute = minute < 10 ? '0' + minute : minute;
        second = second < 10 ? '0' + second : second;
        return h + ':' + minute;
    };

    /**
     * @description 时间戳转字符串
     * @param {Num} inputTime 时间戳
     * @return 返回时间字符 yyyy-MM-dd HH-mm-ss
     */
    app.formatDateTimeshort = function (inputTime) {
        var date = new Date(inputTime);
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        m = m < 10 ? '0' + m : m;
        var d = date.getDate();
        d = d < 10 ? '0' + d : d;
        var h = date.getHours();
        h = h < 10 ? '0' + h : h;
        var minute = date.getMinutes();
        var second = date.getSeconds();
        minute = minute < 10 ? '0' + minute : minute;
        second = second < 10 ? '0' + second : second;
        return y + '-' + m + '-' + d;
    };

    /**
     * @description 读取文件数据
     * @param {String} path  文件路径,在缓存目录下 cache://协议对应下的真实目录
     * @param {function} callBack 返回数据包含ret正确信息和err错误信息，参考api.readFile的返回信息
     * @return NULL
     * @author Allen(allenlinc@gmail.com)
     */
    app.readFile = function (path, callBack) {
        var cacheDir = api.cacheDir;
        api.readFile({
            path: cacheDir + path
        }, function (ret, err) {
            callBack(ret, err);
        });
    };

    /**
     * @description 写入json到缓存文件
     * @param {Object} json 网络传过来的JSON对象
     * @param {Object} id 缓存ID
     * @param {Object} path  文件路径,在缓存目录下 cache://协议对应下的真实目录
     * @author Allen(allenlinc@gmail.com)
     */
    app.writeFile = function (json, id, path) {
        var cacheDir = api.cacheDir;
        api.writeFile({
            path: cacheDir + '/' + path + '/' + id + '.json',
            data: JSON.stringify(json),
            append: false
        }, function (ret, err) {
            if (ret) {
                // console.log("写入成功");
            } else {}
                // console.log("写入失败");

                //写入正常失败信息 待完善
        });
    };

    /**
     * @description ajax请求 无缓存
     * @param {String} url 请求网址  不需要加域名
     * @param {String} method  请求类型 get post put delete head
     * @param {Array} datas    values：{}以表单方式提交参数（JSON对象）, 如 {"field1": "value1", "field1": "value2"} (直接传JSON对像.)
     * @param {Function} callBack 获得ajax返回数据 ret，err 参考api.ajax
     * @return NULL
     */
    app.ajax = function (url, method, datas, callBack) {
        //	    console.log(url);
        var now = Date.now();
        api.ajax({
            url: url,
            method: method,
            cache: false,
            timeout: 30,
            dataType: 'json',
            data: {
                values: datas
            }
        }, function (ret, err) {
            if (ret) {
                callBack(ret, err);
            } else {
                var msg = '错误码:' + err.code + ';错误信息:' + err.msg + '网络状态码:' + err.statusCode;
                api.toast({
                    msg: msg,
                    duration: 2000,
                    location: 'middle'
                });
            }
        });
    };

    /**
     * @description 图片缓存
     * @param {Object} selector  选择器组，图片样式加上cache
     */
    app.iCache = function (selector) {
        selector.forEach(function (data) {
            (function (data) {
                var curl = data.getAttribute('src');
                api.imageCache({
                    url: curl
                }, function (ret, err) {
                    var url = ret.url;
                    data.setAttribute('src', url);
                });
            })(data);
        });
    };

    /**
     * @description 数据本地缓存
     *    不经常更新数据可以使用缓存的通信方法，实时更新数据比如分页第二页什么的还是建议试用api.ajax
     * @param {String} folder  缓存文件保存在的文件夹，不同模块区分开，避免冲突
     * @param {String} id  用户ID，避免和其他用户冲突，如果是通用数据可以使用公用名称
     * @param {String} url 网址
     * @param {function} callback  成功后的回调方法
     * @return NULL
     * @author AllenLi(allelinc@gmail.com)
     */
    app.ajaxCache = function (folder, id, url, callback) {
        app.readFile('/' + folder + '/' + id + '.json', function (ret, err) {
            if (ret.status) {
                //成功读取缓存文件
                var cacheData = ret.data;
                callback(JSON.parse(cacheData));

                //				console.dir($api.domAll('.cache'));
                app.iCache($api.domAll('.cache'));
                //更新数据,防止更新
                app.ajax(url, 'GET', '', function (ret, err) {
                    if (ret) {
                        // console.dir(cacheData);
                        // console.dir(JSON.stringify(ret));
                        if (cacheData != JSON.stringify(ret)) {
                            callback(ret);
                            app.writeFile(ret, id, folder);
                            app.iCache($api.domAll('.cache'));
                        }
                    } else {
                        console.log("网络缓慢或者没有网络，数据获取失败");
                    }
                });
            } else {
                app.ajax(url, 'GET', '', function (ret, err) {
                    if (ret) {
                        if (cacheData != JSON.stringify(ret)) {
                            callback(ret);
                            app.writeFile(ret, id, folder);
                            app.iCache($api.domAll('.cache'));
                        }
                    } else {
                        //alert("网络缓慢或者没有网络，数据获取失败");
                        console.log("网络缓慢或者没有网络，数据获取失败");
                    }
                });
            }
        });
    };

    /**
     * @description 获取接口验证
     * @return 返回时间戳，随机数，验证码
     * eg: let { ctime, ran, yanz } = app.getApikey();
     */
    app.getApikey = function () {
        var times = Date.parse(new Date()) / 1000; //获取当前时间戳，单位秒
        var random = Math.floor(Math.random() * 900 + 100); //获取3位数的随机数
        var appKey = api.loadSecureValue({
            sync: true,
            key: 'apikey'
        });
        var str1 = md5(times + appKey);
        var str2 = md5(str1 + random);
        var code = str2.substr(8, 10);

        return {
            ctime: times,
            ran: random,
            yanz: code
        };
    };

    //Loading封装
    app.UILoadingInit = function () {
      app.UILoading = api.require('UILoading'); //引入UILoading模块
      app.UIloadingId = 0;  //UILoading的id
    };

    app.openLoading = function () {
        app.UILoading.flower({
            mask: true,
            size: 35,
            fixed: true
        }, function(ret) {
          app.UIloadingId = ret.id;
        });
    };

    app.closeLoading = function () {
      app.UILoading.closeFlower({id: app.UIloadingId});
    };

    /**
     * 打开新窗口
     * @param _winName->窗口名称
     * @param _winPath->窗口路径，不带文件名, 例如'./', 默认是当前路径
     * @param _statusStyle->新窗口状态栏颜色,（dark或者light，可不填）
     */
     app.openW = function (_winName, _winPath, _statusStyle) {
       if (_statusStyle == 'dark' || _statusStyle == 'light') {
         api.setStatusBarStyle({
             style: _statusStyle
         });
       }
      if (!_winPath) {
        _winPath = './';
      }
       api.openWin({
           name: _winName,
           url: _winPath + _winName + '.html'
       });
     };

    /**
     * 关闭窗口
     * @param _statusStyle->新窗口状态栏颜色,（dark或者light，可不填）
     */
     app.closeW = function (_statusStyle) {
       if (_statusStyle == 'dark' || _statusStyle == 'light') {
         api.setStatusBarStyle({
             style: _statusStyle
         });
       }
       api.closeWin();
     };

    /**
     *  @description jsonObject输出文本  测试使用
     **/
    app.dump = function (jsonObj) {
        console.log(JSON.stringify(jsonObj));
    };

    /**
     * 给富文本中图片添加域名
     */
     app.addDomain = function (_context) {
       var context = _context;
       if (context != "" && context.indexOf("<img") != -1) {
         var newWebURL = app.webUrl + "/uploads/images";
         context = context.replace(/\/uploads\/images/g, newWebURL);
       }

       return context;
     };

     /**
      * 判断输入的元素是否为空
      * @param {Array} inputArray 要验证的变量组成的二维数组,
      * eg: [[tel, '手机号'], [pwd, '密码']]，tel和pwd表示要验证的变量,'手机号'和'密码'表示没输入时提示的信息
      * @return {Boolean} true表示都有值， false则表示有没填的项
      */
      app.inputValidate = function (inputArray) {
        for (var i = 0; i < inputArray.length; i++) {
          if (inputArray[i][0] == '') {
            api.toast({
                msg: '请输入' + inputArray[i][1],
                duration: 2000,
                location: 'bottom'
            });
            return false;
          }
        }
        return true;
      };

       /**
        * 判断金额是否合法
        * @param {String} _money 要验证的金额
        * @return {Boolean} true表示合法
        */
        app.isRight = function (_money) {
          var reg = /^\d+\.?\d{0,2}$/;
          if (!reg.test(_money) || Number(_money) < 0.01) { // 判断金额是否合法
            api.toast({
                msg: '请输入正确的金额',
                duration: 2000,
                location: 'middle'
            });
            return false;
          }
          return true;
        };

        /**
        * @function encryption
        * @param { Object } value - Encrypted password.
        */
        app.encryption = function (value) {
            return md5(value).toString().substring(0, 32);
        };

        /**
         * 获得验证码
         * @param {String} _id 绑定点击事件的元素的id
         * @param {String} _tel 接收验证码手机号
         */
        app.getCode = function (_id, _tel) {
            if (!/^\d{11}$/.test(_tel)) {
              return false;
            }
            if ($api.text($api.byId(_id)) == '获取验证码') {
              var that = this;
              app.openLoading();
              var apiSign = app.getApikey();  //接口签名
              api.ajax({
                  url: app.apiUrl + "/index/plugin/execute/_plugin/Sms/_controller/Send/_action/SmsVeriSender.html",
                  method: 'post',
                  data: {
                      values: {
                          ctime: apiSign.ctime,
                          ran: apiSign.ran,
                          yanz: apiSign.yanz,
                          mobile: _tel,
                          templeteid: '360047'
                      }
                  }
              },function(ret, err){
                if (ret.status == 1) {
                  api.toast({
                      msg: '验证码发送成功',
                      duration: 2000,
                      location: 'bottom'
                  });

                  app.codeEffect(_id);
                } else {
                  api.toast({
                      msg: '验证码获取失败',
                      duration: 2000,
                      location: 'bottom'
                  });
                }
                app.closeLoading();
              });
            }
        };

        /**
         * 点击获取验证码后倒计时特效
         * @param {String} _id 绑定点击事件的元素的id
         */
        app.codeEffect = function (_id) {
          var countdown = 60,
              startCount = null,
              singleTimer = null,
              sendVerify = $api.byId(_id);

          $api.css(sendVerify, 'pointer-events: none');//移除点击事件


          singleTimer = setTimeout(function () {
              sendVerify.innerHTML = countdown + 's';
              startCount = setInterval(function () {
                  countdown--;
                  sendVerify.innerHTML = countdown + 's';

                  if (countdown === 0) {
                      clearInterval(startCount);
                      sendVerify.innerHTML = '获取验证码';
                      $api.css(sendVerify, 'pointer-events: auto'); //恢复点击事件
                  }
              }, 1000);
          }, 0);
        };

        /**
         * 图片缓存，给图片加上data-url属性，值为真实的图片地址
         * @param {String} selector 值为所有包含data-url的Dom元素
         */
        app.imageC = function (selector) {
          selector.forEach(function (data) {
              (function (data) {
                  var curl = data.dataset.url;
                  api.imageCache({
                      url: curl
                  }, function (ret, err) {
                    if (ret.status) { //如果缓存成功
                      var url = ret.url;
                      data.setAttribute('src', url);
                    } else {
                      data.setAttribute('src', curl);
                    }
                  });
              })(data);
          });
        };

        /**
         * 给字符串加星号
         * @param {String} _src 字符串
         * @param {num} _begin 字符串前面需要保留的位数
         * @param {num} _end 字符串后面需要保留的位数
         */
        app.addStar = function (_src, _begin, _end) {
          var len = _src.length;
          var temp = '';
          for (var i = 0; i < len - _begin - _end; i++) {
            temp += '*';
          }
          var res = _src.substr(0, _begin) + temp + _src.substr(-_end);
          return res;
        };

    window.app = app;
    //不显示错误信息
    // window.onerror = function () {
    //     return true;
    // };

    /**
     * @description  重写window.alert,增强体验
     * @return NULL
     * @author AllenLi(allenlinc@gmail.com)
     */
    window.alert = function (str) {
        api.alert({
            "title": "提示",
            "msg": str,
            "buttons": ["确定"]
        });
    };
})(window);
