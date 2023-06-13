<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title><?=$site_name?></title>
        <link rel="shortcut icon" href="/favicon_<?=$_ENV['app.logo']?>.ico?v=1">

        <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
            <link rel="stylesheet" href="/css/a.min.css?v=4" />
        <?php else : ?>
            <link rel="stylesheet" href="/css/a.min.css?v=<?=time()?>" />
        <?php endif ?>
        
        <link rel="stylesheet" href="/css/jquery-ui.css?ver=1" />

        <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
        <script type="text/javascript" src="/js/jquery-ui.js"></script>
        <script type="text/javascript" src="/js/jquery-form/jquery.form.js"></script>
        <script type="text/javascript" src="/js/jquery-form/jquery.validate.js"></script>

        <link rel="stylesheet" type="text/css" href="/js/semantic-ui/semantic.css" />
        <link rel="stylesheet" href="/css/bootstrap.min.css?ver=1" />
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/semantic-ui/semantic.js?v=1"></script>
        <script src="/js/worker.js?v=1"></script>
        <!--순서중요-->
        <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
            <script type="text/javascript" src="/js/vue.js"></script>
            <script type="text/javascript" src="/js/script.php.js?ver=1"></script>
            <script type="text/javascript" src="/js/lib.js?ver=1"></script>
            <script type="text/javascript" src="/js/common.js?ver=1"></script>
            <script type="text/javascript" src="/js/SLB.js?ver=4"></script>
            <script type="text/javascript" src="/js/main.js?ver=4"></script>
            <link rel="stylesheet" type="text/css" href="/css/devel.css?v=3" />
        <?php else : ?>
            <script type="text/javascript" src="/js/vue.js"></script>
            <script type="text/javascript" src="/js/script.php.js?ver=<?=time()?>"></script>
            <script type="text/javascript" src="/js/lib.js?ver=1"></script>
            <script type="text/javascript" src="/js/common.js?ver=<?=time()?>"></script>
            <script type="text/javascript" src="/js/SLB.js?ver=<?=time()?>"></script>
            <script type="text/javascript" src="/js/main.js?ver=<?=time()?>"></script>
            <link rel="stylesheet" type="text/css" href="/css/devel.css?v=<?=time()?>" />
        <?php endif ?>

        <!-- JS FILES -->

        <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/uikit@latest/dist/css/uikit.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit-icons.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="/js/uikit/uikit.min.css" />
        <script src="/js/uikit/uikit.min.js"></script>
        <script src="/js/uikit/uikit-icons.min.js"></script>

        <script src="/js/jquery.bgswitcher.js"></script>
        <script type="text/javascript" src="/js/jquery-ui/marquee.js"></script>

    <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
        <link rel="stylesheet" type="text/css" href="/css/a.custom.css?ver=4" />
        <link rel="stylesheet" type="text/css" href="/css/c.custom.css?ver=3" />
        <link rel="stylesheet" type="text/css" href="/css/darkmode.css?ver=3" />
    <?php else : ?>
        <link rel="stylesheet" type="text/css" href="/css/a.custom.css?ver=<?=time()?>" />
        <link rel="stylesheet" type="text/css" href="/css/c.custom.css?ver=<?=time()?>" />
        <link rel="stylesheet" type="text/css" href="/css/darkmode.css?ver=<?=time()?>" />
    <?php endif ?>
        <style>
            @media screen and (min-width:680px) { 
                ::-webkit-scrollbar {width:10px; height:3px; }
                ::-webkit-scrollbar-track {background:#1e1e1e; border-radius:2px 2px 0 0; }
                ::-webkit-scrollbar-thumb {background:#383838; border-radius:2px; }
                ::-webkit-scrollbar-thumb:hover {background:#383838; }
            }
            .ui-resizable-se {
                right : 0px;
                bottom: -15px;
            }
            .ui.form input[type="date"]{
                color:#eeeeee;
                background:#24425b;
            }
            .ui.form input[type="text"], .ui.form input[type="password"], .ui.form input[type="number"]{
                color:#eeeeee;
                background:#24425b;
            }
            
            .MainMenu-open-wrapper .MainMenu-LogoSlogan {
                background-image: url(/images/main/sample2.logo_<?=$_ENV['app.logo']?>.png?v=6);
            }

            <?php if(array_key_exists('app.hold', $_ENV) && $_ENV['app.hold'] == 1) :?>
                .games-page .categories-wrapper, .SeoPage .categories-wrapper {
                    /* background-image: linear-gradient(90deg,#262626, #363636, #262626); */
                    background-image: linear-gradient(360deg,#262626, #000000, #000000);
                    margin-top:16px;
                }         
                          
            <?php endif ?>
            <?php if($_ENV['app.name'] == APP_BOLTON) :?>
            .scroll_area{
                background-color: #000000;
            } 
            .SeoPage {
                background-repeat:repeat;
                background-image: url(/images/main/sample2.main_bg_<?=$_ENV['app.logo']?>.jpg?v=2);
            }
            .MainMenu-open-wrapper.js-is-game-open .MainMenu-LogoSlogan, .MainMenu-open-wrapper.js-sticky .MainMenu-LogoSlogan {
                top: 8px;
                width: 150px;
            }
            .MainMenu-open-wrapper.js-is-game-open, .MainMenu-open-wrapper.js-sticky{
                background-color: #000000;
            }
            .MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile:before,
            .MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile:after{
                background-color: #000000;
            }
            
            @media only screen and (max-width: 850px) {
                .MainMenu-open-wrapper.js-is-game-open .MainMenu-LogoSlogan, 
                .MainMenu-open-wrapper.js-sticky .MainMenu-LogoSlogan {
                    top:11px;
                    width:100px;
                    left: 90px;
                }
            }
            <?php endif ?>
            @media only screen and (max-width: 850px) {
                .MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile:before{
                    height: 0px;
                }
            }
            @media only screen and (min-width: 650px) {
                .btn-tiny .txt_cash{
                    display:block;
                } 
                .btn-tiny .icon_cash{
                    display:none;
                }
            }
            @media only screen and (max-width: 650px) {
                .btn-tiny .txt_cash{
                    display:none;
                } 
                .btn-tiny .icon_cash{
                    display:block;
                }
            }

        </style>
    </head>
    <body>
        <div
            id="SLB_film"
            onclick="SLB();"
            style="z-index: 1000; position: absolute; display: none; width: 100%; height: 100%; background-color: #000000; filter: Alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.6; -webkit-opacity: 0.6; "
        ></div>
        <div class="ui centered doubling stackable grid">
            <div id="SLB_wide" class="six wide column">
                <div id="SLB_content" style="z-index: 99999; position: absolute; background-color: #000000; filter: Alpha(opacity=97); opacity: 0.97; -moz-opacity: 0.97; -webkit-opacity: 0.97; "></div>
            </div>
        </div>
        <div id="SLB_loading"></div>

        <div id="wrapper" data-login="<?=is_login()?1:0?>" >
            <input type="checkbox" id="MainMenu-controller" />
            <label class="MainMenu-open burger at-hamburger-menu-button" for="MainMenu-controller">
                <div class="line"></div>
            </label>

            <div class="MainMenu-top-wrapper">
                <div class="MainMenu-open-wrapper <?= $_ENV['app.name'] == APP_BOLTON? "js-sticky":"" ?>"  >
                    <a href="/" class="MainMenu-LogoSlogan-mobile" style="display: none;"></a>

                    <a href="/" class="MainMenu-LogoSlogan">
                        <div class="star-logo">
                            <img src="./images/common/star1.png">
                            <!-- <img src="./images/common/star2.png"> -->
                            <!-- <img src="./images/common/star3.png"> -->
                            <img src="./images/common/star4.png">
                        </div>
                        <div class="MainMenu-LogoSlogan-wrapper"></div>

                    </a>

                    <div class="MainMenu-ActionsContainer">
                        
                        <div class="MainMenu-Left">

                            <?php if(is_login()) :?>
                                <?php if ($apps_enable && (!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] != 1) ):?>
                                    <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_app"  onclick="$('html, body').animate({scrollTop : 450}, 300); showTabMenu('auto');">
                                        <span style="padding:0px;"> <img src="/images/common/logo_app.gif" class="app_icon" /> </span>
                                    </button>
                                <?php endif?>
                                <?php if(!$user_off) :?>
                                    <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" onclick="requestCharge();"><i class="ui cloud download icon"></i><span>입금</span></button>
                                    <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" onclick="requestWithdraw();"><i class="ui cloud upload icon"></i><span>출금</span></button>
                                <?php endif?>
                            
                            <!-- <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" onclick="requestAccount()"><i class="ui question circle icon"></i><span>계좌문의</span></button> -->
                            <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_memo" onclick="SLB_POPUP('/mypage', 'my_memo')">
                                <i class="ui comment outline icon"></i><span>쪽지<span id="memo_count"></span></span>
                            </button>
                            <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_notice" onclick="SLB_POPUP('/mypage', 'notice')"><i class="ui bullhorn icon"></i><span>공지</span></button>
                            <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_info" onclick="SLB_POPUP('/mypage', '')"><i class="ui user icon"></i><span>내정보</span></button>
                            <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" id="_btn_qna" onclick="SLB_POPUP('/mypage', 'my_qna')">
                                <i class="ui comment alternate icon"></i><span>고객센터<span id="answered_count"></span></span>
                            </button>
                            <button class="js-register-open btn-register btn-tiny btn-secondary at-main-register-button" onclick="window.open('about:blank').location.href='/home/pt_login'"><i class="ui users icon"></i><span>파트너</span></button>
                                
                            <?php endif ?>

                        </div>
                        <div class="MainMenu-Right">
                            <?php if(is_login()) :?>
                            
                                <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_user_money" onclick="" style="margin-right:0px">
                                    <span class="txt_cash" style="padding:12px 0px;">보유머니</span> 
                                    <span class="icon_cash" style="padding:12px 0px; width:24px; "><img src="./images/common/won.png?v=1"></span>
                                    <span class="_has_cash" style="padding:12px 3px; color:#ff9600;"><?=number_format($user_money)?></span>
                                </button>
                            
                                <button class="js-register-open btn-register btn-tiny at-main-register-button" id="_btn_user_point" onclick="changePoint();" style="margin-left:10px">
                                    <span class="txt_cash" style="padding:12px 0px;">포인트</span> 
                                    <span class="icon_cash" style="padding:12px 0px; width:24px; "><img src="./images/common/point.png?v=1"></span>
                                    <span class="_has_point" style="padding:12px 3px; color:#ff9600;"  ><?=number_format($user_point)?></span>
                                </button>
                            <?php endif ?>
                        </div>

                        <?php if(!is_login()) :?>
                            <!-- uk-toggle="target: #agentCheckModal"  -->
                            <button class="js-login-open btn-login btn-tiny btn-secondary at-login-button" onclick="showAgentCheckModal();">
                                <span>가입</span>
                            </button>
                            <!-- uk-toggle="target: #loginModal" -->
                            <button class="js-login-open btn-login btn-tiny btn-secondary at-login-button" onclick="showLoginModal();"  >
                                <span>로그인</span>
                            </button>
                        <?php else :?>
                            <button class="js-login-open btn-login btn-tiny btn-secondary at-login-button" onclick="location.href='/home/logout'">
                                <span>로그아웃</span>     
                            </button>
                            <!-- <div class="ui uk-navbar-right nav-overlay">
                                <ul class="uk-navbar-nav after_login" style="gap:10px;">
                                    <li>
                                        <a class="bg-btn" onclick="SLB_POPUP('/mypage')">
                                            <span> <img src="/images/upload/veda_<?=$user_grade?>_icon.png" class="user_level_icon" /> <?=$user_name?> </span>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="uk-navbar-nav after_login2" style="gap:20px;">
                                    <li>
                                        <a class="bg-btn" href="/home/logout"> <span uk-icon="icon: sign-out; " class="uk-icon"> 로그아웃 </span> </a>
                                    </li>    
                                </ul>
                            </div> -->
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <div id="MainMenu" class="MainMenu">
                <div class="MainMenu-wrapper">
                    <div class="MainMenu-logo">
                        <?php if(!is_login()) :?>
                            <div class="MainMenu-play" uk-toggle="target: #agentCheckModal" tabindex="0" aria-expanded="false">
                                <a href="#" class="js-register-open btn-primary btn-normal"><span>가입</span></a>
                            </div>
                        <?php else :?>
                            <div class="MainMenu-play" onclick="SLB_POPUP('/mypage')">
                                <a href="#" class="js-register-open btn-primary btn-normal">
                                    [<?=$user_name?>] 님 환영합니다 <br />
                                    보유머니 : <span class="_has_cash"><?=$user_money?></span><br />
                                    보유포인트 : <span class="_has_point"><?=$user_point?></span>
                                </a>
                            </div>
                        <?php endif ?>
                    </div>

                    <ul class="menu menu--main MainMenu-List">
                        <?php if(!is_login()) :?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" uk-toggle="target: #loginModal" tabindex="0" aria-expanded="false">
                            <a><i class="ui sign in icon"></i> 로그인</a>
                        </li>
                        <?php endif ?>
                        
                        <?php if (!$hold_deny):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('holdem');">
                            <a><i class="ui hospital symbol icon"></i> 홀덤</a>
                        </li>
                        <?php endif ?>

                        <?php if (!$evol_deny || !$cas_deny):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('live-casino');">
                            <a><i class="ui life ring icon"></i> 카지노</a>
                        </li>
                        <?php endif ?>

                        <?php if (!$slot_deny):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('slots');">
                            <a><i class="ui hockey puck icon"></i> 슬롯</a>
                        </li>
                        <?php endif ?>

                        <?php if(!$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny || !$hpg_deny) :?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('mini');">
                            <a><i class="ui bowling ball icon"></i> 미니게임</a>
                        </li>
                        <?php endif ?>

                        <?php if ($apps_enable):?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="showTabMenu('auto');">
                            <a><i class="ui life ring outline icon"></i> 오토앱</a>
                        </li>
                        <?php endif ?>
                        <?php if(!$user_off) :?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="requestCharge();">
                            <a><i class="ui cloud download icon"></i> 입금</a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="requestWithdraw();">
                            <a><i class="ui cloud upload icon"></i> 출금</a>
                        </li>
                        <?php endif ?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="requestAccount()">
                            <a><i class="ui question circle icon"></i> 계좌문의</a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="SLB_POPUP('/mypage', 'my_memo')">
                            <a><i class="ui comment outline icon"></i> 쪽지</a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="SLB_POPUP('/mypage', 'notice')">
                            <a><i class="ui bullhorn icon"></i> 공지</a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="SLB_POPUP('/mypage', 'my_qna')">
                            <a><i class="ui comment alternate icon"></i> 고객센터<span id="answered_count"></span></a>
                        </li>
                        <?php if(is_login()) :?>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="window.open('about:blank').location.href='/home/pt_login'">
                            <a><i class="ui users icon"></i> 파트너</a>
                        </li>
                        <li class="MainMenu-item MainMenu-item--casino MainMenu-item--active-trail element" onclick="location.href='/home/logout'">
                            <a><i class="ui sign out icon"></i> 로그아웃</a>
                        </li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
            
            <div class="MainContent" id="js-main-content">
                <div class="MainContentPage">
                    <div class="SeoPage">
                        <section class="MainBanner-container banner-top">
                            <div class="container-max">
                                <div class="BannerSlider-container">
                                    <div id="main-banner-carousel" class="BannerSlider-list carousel slide js-banner-slider">
                                        <div class="carousel-inner" role="listbox">
                                            <div class="item active">
                                                <div class="block block--cta-block block--cta-block--category-video-slots block--category-video-slots" data-banner-id="category_video_slots">
                                                    <div class="BannerSlider-bg">
                                                        <div class="BannerSlider-bgDesktop">
                                                            <div class="bg-img field_decoupled_block_bg_image_category_video_slots" style=""></div>
                                                        </div>
                                                        <div class="star-container">
                                                            <img src="./images/common/star1.png">
                                                            <img src="./images/common/star2.png">
                                                            <img src="./images/common/star3.png">
                                                            <img src="./images/common/star4.png">
                                                        </div>
                                                        <div class="BannerItem-container">
                                                            <div class="BannerItem-content">
                                                                <div class="wrap">
                                                                    <div class="cont">
                                                                        <div class="field field--text-long">
                                                                        <?php if(!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] != 1) :?>
                                                                            <h1>환영합니다.</h1>
                                                                            <div class="text">
                                                                                저희 카지노는 전세계 유수의 슬롯게임과 라이브카지노를 제공하여, 회원 여러분의 만족을 위해 최선을 다합니다.
                                                                            </div>
                                                                        <?php endif ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- 메인 텍스트공지 마퀴-->
                        <div class="scroll_area">
                            <div class="scroll_text"><?=$notice_main?>
                            </div>
                        </div>
                        
                        <script type="text/javascript">
                            $(".BannerSlider-bgDesktop .field_decoupled_block_bg_image_category_video_slots").bgswitcher({
                                <?php if($_ENV['app.name'] == APP_PHANTOM) :?>
                                    images: ["/images/main/banner11.png"],
                                    effect: "hide",
                                <?php elseif($_ENV['app.name'] == APP_BOLTON) :?>
                                    images: ["/images/main/banner21.png?v=1", "/images/main/banner22.png?v=2"],
                                    effect: "clip",
                                <?php else: ?>
                                    images: ["/images/main/banner1.png", "/images/main/banner2.png", "/images/main/banner3.png", "/images/main/banner4.png"],
                                    effect: "clip",
                                <?php endif ?>
                                
                            });

                            $(".scroll_text").marquee();

                            function showTabMenu(menu) {
                                $("#live-casino").hide();
                                $("#mini").hide();
                                $("#slots").hide();
                                $("#auto").hide();
                                $("#holdem").hide();

                                if($("#holdem").length > 0)
                                    $("#img_casino").attr("src", "/images/common/tab_casino_mid.png?v=1");
                                else
                                    $("#img_casino").attr("src", "/images/common/tab_casino.png?v=1");

                                if($("#auto").length > 0)
                                    $("#img_mini").attr("src", "/images/common/tab_mini.png?v=1");
                                else 
                                    $("#img_mini").attr("src", "/images/common/tab_mini_rit.png?v=1");
                                
                                $("#img_slots").attr("src", "/images/common/tab_slot.png?v=1");
                                $("#img_auto").attr("src", "/images/common/tab_auto.png?v=1");
                                $("#img_holdem").attr("src", "/images/common/tab_holdem.png?v=1");

                                if (menu == "slots") {
                                    $("#slots").fadeIn("slow");
                                    $("#img_slots").attr("src", "/images/common/tab_slot_select.png?v=1");
                                } else if (menu == "mini") {
                                    $("#mini").fadeIn("slow");
                                    if($("#auto").length > 0)
                                        $("#img_mini").attr("src", "/images/common/tab_mini_select.png?v=1");
                                    else 
                                        $("#img_mini").attr("src", "/images/common/tab_mini_select_rit.png?v=1");
                                } else if (menu == "auto") {
                                    $("#auto").fadeIn("slow");
                                    $("#img_auto").attr("src", "/images/common/tab_auto_select.png?v=1");
                                } else if (menu == "holdem") {
                                    $("#holdem").fadeIn("slow");
                                    $("#img_holdem").attr("src", "/images/common/tab_holdem_select.png?v=1");
                                } else {
                                    $("#live-casino").fadeIn("slow");
                                    if($("#holdem").length > 0)
                                        $("#img_casino").attr("src", "/images/common/tab_casino_select_mid.png?v=1");
                                    else
                                        $("#img_casino").attr("src", "/images/common/tab_casino_select.png?v=1");
                                }
                            }

                            $("#MainMenu .MainMenu-play, #MainMenu .MainMenu-item").click(function(e) {
                                $("#MainMenu-controller").prop("checked", false);
                            });

                        </script>
                        <div class="seoCategoryPage-category categories-wrapper js-seo-category-page-categories">
                            <div class="categories categories-desktop js-games-categories-slider slick-initialized slick-slider">
                                <div class="ui two column centered grid">
                                    <div class="five column centered row">
                                        <?php if (!$hold_deny):?>
                                            <div class="column first">
                                                <img src="/images/common/tab_holdem_select.png?v=1" onclick="javascript:showTabMenu('holdem');" id="img_holdem" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                        <?php if (!$evol_deny || !$cas_deny):?>
                                            <div class="column first">
                                                <img 
                                                    <?php if (!$hold_deny):?>
                                                        src="/images/common/tab_casino_mid.png?v=1" 
                                                    <?php else :?>
                                                        src="/images/common/tab_casino_select.png?v=1" 
                                                    <?php endif ?>
                                                    onclick="javascript:showTabMenu('live-casino');" id="img_casino" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                        <?php if (!$slot_deny):?>
                                            <div class="column first">
                                                <img src="/images/common/tab_slot.png?v=1" onclick="javascript:showTabMenu('slots');" id="img_slots" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                        
                                        <?php if(!$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny || !$hpg_deny) :?>
                                        <div class="column first">
                                            <img     
                                            <?php if ($apps_enable):?>
                                                src="/images/common/tab_mini.png?v=1" 
                                            <?php else:?>
                                                src="/images/common/tab_mini_rit.png?v=1" 
                                            <?php endif ?>
                                            onclick="javascript:showTabMenu('mini');" id="img_mini" style="cursor: pointer;" />
                                        </div>
                                        <?php endif ?>
                                        <?php if ($apps_enable):?>
                                            <div class="column first">
                                                <img src="/images/common/tab_auto.png?v=1" onclick="javascript:showTabMenu('auto');" id="img_auto" style="cursor: pointer;" />
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!$slot_deny):?>
                        <section class="uk-section" id="slots"  style="display: none;">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">SLOT GAMES
                                    <div class="star-title">
                                        <img src="./images/common/star1.png">
                                        <img src="./images/common/star2.png">
                                        <img src="./images/common/star3.png">
                                        <img src="./images/common/star4.png">
                                        <img src="./images/common/star1.png">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <?php foreach ($slot_plus as $item):?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/slot/<?=$item->img?>.png" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="<?=$item->name_kr?>" data-cid="<?=$item->code?>" data-cname="<?=$item->name_kr?>"
                                                                <?php if($item->maintain==1) :?>
                                                                    data-onoff="off">점검중입니다
                                                                <?php else :?>
                                                                    data-onoff="on">Play
                                                                <?php endif ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"><?=$item->name_kr?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>

                        <?php if (!$evol_deny || !$cas_deny):?>
                        <section class="uk-section" id="live-casino"  
                            <?php if (!$hold_deny):?>
                                style="display: none;"
                            <?php endif ?>
                            >

                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">
                                    LIVE CASINO
                                    <div class="star-title">
                                        <img src="./images/common/star1.png">
                                        <img src="./images/common/star2.png">
                                        <img src="./images/common/star3.png">
                                        <img src="./images/common/star4.png">
                                        <img src="./images/common/star1.png">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match uk-grid-stack" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <?php foreach ($cas_evol as $item):?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="/images/casino/<?=$item->img?>.png" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">

                                                                <button class="uk-button uk-button-primary playBtn" id="playBtn" data-cid="<?=$item->cas_id?>" data-gameid="<?=$item->cat?>" 
                                                                    <?php if($item->maintain==1) :?>
                                                                        data-onoff="off">점검중입니다
                                                                    <?php else :?>
                                                                        data-onoff="on">Play
                                                                    <?php endif ?>
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand">
                                                            <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"><?=$item->name?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                    <?php foreach ($cas_kgon as $item):?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="/images/casino/<?=$item->img?>.png?v=1" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary playBtn" id="playBtn" data-cid="<?=$item->cas_id?>" data-gameid="<?=$item->cat?>"
                                                                    <?php if($item->maintain==1) :?>
                                                                        data-onoff="off">점검중입니다
                                                                    <?php else :?>
                                                                        data-onoff="on">Play
                                                                    <?php endif ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand">
                                                            <span class="game_title <?=$item->maintain==1?'gray':'blue'?>"><?=$item->name?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>

                        <?php if(!$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny || !$hpg_deny) :?>
                        <section class="uk-section" id="mini" style="display: none;">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">MINI GAMES
                                    <div class="star-title">
                                        <img src="./images/common/star1.png">
                                        <img src="./images/common/star2.png">
                                        <img src="./images/common/star3.png">
                                        <img src="./images/common/star4.png">
                                        <img src="./images/common/star1.png">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <?php if(!$hpg_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_hpb.png?v=2" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="해피파워볼" data-onoff="on" data-cid="HPB">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">해피파워볼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$bpg_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_bgb.png?v=2" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="보글파워볼" data-onoff="on" data-cid="BGB">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">보글파워볼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_bgl.png?v=2" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="보글사다리" data-onoff="on"  data-cid="BGL">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">보글사다리</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$eos5_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_eos5.png?v=2" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="EOS5분파워볼" data-onoff="on"  data-cid="EOS5">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">EOS5분파워볼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$eos3_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_eos3.png?v=2" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="EOS3분파워볼" data-onoff="on" data-cid="EOS3">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">EOS3분파워볼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$coin5_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_coin5.png?v=2" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="코인5분파워볼" data-onoff="on" data-cid="COIN5">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">코인5분파워볼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <?php if(!$coin3_deny) :?>
                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_coin3.png?v=2" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="코인3분파워볼" data-onoff="on" data-cid="COIN3">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column">
                                                            <span class="game_title blue">코인3분파워볼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    
                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>
                        
                        <?php if (!$hold_deny):?>
                        <section class="uk-section" id="holdem">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">HOLDEM GAME
                                    <div class="star-title">
                                        <img src="./images/common/star1.png">
                                        <img src="./images/common/star2.png">
                                        <img src="./images/common/star3.png">
                                        <img src="./images/common/star4.png">
                                        <img src="./images/common/star1.png">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match uk-flex-center" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                    <img src="/images/mini/btn_hold.png?v=1" />
                                                    <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                        <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                            <button class="uk-button uk-button-primary openGameBtn uk-first-column" id="playBtn" title="홀덤" data-onoff="on" data-cid="Holdem">Play </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand uk-first-column uk-flex uk-flex-center">
                                                            <span class="game_title blue">홀덤</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- item -->
                                </div>
                            </div>
                        </section>
                        <?php endif ?>

                        <?php if($apps_enable):?>
                        <!-- Auto Apps -->
                        <section class="uk-section" id="auto" style="display: none;">
                            <div class="uk-container">
                                <h2 style="margin-bottom: 20px; text-align: center;">
                                    AUTO APPS
                                    <div class="star-title">
                                        <img src="./images/common/star1.png">
                                        <img src="./images/common/star2.png">
                                        <img src="./images/common/star3.png">
                                        <img src="./images/common/star4.png">
                                        <img src="./images/common/star1.png">
                                    </div>
                                </h2>
                                <div class="uk-grid uk-grid-small uk-child-width-1-3 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-5@xl uk-grid-match uk-grid-stack" data-uk-lightbox="toggle:a.uk-position-cover" data-uk-grid="">
                                    <!-- item -->
                                    <?php foreach ($apps_auto as $item):?>

                                        <div>
                                            <div class="uk-card uk-card-default uk-card-small">
                                                <div class="uk-card-media-top">
                                                    <div class="uk-inline-clip uk-transition-toggle uk-light">
                                                        <img src="/images/app/<?=$item->ename?>.png?v=2" />
                                                        <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-primary uk-flex uk-flex-center uk-flex-middle">
                                                            <div data-uk-margin="" class="uk-transition-slide-bottom-small">
                                                                <button class="uk-button uk-button-primary playBtn" id="playBtn" data-name="<?=$item->name?>" data-path="<?=$item->path?>">Download</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-card-header">
                                                    <div class="uk-grid-small uk-flex uk-flex-middle uk-grid uk-grid-stack" data-uk-grid="">
                                                        <div class="uk-width-expand">
                                                            <span class="game_title blue"><?=$item->name?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- item -->
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </section>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <section class="FooterSection">
                <div class="PaymentIconsContainer standard">
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--visa"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--mastercard"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--maestro"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--neteller"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--skrill"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--trustly"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--sofort"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--giropay"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--paysafecard"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--boku"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--euteller"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--ecopayz"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--interac"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--idebit"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--instadebit"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg?v=1#color--easyeft2"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--jeton"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--paypal"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--ecobanq"></use>
                        </svg>
                    </div>

                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--applepay"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--astropay"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--neosurf"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--ezeewallet"></use>
                        </svg>
                    </div>
                    <div class="Payment-icon">
                        <svg>
                            <use xlink:href="/images/sprite.svg#color--bank-lm"></use>
                        </svg>
                    </div>
                </div>

                <!-- <section class="region region--licensing">
                    <div class="block block--basic-block block--basic-block--licensing-and-regulation block--licensing-and-regulation">
                        <h2>Licensing and Regulation</h2>
                        <div class="field field--text-long">
                            <p>
                                WJoy Global Limited is incorporated under the laws of Malta (C65325) at registered address 28, GB Buildings, Level 3, Watar Street, Ta’ Xbiex, XBX 1301, Malta. WJoy Global Limited is licensed and regulated by
                                the <a href="#">Malta Gaming Authority</a> with licence number MGA/B2C/314/2015 issued on the 5th August 2016 and also by the <a href="#">British Gambling Commission</a> with account 45235. Gambling can be
                                harmful; our <a href="#">Responsible Gaming page</a> helps you to stay in control.
                            </p>

                            <p></p>

                            <p>UNDERAGE GAMBLING IS AN OFFENCE.</p>

                            <p></p>

                            <p><a href="#">WJoy Casino ES</a> is also in possession of a Spanish License which is regulated by the Directorate General for the Regulation of Gambling (DGOJ) with licence number GO/2018/027.</p>
                        </div>
                        <div class="field field--boolean"></div>
                    </div>
                </section> -->

                <div class="Footer">
                    <div class="Footer-wrapper">
                    <span  style="font-size:17px; padding:10px;">Copyright 2022 <span style="color:white"><?=$site_name?></span>. All right reserved.</span>
                        <!-- <ul class="menu menu--footer menu-root">
                            <li class="menu-item at-privacy-policy-footer-link">
                                <a href="#">Privacy Policy</a>
                            </li>

                            <li class="menu-item at-about-us-footer-link">
                                <a href="#">About Us</a>
                            </li>

                            <li class="menu-item at-affilliate-program-footer-link">
                                <a href="#">Affiliate Program</a>
                            </li>

                            <li class="menu-item at-responsible-gaming-footer-link">
                                <a href="#">Responsible Gaming</a>
                            </li>

                            <li class="menu-item at-terms-and-conditions-footer-link">
                                <a href="#">Terms and Conditions</a>
                            </li>

                            <li class="menu-item at-bonus-terms-and-conditions-link">
                                <a href="#">Bonus Terms &amp; Conditions</a>
                            </li>
                        </ul> -->
                    </div>
                </div>
            </section>
        </div>

        <!--MODAL-->
        <div id="loginModal" uk-modal class="uk-modal">
            <div class="uk-modal-dialog">
                <form class="ui form equal width" name="formLogin" id="formLogin" autocomplete="off">
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">로그인</h2>
                    </div>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                    <div class="uk-modal-body">
                        <div class="ui fields">
                            <div class="field required">
                                <label>아이디 </label>
                                <input type="text" name="userid" placeholder="userid" />
                            </div>

                            <div class="field required">
                                <label>비밀번호 </label>
                                <input type="password" name="passwd" placeholder="password" />
                            </div>
                            <input type="text" name="ip" id="ip_addr" hidden/>
                        </div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary submit button" type="submit">로그인하기</button>
                        <div class="ui uk-modal-close button">취소</div>
                    </div>
                </form>
            </div>
        </div>
        <div id="agentCheckModal" uk-modal class="uk-modal">
            <div class="uk-modal-dialog">
                <form class="ui form equal width" name="agentCheckForm" id="agentCheckForm" autocomplete="off">
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">추천인 입력</h2>
                    </div>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                    <div class="uk-modal-body">
                        <div class="ui fields">
                            <div class="field required">
                                <label>추천인 아이디 </label>
                                <input type="text" name="recommender_id" id="recommender_id" placeholder="추천인 아이디" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary submit button" type="button">다음단계</button>
                        <div class="ui uk-modal-close button">취소</div>
                    </div>
                </form>
            </div>
        </div>
        <div id="registModal" uk-modal class="uk-modal">
            <div class="uk-modal-dialog">
                <form class="ui form equal width" name="fregisterform" id="fregisterform" autocomplete="off">
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title">가입정보 입력</h2>
                    </div>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                    <div class="uk-modal-body">
                        <div class="fields">
                            <div class="twelve wide field">
                                <label>회원아이디 </label>
                                <input type="text" name="userid" id="userid" placeholder="4자~16자, 영문 또는 숫자" minlength="4" maxlength="16" />
                            </div>
                            <div class="two wide field">
                                <label>&nbsp;</label>
                                <button class="ui teal button" type="button" onclick="checkDupUserid();">중복확인</button>
                            </div>
                        </div>

                        <div class="two fields">
                            <div class="field">
                                <label>비밀번호 </label>
                                <input type="password" name="passwd" id="passwd" placeholder="8자~20자, 특수문자 한개 이상" />
                            </div>
                            <div class="field">
                                <label>비밀번호 확인 </label>
                                <input type="password" name="passwd_re" id="passwd_re" placeholder="confirm password" />
                            </div>
                        </div>

                        <div class="field">
                            <label>닉네임 </label>
                            <input type="text" name="nickname" id="nickname" placeholder="nickname" />
                        </div>

                        <div class="two fields">
                            <div class="field required">
                                <label>출금계좌 은행 </label>
                                <select name="bank_name" id="bank_name">
                                    <option value="">은행선택</option>
                                    <option value="국민은행">국민은행</option>
                                    <option value="농협">농협</option>
                                    <option value="우리은행">우리은행</option>
                                    <option value="신한은행">신한은행</option>
                                    <option value="하나은행">하나은행</option>
                                    <option value="외환은행">외환은행</option>
                                    <option value="기업은행">기업은행</option>
                                    <option value="제일은행">제일은행</option>
                                    <option value="부산은행">부산은행</option>
                                    <option value="제주은행">제주은행</option>
                                    <option value="경남은행">경남은행</option>
                                    <option value="광주은행">광주은행</option>
                                    <option value="전북은행">전북은행</option>
                                    <option value="대구은행">대구은행</option>
                                    <option value="시티은행">시티은행</option>
                                    <option value="우체국">우체국</option>
                                    <option value="상호저축은행">상호저축은행</option>
                                    <option value="수협">수협</option>
                                    <option value="산업은행">산업은행</option>
                                    <option value="신협중앙회">신협중앙회</option>
                                    <option value="새마을금고">새마을금고</option>
                                    <option value="메리츠증권">메리츠증권</option>
                                    <option value="대우증권">대우증권</option>
                                    <option value="동양종금">동양종금</option>
                                    <option value="삼성생명">삼성생명</option>
                                    <option value="미래에셋">미래에셋</option>
                                    <option value="현대증권">현대증권</option>
                                    <option value="삼성증권">삼성증권</option>
                                    <option value="한국투자증권">한국투자증권</option>
                                    <option value="우리투자증권">우리투자증권</option>
                                    <option value="하이투자증권">하이투자증권</option>
                                    <option value="HMC투자증권">HMC투자증권</option>
                                    <option value="대신증권">대신증권</option>
                                    <option value="한화증권">한화증권</option>
                                    <option value="신한금융투자">신한금융투자</option>
                                    <option value="카카오뱅크">카카오뱅크</option>
                                    <option value="K뱅크">K뱅크</option>
                                    <option value="유안타증권">유안타증권</option>
                                </select>
                            </div>
                            <div class="field required">
                                <label>출금계좌 소유자명</label>
                                <input type="text" id="bank_owner" name="bank_owner" placeholder="Bank Owner Name" />
                            </div>
                        </div>

                        <div class="two fields">
                            <div class="field required">
                                <label>출금계좌 번호</label>
                                <input type="text" id="bank_account" name="bank_account" value="" size="10" maxlength="30" pattern="[0-9\-]+" />
                            </div>
                            <div class="field required">
                                <label>출금 비밀번호</label>
                                <input type="text" id="refund_password" name="refund_password" value="" />
                            </div>
                        </div>
                        
                        <div class="field">
                            <label>추천인 </label>
                            <input type="text" name="agent_id" id="agent_id" class="frm_input required" readonly="readonly" />
                        </div>

                        <div class="field">
                            <label>휴대폰 번호</label>
                            <input type="text" id="phone" name="phone" placeholder="000-0000-0000" />
                        </div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary button" type="submit">가입하기</button>
                        <div class="ui uk-modal-close button" onclick='document.getElementById("agentCheckForm").reset();document.getElementById("fregisterform").reset();'>취소</div>
                    </div>
                </form>
            </div>
        </div>

        <div id="vue_modal">
            <div id="request_charge" uk-modal class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="chargeForm" id="chargeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud download icon"></i> 입금요청</h3></div>
                        <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                        <div class="uk-modal-body">
                            <div class="field required">
                                <label>요청금액</label>
                                <div class="ui input"><input type="number" name="cash" id="cash" placeholder="입금요청하실 금액을 만원단위로 입력해주세요" step="10000" /></div>
                                <div style="padding-top: 5px; text-align:right;">
                                    <button type="button" onclick="setMoneyField('cash',10000)" class="ui inverted blue mini button">1만</button> <button type="button" onclick="setMoneyField('cash',50000)" class="ui inverted blue mini button">5만</button>
                                    <button type="button" onclick="setMoneyField('cash',100000)" class="ui inverted blue mini button">10만</button> <button type="button" onclick="setMoneyField('cash',500000)" class="ui inverted blue mini button">50만</button>
                                    <button type="button" onclick="setMoneyField('cash',1000000)" class="ui inverted blue mini button">100만</button> <button type="button" onclick="setMoneyField('cash',0)" class="ui inverted blue mini button">다시입력</button>
                                </div>
                            </div>
                            <div class="field required">
                                <label>입금자 명</label>
                                <div class="ui input">
                                    <input type="text" name="req_name_replaced" v-model="myInfo.user_bank_own" placeholder="입금자 명" readonly="readonly" /> 
                                </div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">입금요청하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                </div>
            </div>
            <div id="request_exchange" uk-modal class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="exchangeForm" id="exchangeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud upload icon"></i> 출금신청</h3></div>
                        <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                        <div class="uk-modal-body">
                            <div class="inline field">
                                <label>현재 보유 머니</label>
                                <div class="ui teal label"><span class="_has_cash">{{myInfo.user_money}}</span> 원</div>
                            </div>

                            <div class="inline field required">
                                <label>신청금액</label>
                                <div class="ui input"><input type="number" name="cash" id="cash_out" placeholder="출금요청하실 금액을 만원단위로 입력해주세요" step="10000" /></div>
                                <div style="padding-top: 5px; text-align:right;">
                                    <button type="button" onclick="setMoneyField('cash_out',10000)" class="ui inverted blue mini button">1만</button> <button type="button" onclick="setMoneyField('cash_out',50000)" class="ui inverted blue mini button">5만</button>
                                    <button type="button" onclick="setMoneyField('cash_out',100000)" class="ui inverted blue mini button">10만</button> <button type="button" onclick="setMoneyField('cash_out',500000)" class="ui inverted blue mini button">50만</button>
                                    <button type="button" onclick="setMoneyField('cash_out',1000000)" class="ui inverted blue mini button">100만</button> <button type="button" onclick="setMoneyField('cash_out',0)" class="ui inverted blue mini button">다시입력</button>
                                </div>
                            </div>
                            <h4 class="ui dividing teal header">출금정보</h4>
                            <div class="inline field">
                                <label>계좌주&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_own" /> </div>
                            </div>
                            <div class="inline field">
                                <label>은행명&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_name" /> </div>
                            </div>
                            <div class="inline field">
                                <label>계좌번호&nbsp;</label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_num" /> </div>
                            </div>
                            
                            <div class="inline field">
                                <label>출금비번&nbsp;</label>
                                <div class="ui input"><input type="text" name="bank_passwd" /></div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">출금신청하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                </div>
            </div>
            <div id="change_pwd" uk-modal class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="chgpwdForm" id="chgpwdForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title">비번변경</h3></div>
                        <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                        <div class="uk-modal-body">
                            <div class="field required">
                                <label>현재 비밀번호</label>
                                <div class="ui input">
                                    <input type="text" name="pwd_old" id="pwd_old" placeholder="현재 비밀번호" /> 
                                </div>
                            </div>
                            <div class="field required">
                                <label>새 비밀번호</label>
                                <div class="ui input">
                                    <input type="text" name="pwd_new" id="pwd_new" placeholder="새 비밀번호" /> 
                                </div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">변경하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                </div>
            </div>
        </div>

        
        <script>
            function SLB_POPUP(page, tab) {
                if(!check_login()){
                    return;
                }
                if (typeof tab == "undefined") tab = "";
                UIkit.modal("#request_charge").hide();
                UIkit.modal("#request_exchange").hide();

                SLB(page + "?tab=" + tab, {
                    width: "ten wide column",
                    caption: "<i class='user icon'></i><?=$user_id?> (<?=$user_name?>)",
                });
            }

            // validate rule check
            var validationLoginRules = {
                userid: {
                    identifier: "userid",
                    rules: [
                        {
                            type: "empty",
                        },
                        // {
                        //     type: "minLength[2]",
                        // },
                        // {
                        //     type: "maxLength[16]",
                        // },
                    ],
                },
                passwd: {
                    identifier: "passwd",
                    rules: [
                        {
                            type: "empty",
                        },
                        // {
                        //     type: "minLength[2]",
                        // },
                    ],
                },
            };
            $("#formLogin").form({
                fields: validationLoginRules,
                inline: true,
                on: "submit",
                onSuccess: function (event) {
                    return true;
                },
            });

            $("#formLogin").ajaxForm({
                dataType: "json",
                url: "/api/login",
                type: "POST",
                data: $(this).serialize(),
                beforeSubmit: function () {
                    //return $('#formLogin').valid();
                },
                success: function (response) {
                    if (response.status == 'success') {
                        setLogCookie('logged', 'yes', 0);
                        location.reload();
                        // window.location.href = "/home";
                    } else if (response.status == 'fail') {
                        if(response.code == 9){  //점검중
                            
                        } else {
                            
                        }
                        alert(response.msg);
                    }

                },
                error: function(request, status, error) {
                    console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            });

            var validationRegRules = {
                userid: {
                    identifier: "userid",
                    rules: [
                        {
                            type: "empty",
                            prompt: "회원아이디를 입력해주세요",
                        },
                        {
                            type: "minLength[4]",
                            prompt: "회원아이디를 4글자이상 입력해주세요",
                        },
                        {
                            type: "maxLength[16]",
                            prompt: "회원아이디를 16글자이하 입력해주세요",
                        },
                    ],
                },
                passwd: {
                    identifier: "passwd",
                    rules: [
                        {
                            type: "empty",
                            prompt: "비밀번호를 입력해주세요",
                        },
                        {
                            type: "minLength[4]",
                            prompt: "비밀번호를 4글자이상 입력해주세요",
                        },
                    ],
                },
                passwd_re: {
                    identifier: "passwd_re",
                    rules: [
                        {
                            type: "empty",
                            prompt: "비밀번호 확인을 입력해주세요",
                        },
                        {
                            type: "match[passwd]",
                            prompt: "비밀번호 확인을 정확히 입력해주세요.",
                        },
                    ],
                },
                name: {
                    identifier: "name",
                    rules: [
                        {
                            type: "empty",
                        },
                    ],
                },
                nickname: {
                    identifier: "nickname",
                    rules: [
                        {
                            type: "empty",
                            prompt: "닉네임을 입력해주세요",
                        },
                    ],
                },
                bank_name: {
                    identifier: "bank_name",
                    rules: [
                        {
                            type: "empty",
                            prompt: "출금계좌 은행을 선택해주세요",
                        },
                    ],
                },
                bank_owner: {
                    identifier: "bank_owner",
                    rules: [
                        {
                            type: "empty",
                            prompt: "출금계좌 소유자명을 입력해주세요",
                        },
                    ],
                },
                bank_account: {
                    identifier: "bank_account",
                    rules: [
                        {
                            type: "empty",
                            prompt: "출금계좌 번호를 입력해주세요",
                        },
                    ],
                },
                refund_password: {
                    identifier: "refund_password",
                    rules: [
                        {
                            type: "empty",
                            prompt: "출금 비밀번호를 입력해주세요",
                        },
                    ],
                },
                agent_id: {
                    identifier: "agent_id",
                    rules: [
                        {
                            type: "empty",
                        },
                    ],
                },
                phone: {
                    identifier: "phone",
                    rules: [
                        {
                            type: "empty",
                            prompt: "휴대폰 번호를 입력해주세요",
                        },
                    ],
                },
            };
            $("#fregisterform").form({
                fields: validationRegRules,
                inline: true,
                on: "submit",
                onSuccess: function (event) {
                    return true;
                },
            });
            $("#fregisterform").ajaxForm({
                dataType: "json",
                type: "POST",
                url: "/api/register",
                data: $(this).serialize(),
                beforeSubmit: function () {
                    return $('#fregisterform').valid();
                },
                success: function (response) {
                    if (response.status == "success") {
                        alert("가입이 신청되었습니다.\n관리자 승인 후 사이트를 이용하실 수 있습니다.\n감사합니다.");
                        UIkit.modal("#loginModal").show();
                    } else {
                        alert(response.msg);
                    }
                },
            });
            $("#agentCheckForm").form({
                fields: validationRegRules,
                inline: true,
                on: "submit",
                onSuccess: function (event) {
                    return true;
                },
            });
            $("#agentCheckForm").ajaxForm({
                dataType: "json",
                type: "POST",
                url: "/api/check_proposer",
                data: $(this).serialize(),
                beforeSubmit: function () {},
                success: function (response) {
                    if (response.status == "success") {
                        UIkit.modal("#registModal").show();
                        $("#fregisterform #agent_id").val($("#recommender_id").val());
                    } else {
                        alert(response.msg);
                    }
                },
            });


            function eventControl() {
                return true;
            }

            function onWindowScroll(){
                if ($(window).scrollTop() > 0) {
                    $(".MainMenu-open-wrapper").addClass("js-sticky");
                    $(".MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile").show();
                    $(".MainMenu-open-wrapper .star-logo").hide();
                } else {
                    <?php if($_ENV['app.name'] == APP_BOLTON) :?>
                        $(".MainMenu-open-wrapper").addClass("js-sticky");
                        $(".MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile").show();
                    <?php else :?>
                        $(".MainMenu-open-wrapper").removeClass("js-sticky");
                        $(".MainMenu-open-wrapper .MainMenu-LogoSlogan-mobile").hide();
                    <?php endif ?>
                    $(".MainMenu-open-wrapper .star-logo").show();
                }
            }

            $(document).ready(function () {
                document.onselectstart = eventControl;
                document.oncontextmenu = eventControl;

                onWindowScroll();
                $(window).scroll(function () {
                    onWindowScroll();
                });

                // LoadUserHasInfo();
                $(".dropdown").dropdown({
                    action: "select",
                });

                $.getJSON("https://jsonip.com/",
                    function(json) {
                        // console.log("ip2="+json.ip);
                        if(json.ip !== undefined && json.ip.length > 0){
                            $("#ip_addr").val(json.ip);
                            console.log("jsonip="+json.ip);
                        }
                    }
                );

                $.getJSON("https://api.ipify.org?format=jsonp&callback=?",
                    function(json) {
                        // console.log("ip1="+json.ip);
                        if(json.ip !== undefined && json.ip.length > 0){
                            $("#ip_addr").val(json.ip)
                            console.log("ipify="+json.ip);
                        }
                    }
                );

                $("#slots .openGameBtn").click(function () {
                    {
                        var onoff = $(this).data("onoff");
                        var game_id = $(this).data("cid");
                        var message = "점검중입니다."; //$(this).data('message');
                        if (onoff == "on") {
                            openSlotGame(game_id, $(this).data("cname"));
                        } else {
                            alert(message);
                        }
                    }
                });

                $("#casinos .openGameBtn, #live-casino .playBtn").click(function () {
                    var onoff = $(this).data("onoff");
                    if (onoff == "on") {
                        openCasinoGame($(this).data("cid"), $(this).data("gameid"));
                    } else {
                        alert("점검중입니다.");
                    }
                });
                $("#holdem .openGameBtn, #holdem .playBtn").click(function () {
                    if(!check_login()){
                        return;
                    }
                    var onoff = $(this).data("onoff");
                    if (onoff == "on") {
                        window.open("/holdem", "games", "width=1200, height=800, left=100, top=50");
                    } else {
                        alert("점검중입니다.");
                    }
                });
                $("#auto .openGameBtn, #auto .playBtn").click(function () {
                    if(!check_login()){
                        return;
                    }
                    var name = $(this).data("name");
                    var path = $(this).data("path");

                    if(name.length > 0 && path.length > 0){
                        if(confirm("'" + name + "'을 다운하시겠습니까?")){
                            window.open(path);
                        }
                    }
                });

                $("#mini .openGameBtn").click(function () {
                    if(!check_login()){
                        return;
                    }
                    var onoff = $(this).data("onoff");
                    if (onoff == "on") {
                        window.open("/mini?gm="+$(this).data("cid"), "games", "width=1200, height=800, left=100, top=50");
                    } else {
                        alert("점검중입니다.");
                    }
                });

                // validate rule check
                var validationChgRules = {
                    cash: {
                        identifier: "cash",
                        rules: [
                            {
                                type: "empty",
                                prompt: "요청하실 금액을 숫자로 입력해주세요",
                            },
                            {
                                type: "minLength[5]",
                                prompt: "최소 1만원 이상 입력해주세요",
                            },
                        ],
                    },
                    point: {
                        identifier: "point",
                        rules: [
                            {
                                type: "empty",
                                prompt: "요청하실 롤링금을 숫자로 입력해주세요",
                            },
                            {
                                type: "minLength[5]",
                                prompt: "최소 1만원 이상 입력해주세요",
                            },
                        ],
                    },
                    req_name: {
                        identifier: "req_name",
                        rules: [
                            {
                                type: "empty",
                                prompt: "입금하실분의 명칭을 입력해주세요",
                            },
                        ],
                    },
                    bank_passwd: {
                        identifier: "bank_passwd",
                        rules: [
                            {
                                type: "empty",
                                prompt: "출금 비밀번호를 입력해주세요",
                            },
                        ],
                    },
                    pwd_old: {
                        identifier: "pwd_old",
                        rules: [
                            {
                                type: "empty",
                                prompt: "현재 비밀번호를 입력해주세요",
                            }
                        ],
                    },
                    pwd_new: {
                        identifier: "pwd_new",
                        rules: [
                            {
                                type: "empty",
                                prompt: "새 비밀번호를 입력해주세요",
                            },
                            {
                                type: "minLength[3]",
                                prompt: "최소 3글자 이상 입력해주세요",
                            },
                        ],
                    },
                };

                $("#chargeForm").form({
                    fields: validationChgRules,
                    inline: true,
                    on: "submit",
                    onSuccess: function (event) {
                        return true;
                    },
                });

                // 입금요청
                $("#chargeForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/register_charge",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#chargeForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            UIkit.modal.alert("정상적으로 입금요청이 신청되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                                // location.reload();
                            });
                        } else if (response.status == "fail") {
                            alert(response.msg);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });

                $("#exchangeForm").form({
                    fields: validationChgRules,
                    inline: true,
                    on: "submit",
                    onSuccess: function (event) {
                        return true;
                    },
                });
                //출금요청
                $("#exchangeForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/register_exchange",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#exchangeForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            UIkit.modal.alert("정상적으로 출금요청이 신청되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                                session_check();
                                // objMain.getMyInfo();
                                //location.reload();
                            });
                        } else if (response.status == "fail") {
                            alert(response.msg);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });

                $("#chgpwdForm").form({
                    fields: validationChgRules,
                    inline: true,
                    on: "submit",
                    onSuccess: function (event) {
                        return true;
                    },
                });
                //비밀번호 변경요청
                $("#chgpwdForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/change_pass",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#chgpwdForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            UIkit.modal.alert("비밀번호가 변경되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                            });
                        } else if (response.status == "fail") {
                            alert(response.msg);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });
            });

            function requestAccount() {
                if(!check_login()){
                    return;
                }

                let title = "[빠른문의] 입금계좌요청";
                let content = "빠른문의 : 입금계좌요청";
                if (confirm(title + " \n\n을(를) 보내시겠습니까?\n\n(입금계좌 답변은  1:1 문의에서 확인하세요)") == false) return false;

                $.post(
                    "/api/request_account3",
                    {
                        title: title,
                        content: content,
                    },
                    function (response) {
                        if (response.status == "success") {
                            alert("정상적으로 요청 되었습니다\n\n입금계좌 답변은  1:1 문의에서 확인하세요");
                        } else {
                            alert(response.message);
                        }
                    },
                    "json"
                );
            }

            function showAgentCheckModal() {
                // if(check_login()){
                //     return;
                // }
                SLB(); 
                UIkit.modal("#agentCheckModal").show();
            }
            
            function showLoginModal() {
                // if(check_login()){
                //     return;
                // }
                SLB(); 
                UIkit.modal("#loginModal").show();
            }

            function requestCharge() {
                if(!check_login()){
                    return;
                }
                SLB(); 
                UIkit.modal("#request_charge").show();
            }

            function requestWithdraw() {
                if(!check_login()){
                    return;
                }
                SLB(); 
                objMain.getMyInfo();
                UIkit.modal("#request_exchange").show();
            }

            function changePwd() {
                if(!check_login()){
                    return;
                }
                UIkit.modal("#change_pwd").show();
            }

            function changePoint() {

                if($("#_btn_user_point ._has_point").text().length < 2){
                    return;
                }

                UIkit.modal.confirm("포인트를 머니로 전환하시겠습니까?", {labels: {'ok': '확인', 'cancel': '취소'}}).then(
                    function () {
                        $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "/api/change_point",
                            success: function (response) {
                                if (response.status == "success") {
                                    UIkit.modal.alert("머니로 전환되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                                        session_check();
                                    });
                                } else {
                                    // alert(response.msg);
                                }
                            },
                        });
                    },
                    function () {
                        //취소
                    }
                );

            }

            var objMain = new Vue({
                el: "#vue_modal",
                data: {
                    myInfo: [],
                },
                methods: {
                    getMyInfo: function () {
                        $.get(
                            "/api/myinfo",
                            function (response) {
                                // console.log(response);
                                if (response.status == "success") {
                                    objMain.myInfo = response.data;
                                }  else if (response.status == "logout") {
                                    // console.log("myinfo logout");
                                }
                            },
                            "json"
                        );
                    },
                },
                mounted: function () {
                    this.getMyInfo();
                },
                filters: {
                    number_format: function (value) {
                        return new Intl.NumberFormat("ko-KR", {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        }).format(value);
                    },
                    number_format2: function (value) {
                        return new Intl.NumberFormat("ko-KR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }).format(value);
                    },
                },
            });
        </script>
         <style>
            .btn {
                color: #fff;
                background: #365a92;/*#38383a*/
                padding:8px 0;
            }
            .btn:hover {
                background: #2e456a;/*4f4f52*/
                color:#eee;
            }
            .pop_layer {
                position: fixed;
                width: 370px;
                height: auto;
                z-index: 1100;
            }

            .pop_layer .pop_container {
                position: relative;
            }

            .pop_layer .pop_container .pop_top {
                height: 50px;
                padding: 0 21px;
                overflow: hidden;
                border-radius: 10px 10px 0 0;
                background: #1c355b; /*#ffcc00;*/
            }

            .pop_layer .pop_container .pop_top .tit {
                line-height: 50px;
                font-size: 20px;
                font-weight: 600;
            }

            .pop_layer .pop_container .pop_con {
                max-height: 530px;
                overflow-y: auto;
            }

            .pop_layer .pop_container .pop_con .txt {
                text-align: center;
                font-size: 14px;
                line-height: 30px;
                font-weight: 600;
                padding: 40px;
            }

            .pop_layer .pop_container .pop_con .txt span {
                font-size: 14px;
                vertical-align: top;
            }

            .pop_layer .pop_container .pop_con .txt .underline {
                text-decoration: underline;
            }

            .pop_layer .pop_container .pop_con p {
                /* padding: 0 10px; */
            }

            .pop_layer .pop_container .pop_con p:first-child {
                /* padding-top: 20px; */
            }

            .pop_layer .pop_container .pop_con p:last-child {
                /* padding-bottom: 20px; */
            }

            .pop_layer .pop_container .pop_con img {
                width: 100%;
            }

            .pop_layer .pop_container .pop_close {
                position: absolute;
                top: 18px;
                right: 21px;
                width: 10px;
                height: 10px;
                background: url(/images/common/pop_close.png) no-repeat left top;
            }

            .pop_layer .check {
                text-align: right;
                margin: 0 0;
                padding: 5px 10px;
            }

            .pop_layer .btn_wrap {
                margin-top: 0;
                overflow: hidden;
                border-radius: 0 0 10px 10px;
            }

            .pop_layer .btn_wrap .btn {
                width: 50%;
                border-radius: 0;
                margin-left: 0;
            }

            .pop_layer .btn_wrap button:first-child {
                float: left;
            }

            .pop_layer .btn_wrap button:last-child {
                float: right;
            }

            #layer1, #layer3, #layer4, #layer5, #layer6, #layer7{
                top: 50%;
                left: 50%;
                color:white;
                z-index: 990;
            }

            <?php if( count($boards) == 1 ) :?>
                #layer4
                {
                    margin-left: -185px;
                    margin-top: -280px;
                }
                
                @media screen and (max-width: 800px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php elseif( count($boards) == 2 ) :?>
                #layer4
                {
                    margin-left: -375px;
                    margin-top: -280px;
                    z-index: 999;
                }
                #layer5
                {
                    margin-left: 5px;
                    margin-top: -280px;
                    z-index: 998;
                }
                @media screen and (max-width: 800px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php elseif( count($boards) == 3 ) :?>
                #layer4
                {
                    margin-left: -560px;
                    margin-top: -280px;
                    z-index: 999;
                }
                #layer5
                {
                    margin-left: -185px;
                    margin-top: -280px;
                    z-index: 998;
                }
                #layer6
                {
                    margin-left: 190px;
                    margin-top: -280px;
                    z-index: 997;
                }
                
                @media screen and (max-width: 1120px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php else :?>
                #layer4
                {
                    margin-left: -375px;
                    margin-top: -280px;
                    z-index: 999;
                }
                #layer5
                {
                    margin-left: 5px;
                    margin-top: -280px;
                    z-index: 998;
                }
                #layer6
                {
                    margin-left: -755px;
                    margin-top: -280px;
                    z-index: 997;
                }
                #layer7
                {
                    margin-left: 385px;
                    margin-top: -280px;
                    z-index: 996;
                }
                #layer1
                {
                    margin-left: -755px; /*-610px */
                    margin-top: -280px; /*-320px;*/
                    z-index: 995;
                }
                #layer3
                {
                    margin-left: 385px; /*210px;*/
                    margin-top: -280px;  /*-320px;*/
                    z-index: 994;
                }
            
                @media screen and (max-width: 1500px){
                    #layer4, #layer7  {
                        margin-left: -560px;
                        margin-top: -280px;
                    }
                    #layer5, #layer1  {
                        margin-left: -185px;
                        margin-top: -280px;
                    }
                    #layer6, #layer3  {
                        margin-left: 190px;
                        margin-top: -280px;
                    }
                }

                @media screen and (max-width: 1120px){
                    #layer1, #layer4, #layer6  {
                        margin-left: -375px;
                        margin-top: -280px;
                    }
                    #layer3, #layer5, #layer7  {
                        margin-left: 5px;
                        margin-top: -280px;
                    }
                }
                
                @media screen and (max-width: 800px){
                    #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                        margin-left: -185px;
                    }
                }
            <?php endif ?>

            @media screen and (max-height: 700px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -260px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 480px;
                    padding-bottom:35px;
                    border-radius:0 0 0 10px;
                }
                .pop_layer .btn_wrap{
                    margin-top:-35px;
                    opacity: 0.99;
                }
                .btn {
                    background: rgba(54, 90, 146, 0.8);
                }
            }
            
            @media screen and (max-height: 650px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -235px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 440px;
                }
            }

            @media screen and (max-height: 600px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -215px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 400px;
                }
            }

            @media screen and (max-height: 550px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -190px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 350px;
                }
            }
            
            @media screen and (max-height: 500px){
                #layer1, #layer3, #layer4, #layer5, #layer6, #layer7  {
                    margin-top: -165px;
                }
                .pop_layer .pop_container .pop_con {
                    max-height: 300px;
                }
            }
            h1, h2, h3, h4, h5, h6{
                color:white;
                line-height:0.2;
                margin-bottom:10px;
                margin-top:10px;
            }
            .pop_layer .pop_container .pop_con {
                background: #333;
            }

        </style>

    <?php if( count($boards) > 4 ) :?>

        <div id="layer1" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[4]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[4]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[4]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer1">하루안보이기</button>
                <button type="button" class="btn btn_red pop_close">닫기</button>
            </div>
        </div>
    <?php endif ?>
        
    <?php if( count($boards) > 5 ) :?>

        <div id="layer3" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[5]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[5]->notice_color?>">
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[5]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer3">하루안보이기</button>
                <button type="button" class="btn btn_red pop_close">닫기</button>
            </div>
        </div>
    <?php endif ?>

	<?php if( count($boards) > 0 ) :?>
        
        <div id="layer4" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[0]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[0]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[0]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer4">하루안보이기</button>
                <button type="button" class="btn btn_red pop_close">닫기</button>
            </div>
        </div>

    <?php endif ?>

    
	<?php if( count($boards) > 1 ) :?>
        
        <div id="layer5" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[1]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[1]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[1]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer5">하루안보이기</button>
                <button type="button" class="btn btn_red pop_close">닫기</button>
            </div>
        </div>

    <?php endif ?>

	<?php if( count($boards) > 2 ) :?>
        
        <div id="layer6" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[2]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[2]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[2]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer6">하루안보이기</button>
                <button type="button" class="btn btn_red pop_close">닫기</button>
            </div>
        </div>

    <?php endif ?>

	<?php if( count($boards) > 3 ) :?>
        
        <div id="layer7" class="pop_layer" style="display:none;">
            <div class="pop_container">
                <div class="pop_top">
                    <p class="tit"><?=$boards[3]->notice_title?></p>
                </div>
                <div class="pop_con" style="background:<?=$boards[3]->notice_color?>">
                    <!-- white-space: pre-wrap;  -->
                    <div style="text-align:center; font-size:13px; line-height:30px;"><?=$boards[3]->notice_content?>
                    </div>
                </div>
                <button type="button" class="pop_close"><span class="ir_wa"></span></button>
            </div>
            <div class="btn_wrap">
                <button type="button" class="btn" id="btn_close_oneday_layer7">하루안보이기</button>
                <button type="button" class="btn btn_red pop_close">닫기</button>
            </div>
        </div>

    <?php endif ?>
    </body>
</html>
