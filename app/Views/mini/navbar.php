
<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <body oncontextmenu="return false" ondragstart="return false" onselectstart="return false" class="theme01 slot_black">
<?php else : ?>
    <body class="theme01 slot_black">
<?php endif ?>

<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo base_url('/js/mini/navbar.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo base_url('/js/mini/navbar.js?v=1');?>"></script>
<?php endif ?>

    <div id="wrap">
        <div class="loading" style="display: none;"></div>
        
        <div class="alert_wrap basic_alert" id="basic_alert">
            <div class="alert_bot">
                <p class="question_ico" id="alert_content"></p>
                <div class="btn_wrap">
                    <a onclick="location.href='javascript:okAlert()';" class="btn btn_point02" id="basic_ok" style="cursor: pointer;">확인</a>
                    <a onclick="location.href='javascript:closeAlert()';" class="btn" style="cursor: pointer;">취소</a>
                </div>
            </div>
        </div>

        <div class="alert_wrap basic_alert" id="basic_alert2">
            <div class="alert_bot">
                <p class="list_wrap" id="alert2_content"></p>
                <div class="btn_wrap">
                    <a class="btn btn_point02" id="basic2_ok" style="cursor: pointer;">확인</a>
                    <a class="btn" id="basic2_cancel" style="cursor: pointer;">취소</a>
                </div>
            </div>
        </div>
        <div class="alert_wrap confirm_alert" id="confirm_alert" style="display: none;">
            <div class="alert_bot">
                <p class="question_ico" id="alert_content">게임 선택 후 금액을 눌러주세요</p>
                <div class="btn_wrap">
                    <a class="btn" id="confirm_ok" style="cursor: pointer;">확인</a>
                </div>
            </div>
        </div>
        <section id="container"  style="margin-top: 0px;">

            <!--//lnb -->
            <div class="content_wrap">
                <div class="subTitle_game">
                    <?php if($game_id == GAME_BOGLE_BALL) : ?>
                        <h2> 보글파워볼 <small>BOGLE Powerball</small></h2>
                    <?php elseif($game_id == GAME_BOGLE_LADDER) : ?>
                        <h2> 보글사다리 <small>BOGLE LADDER</small></h2>
                    <?php elseif($game_id == GAME_EOS5_BALL) : ?>
                        <h2> EOS5분파워볼 <small>EOS5 Powerball</small></h2>
                    <?php elseif($game_id == GAME_EOS3_BALL) : ?>
                        <h2> EOS3분파워볼 <small>EOS3 Powerball</small></h2>
                    <?php elseif($game_id == GAME_COIN5_BALL) : ?>
                        <h2> 코인5분파워볼 <small>COIN5 Powerball</small></h2>
                    <?php elseif($game_id == GAME_COIN3_BALL) : ?>
                        <h2> 코인3분파워볼 <small>COIN3 Powerball</small></h2>
                    <?php else :?>
                        <h2> 해피파워볼 <small>HAPPY Powerball</small></h2>
                    <?php endif ?>
                    <div class="user-info-part">보유머니:<span id="h_money"><?=$user_money?></span> 
                        포인트:<span id="h_point"><?=$user_point?></span>
                    </div>
                </div>
                <ul class="gamezone-menu result_menu">
                    <?php if($hpg_enable) :?>
                    <li class="dropdown">
                        <a class="<?=$gm_hpb?>" href="/mini?gm=HPB">해피게임</a>
                    </li>
                    <?php endif ?>
                    <?php if(!$bpg_deny) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_bg?>">보글게임</button>
                        <div class="dropdown-content">
                            <a class="<?=$gm_bgb?>" href="/mini?gm=BGB">보글파워볼</a>
                            <a class="<?=$gm_bgl?>" href="/mini?gm=BGL">보글사다리</a>
                        </div>
                    </li>
                    <?php endif ?>

                    <?php if($eos5_enable || $eos3_enable) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_eos?>">EOS게임</button>
                        <div class="dropdown-content">
                        <?php if($eos3_enable) :?>
                            <a class="<?=$gm_e3?>" href="/mini?gm=EOS3">EOS3분파워볼</a>
                        <?php endif ?>
                        <?php if($eos5_enable) :?>
                            <a class="<?=$gm_e5?>" href="/mini?gm=EOS5">EOS5분파워볼</a>
                        <?php endif ?>
                        </div>
                    </li>
                    <?php endif ?>

                    <?php if($coin5_enable || $coin3_enable) :?>
                    <li class="dropdown">
                        <button class="dropbtn <?=$gm_co?>">코인게임</button>
                        <div class="dropdown-content">
                        <?php if($coin3_enable) :?>
                            <a class="<?=$gm_c3?>" href="/mini?gm=COIN3">코인3분 파워볼</a>
                        <?php endif ?>
                        <?php if($coin5_enable) :?>
                            <a class="<?=$gm_c5?>" href="/mini?gm=COIN5">코인5분 파워볼</a>
                        <?php endif ?>
                        </div>
                    </li>
                    <?php endif ?>
                    
                    <li><a class="<?=$ls_rnd?>" href="/rndlist?gm=<?=$gm_ref?>" style="margin-right:3px">게임결과</a></li>
                    <li><a class="<?=$ls_bet?>" href="/betlist?gm=<?=$gm_ref?>">배팅내역</a></li>
                </ul>
                <!-- <div class="content_inner" style="display:none;">
                    <p class="inspection_none">운영시간이 아닙니다.</p>
                </div> -->
                <div class="game_list" style="" id="<?=$game_id?>">
                </div>