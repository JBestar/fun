<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta content="blendTrans(Duration=0.0)" http-equiv="Page-Enter">
    <meta content="blendTrans(Duration=0.0)" http-equiv="Page-Exit">
    <link rel="shortcut icon" href="/favicon_<?=$_ENV['app.logo']?>.ico?v=1">
    <!-- CSS  -->
    <link rel="stylesheet" href="/css/login/reset.css?v=1">
    <link rel="stylesheet" href="/css/login/common.css?v=1">
    <link rel="stylesheet" href="/css/login/content.css?v=1">
    <link rel="stylesheet" href="/css/login/style.css?v=1">
    <link rel="stylesheet" href="/css/login/login.css?v=1">

    <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="/js/jquery-ui.js"></script>

    <title><?=$site_name?></title>
</head>
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
<body oncontextmenu="return false" ondragstart="return false" onselectstart="return false">
<?php else : ?>
<body>
<?php endif ?>
    <div class="alert_wrap basic_alert" id="basic_alert">
        <div class="alert_bot">
            <p class="question_ico" style="white-space:pre-line;" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:okAlert()';" class="btn btn_red" id="basic_ok" style="cursor: pointer">확인</a>
                <a onclick="location.href='javascript:closeAlert()';" class="btn" style="cursor: pointer">취소</a>
            </div>
        </div>
    </div>

    <div class="alert_wrap confirm_alert" id="confirm_alert">
        <div class="alert_bot">
            <p class="question_ico" style="white-space:pre-line;" id="alert_content"></p>
            <div class="btn_wrap">
                <a onclick="location.href='javascript:closeAlert()';" class="btn" id="confirm_ok" style="cursor: pointer">확인</a>
            </div>
        </div>
    </div>

    <style>
        
        <?php if($_ENV['app.name'] == APP_PHANTOM) :?>
            #wrap.users_wrap {
                background:url(/images/login/login_bg_<?=strtolower($_ENV['app.logo'])?>.png?v=1) no-repeat center center;
                background-size: cover;
                padding-bottom: 0;
                overflow: hidden;
            }
        <?php elseif($_ENV['app.name'] == APP_BOLTON || $_ENV['app.name'] == APP_HERMES) :?>
            body {
                background:url(/images/login/login_bg_clip_<?=strtolower($_ENV['app.logo'])?>.png?v=3) repeat ;
                overflow: hidden;
            }
            #wrap.users_wrap {
                background:url(/images/login/login_bg_<?=strtolower($_ENV['app.logo'])?>.png?v=1) no-repeat center center;
                background-size: 100%;
                padding-bottom: 0;
                overflow: hidden;
            }
            @media screen and (max-width: 800px){
                #wrap.users_wrap {
                    background-size: 150%;
                }
            }
        <?php endif ?>

        <?php if( array_key_exists('login.dlg_border', $_ENV) ) : ?>

            .users_wrap .login_wrap{
                border: <?=$_ENV['login.dlg_border']?>;
                border-radius:5px;
            }
        <?php endif ?>

        <?php if( array_key_exists('login.type', $_ENV) && $_ENV['login.type'] == 2 ) : ?>
            
            .users_wrap .login_wrap{
                width:500px;
                margin-top: 115px;
                margin-left: -250px;
            }
            .users_wrap .login_wrap .login_area,
            .users_wrap .login_wrap .join_area
            {
                padding:20px 10px;
            }
            .users_wrap .login_wrap .join_area
            {
                padding-top:40px;
            }
            .users_wrap .login_wrap .login_area .login_con {
                padding-top: 0px;
            }
            .users_wrap .login_wrap input,
            .users_wrap .login_wrap .login_area input
            {
                padding: 0 10px 0 10px;
                border: 1px solid #404447;
                border-radius: 5px;
                background: #1e1f21;
                font-size: 16px;
                font-family: "ns";
                box-shadow: 0px 3px 5px #000 inset;
            }
            .users_wrap .login_wrap .login_area input{
                width: 140px;
            }
            .users_wrap .login_wrap .submit_btn,
            .users_wrap .login_wrap .join_btn
            {
                overflow: hidden;
                display: inline-block;
                width: 85px;
                height: 43px;
                line-height: 43px;
                font-size: 16px;
                font-weight: 600;
                text-align: center;
                vertical-align: middle;
                color: #fff;
                border-radius: 5px;
                margin-top: -5px;
                margin-left: 5px;
            }
            .users_wrap .join_area .prev_btn,
            .users_wrap .join_area .next_btn
            {
                width: 120px;
            }

            .users_wrap .login_wrap .submit_btn{
                background: linear-gradient(#df5e61, #a93a3e, #86181d);
                margin-left: 2px;
            }
            .users_wrap .login_wrap .submit_btn:hover {
                opacity: 0.8;
            }

            .users_wrap .login_wrap .join_btn{
                background: linear-gradient(#858585, #4d4d4d, #1e1e1e);
                margin-left: 2px;
            }
            .users_wrap .login_wrap .join_btn:hover {
                opacity: 0.8;
            }

            @media screen and (max-width: 679px){
                .users_wrap .login_wrap {
                    max-width: 400px;
                    margin-left:-200px;
                }

                .users_wrap .login_wrap .login_area .submit_btn{
                    margin-top: 5px;
                    width:140px;
                }
                .users_wrap .login_wrap .login_area .join_btn{
                    width:140px;
                    margin-left:0px;
                }
                .users_wrap .login_wrap input{
                    height:43px;
                }
                .users_wrap .login_wrap .login_area .login_con .id_area .id_ico,
                .users_wrap .login_wrap .login_area .login_con .password_area .password_ico {
                    top: 11px;
                }
            }
        <?php endif ?>

        <?php if( array_key_exists('login.dlg_opacity', $_ENV) ) : ?>
            .users_wrap .login_wrap{
                background:rgb(20, 21, 25, <?=$_ENV['login.dlg_opacity']?>);
            }
        <?php endif ?>
        
        <?php if( array_key_exists('login.btn_color', $_ENV) ) : ?>
            
            .users_wrap .login_wrap .submit_btn{
                background:  linear-gradient(to right, #9f2124, #991f20, #911e22, #86181d);
            }
           
            .users_wrap .login_wrap .submit_btn:hover {
                opacity: 0.8;
            }
        <?php endif ?>


        .users_border{
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: -2;
            width: 1000px;
            height: 200px;
            margin-top: 75px;
            margin-left: -500px;
            border: 3px solid #fff8bd;
            border-radius: 0px;
            box-shadow: 10px 3px 10px #888;
            background:#000000;
        }

        <?php if($_ENV['app.name'] == APP_HERMES) :?>

            .users_border{
                box-shadow: 10px 3px 10px #fffb98;
            }
        <?php endif ?>

    </style>

    <div id="wrap" class="users_wrap">
        <div class="users_border">
        </div>
        
            <div class="login_wrap">
                <div class="login_area">
                    <div class="login_con">
                        <input type="text" placeholder="User ID" id="user_id" class="english_p">
                        <input type="password" placeholder="Password" id="user_pw" class="english_s">
                        <input type="text" id="ip_addr" hidden>
                        <button type="button" class="submit_btn" id="btnLogin">LOGIN</button>
                        <button type="button" class="join_btn">JOIN</button>
                    </div>
                </div>
                <!--//login_area -->
                <div class="join_area step01">
                    <input type="text" name="proposer" id="proposer" placeholder="추천인 코드를 입력하세요" class="english_p" style="width:250px;">
                    <div class="btn_wrap">
                        <button type="button" class="prev_btn" value="BACK" title="BACK">BACK</button>
                        <button type="button" class="next_btn join01_btn" value="START" title="START" id="btnCode">START</button>
                    </div>
                    <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
                </div>

                <div class="join_area step02">
                    <ul>
                        <li>
                            <p class="tit">아이디<span class="desc" id="id_desc">※ 영문 또는 숫자 4~16자</span></p>
                            <input type="text" name="input_id" id="input_id" class="english" placeholder="영문 또는 숫자 4~16자">
                        </li>
                        <li>
                            <p class="tit">닉네임<span class="desc" id="nickname_desc">※ 닉네임은 한글과숫자, 영문만 가능합니다. 3~12</span></p>
                            <input type="text" name="input_nickname" id="input_nickname" class="korean" placeholder="닉네임은 한글과숫자만 입력 가능합니다.">
                        </li>
                        <li>
                            <p class="tit">비밀번호<span class="desc">※ 8~20자 특수문자 한개 이상</span></p>
                            <input type="text" name="input_pw" id="input_pw" class="english_s" autocomplete="off">
                        </li>
                        <li>
                            <p class="tit">비밀번호 확인</p>
                            <input type="text" name="input_pw_check" id="input_pw_check" class="english_s" autocomplete="off" style="-webkit-text-security: disc;">
                        </li>
                    </ul>
                    <div class="btn_wrap">
                        <button type="button" class="prev_btn" value="BACK" title="BACK">BACK</button>
                        <button type="button" class="next_btn" value="NEXT" title="NEXT" id="btn_next">NEXT</button>
                    </div>
                    <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
                </div>

                <div class="join_area step03">
                    <ul>
                        <li>
                            <p class="tit">이름<span class="desc">※ 가입자명과 예금주명이 동일하게 사용됩니다.</span></p>
                            <input type="text" name="user_name" id="user_name">
                        </li>
                        <li>
                            <p class="tit">연락처</p>
                            <input type="number" pattern="[0-9]*" inputmode="numeric" min="0" onkeydown="checkNumber(event)" style="ime-mode:disabled;" name="user_phone" id="user_phone" placeholder="-없이 숫자만 입력하세요">
                        </li>
                        <li>
                            <p class="tit">은행명</p>
                            <input type="text" name="bank_name" id="bank_name">
                        </li>
                        <li>
                            <p class="tit">계좌번호</p>
                            <input type="number" pattern="[0-9]*" inputmode="numeric" min="0" onkeydown="checkNumber(event)" style="ime-mode:disabled;" name="bank_account" id="bank_account" placeholder="-없이 숫자만 입력하세요">
                        </li>
                        <li>
                            <p class="tit">환전 비밀번호</p>
                            <input type="text" name="bank_pw" id="bank_pw" autocomplete="off" style="-webkit-text-security: disc;">
                        </li>
                    </ul>
                    <div class="btn_wrap">
                        <button type="button" class="prev_btn" value="BACK" title="BACK">BACK</button>
                        <button type="button" class="next_btn" value="NEXT" title="NEXT" id="btn_done">NEXT</button>
                    </div>
                    <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
                </div>

                <div class="join_area step04">
                    <p class="txt">회원가입이 완료되었습니다.</p>
                    <p class="desc"  id="join_commment"><span class="name" id="name_complete"></span>님의 회원가입을 환영합니다.<br> 관리자 승인 후 사이트를 이용하실 수 있습니다.<br> 감사합니다.
                    </p>
                    <button type="button" class="next_btn" value="LOGIN NOW" id="btn_login">로그인</button>
                    <button type="button" class="join_close_btn"><span class="ir_pm">닫기</span></button>
                </div>
            </div>
            <!-- login_wrap -->

    </div>
    <?php if(array_key_exists("app.produce", $_ENV)) :?>
        <script src="<?php echo base_url('/js/login.js?t='.time());?>"></script>
    <?php else : ?>
        <script src="<?php echo base_url('/js/login.js?v=2');?>"></script>
    <?php endif ?>
</body>

</html>