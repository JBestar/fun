
function getGameName(game) {
    var sGame = "";
    switch (game) {
        case 1:
            sGame = "파워볼";
            break;
        case 2:
            sGame = "파워사다리";
            break;
        case 5:
            sGame = "보글파워볼";
            break;
        case 6:
            sGame = "보글사다리";
            break;
        case 9:
            sGame = "EOS5분 파워볼";
            break; 
        case 10:
            sGame = "EOS3분 파워볼";
            break;
        case 14:
            sGame = "해피 파워볼";
            break;
        default:
            break;
    }
    return sGame;
}

function getModeName(game, mode) {
    var sMode = "";
    if (game == 1 || game == 5 || (game >= 9 && game <= 12) || game == 14 ) {
        if (mode == 1) {
            sMode = "파워볼 홀짝";
        } else if (mode == 2) {
            sMode = "파워볼 언오버";
        } else if (mode == 3) {
            sMode = "일반볼 홀짝";
        } else if (mode == 4) {
            sMode = "일반볼 언오버";
        } else if (mode >= 5 && mode <= 8) {
            sMode = "파워볼 조합";
        } else if (mode >= 9 && mode <= 12) {
            sMode = "일반볼 조합";
        } else if (mode >= 13 && mode <= 20) {
            sMode = "일반볼 + 파워볼 조합";
        } else if (mode >= 21 && mode <= 29) {
            sMode = "일반볼 대중소";
        } else if (mode == 30) {
            sMode = "파워볼 숫자";
        } else if (mode >= 31 && mode <= 38) {
            sMode = "일반볼조합 + 파워볼 홀짝";
        }
    } else if (game == 2 || game == 6) {
        if (mode >= 1 && mode <= 3) {
            sMode = "일반";
        } else if (mode >= 4 && mode <= 7) {
            sMode = "조합";
        }
    }
    return sMode;
}

function getTargetHtml(side, name) {
    html = "";
    switch (side) {
        case 1:
            html = "<span class='txt_blue'>" + name + "</span>";
            break;
        case 2:
            html = "<span class='txt_red'>" + name + "</span>";
            break;
        case 3:
            html = "<span class='txt_green'>" + name + "</span>";
            break;
        case 4:
            html = "<span class='txt_gray'>" + name + "</span>";
            break;
    }
    return html;
}

function getResultName(game, mode, result) {
    var sResult = "";
    if (game == 1 || game == 5 || (game >= 9 && game <= 12) || game == 14 ) {
        switch (parseInt(mode)) {
            case 1:
            case 3:
                sResult = result == "P" ? getTargetHtml(1, "홀") : getTargetHtml(2, "짝");
                break;
            case 2:
            case 4:
                sResult = result == "P" ? getTargetHtml(1, "언더") : getTargetHtml(2, "오버");
                break;
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
                if (result == "PP")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(1, "언더");
                else if (result == "PB")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(2, "오버");
                else if (result == "BP")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(1, "언더");
                else if (result == "BB")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(2, "오버");
                break;
            case 13:
            case 14:
            case 15:
            case 16:
                if (result == "PP")
                    sResult = getTargetHtml(1, "일 홀") + " / " + getTargetHtml(1, "파 홀");
                else if (result == "PB")
                    sResult = getTargetHtml(1, "일 홀") + " / " + getTargetHtml(2, "파 짝");
                else if (result == "BP")
                    sResult = getTargetHtml(2, "일 짝") + " / " + getTargetHtml(1, "파 홀");
                else if (result == "BB")
                    sResult = getTargetHtml(2, "일 짝") + " / " + getTargetHtml(2, "파 짝");
                break;
            case 17:
            case 18:
            case 19:
            case 20:
                if (result == "PP")
                    sResult = getTargetHtml(1, "일 언더") + " / " + getTargetHtml(1, "파 언더");
                else if (result == "PB")
                    sResult = getTargetHtml(1, "일 언더") + " / " + getTargetHtml(2, "파 오버");
                else if (result == "BP")
                    sResult = getTargetHtml(2, "일 오버") + " / " + getTargetHtml(1, "파 언더");
                else if (result == "BB")
                    sResult = getTargetHtml(2, "일 오버") + " / " + getTargetHtml(2, "파 오버");
                break;
            case 21:
            case 22:
            case 23:
            case 24:
            case 25:
            case 26:
                if (result == "PL")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(3, "대");
                else if (result == "PM")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(3, "중");
                else if (result == "PS")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(3, "소");
                else if (result == "BL")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(3, "대");
                else if (result == "BM")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(3, "중");
                else if (result == "BS")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(3, "소");
                break;
            case 27:
            case 28:
            case 29:
                if (result == "L")
                    sResult = getTargetHtml(3, "대");
                else if (result == "M")
                    sResult = getTargetHtml(3, "중");
                else if (result == "S")
                    sResult = getTargetHtml(3, "소");
                break;
            case 30:
                sResult = getTargetHtml(4, result);
                break;
            case 31:
            case 32:
            case 33:
            case 34:
            case 35:
            case 36:
            case 37:
            case 38:
                if (result == "PPP")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(1, "파 홀");
                else if (result == "PPB")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(2, "파 짝");
                else if (result == "PBP")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(1, "파 홀");
                else if (result == "PBB")
                    sResult = getTargetHtml(1, "홀") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(2, "파 짝");
                else if (result == "BPP")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(1, "파 홀");
                else if (result == "BPB")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(2, "파 짝");
                else if (result == "BBP")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(1, "파 홀");
                else if (result == "BBB")
                    sResult = getTargetHtml(2, "짝") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(2, "파 짝");
                break;
            default:
                break;
        }
    } else if (game == 2 || game == 6) {
        switch (parseInt(mode)) {
            case 1:
                sResult = result == "P" ? getTargetHtml(1, "좌") : getTargetHtml(2, "우");
                break;
            case 2:
                sResult = result == "P" ? getTargetHtml(1, "3") : getTargetHtml(2, "4");
                break;
            case 3:
                sResult = result == "P" ? getTargetHtml(1, "홀") : getTargetHtml(2, "짝");
                break;
            case 4:
            case 5:
            case 6:
            case 7:
                if (result == "PP")
                    sResult = getTargetHtml(1, "좌") + " / " + getTargetHtml(1, "3");
                else if (result == "PB")
                    sResult = getTargetHtml(1, "좌") + " / " + getTargetHtml(2, "4");
                else if (result == "BP")
                    sResult = getTargetHtml(2, "우") + " / " + getTargetHtml(1, "3");
                else if (result == "BB")
                    sResult = getTargetHtml(2, "우") + " / " + getTargetHtml(2, "4");
                break;
            default:
                break;
        }
    }
    return sResult;
}

function getTargetName(game, mode, target) {
    var sTarget = "";
    if (game == 1 || game == 5 || (game >= 9 && game <= 12) || game == 14) {
        switch (parseInt(mode)) {
            case 1:
            case 3:
                sTarget = target == "P" ? getTargetHtml(1, "홀") : getTargetHtml(2, "짝");
                break;
            case 2:
            case 4:
                sTarget = target == "P" ? getTargetHtml(1, "언더") : getTargetHtml(2, "오버");
                break;
            case 5:
            case 9:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(1, "언더");
                break;
            case 6:
            case 10:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(2, "오버");;
                break;
            case 7:
            case 11:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(1, "언더");
                break;
            case 8:
            case 12:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(2, "오버");
                break;
            case 13:
                sTarget = getTargetHtml(1, "일 홀") + " / " + getTargetHtml(1, "파 홀");
                break;
            case 14:
                sTarget = getTargetHtml(1, "일 홀") + " / " + getTargetHtml(2, "파 짝");
                break;
            case 15:
                sTarget = getTargetHtml(2, "일 짝") + " / " + getTargetHtml(1, "파 홀");
                break;
            case 16:
                sTarget = getTargetHtml(2, "일 짝") + " / " + getTargetHtml(2, "파 짝");
                break;
            case 17:
                sTarget = getTargetHtml(1, "일 언더") + " / " + getTargetHtml(1, "파 언더");
                break;
            case 18:
                sTarget = getTargetHtml(1, "일 언더") + " / " + getTargetHtml(2, "파 오버");
                break;
            case 19:
                sTarget = getTargetHtml(2, "일 오버") + " / " + getTargetHtml(1, "파 언더");
                break;
            case 20:
                sTarget = getTargetHtml(2, "일 오버") + " / " + getTargetHtml(2, "파 오버");
                break;
            case 21:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(3, "대");
                break;
            case 22:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(3, "중");
                break;
            case 23:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(3, "소");
                break;
            case 24:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(3, "대");
                break;
            case 25:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(3, "중");
                break;
            case 26:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(3, "소");
                break;
            case 27:
                sTarget = getTargetHtml(3, "대");
                break;
            case 28:
                sTarget = getTargetHtml(3, "중");
                break;
            case 29:
                sTarget = getTargetHtml(3, "소");
                break;
            case 30:
                sTarget = getTargetHtml(4, target);
                break;
            case 31:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(1, "파 홀");
                break;
            case 32:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(2, "파 짝");
                break;
            case 33:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(1, "파 홀");
                break;
            case 34:
                sTarget = getTargetHtml(1, "홀") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(2, "파 짝");
                break;
            case 35:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(1, "파 홀");
                break;
            case 36:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(1, "언더") + " / " + getTargetHtml(2, "파 짝");
                break;
            case 37:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(1, "파 홀");
                break;
            case 38:
                sTarget = getTargetHtml(2, "짝") + " / " + getTargetHtml(2, "오버") + " / " + getTargetHtml(2, "파 짝");
                break;

            default:
                break;
        }
    } else if (game == 2 || game == 6) {
        switch (parseInt(mode)) {
            case 1:
                sTarget = target == "P" ? getTargetHtml(1, "좌") : getTargetHtml(2, "우");
                break;
            case 2:
                sTarget = target == "P" ? getTargetHtml(1, "3") : getTargetHtml(2, "4");
                break;
            case 3:
                sTarget = target == "P" ? getTargetHtml(1, "홀") : getTargetHtml(2, "짝");
                break;
            case 4:
                sTarget = getTargetHtml(1, "좌") + " / " + getTargetHtml(1, "3");
                break;
            case 5:
                sTarget = getTargetHtml(1, "좌") + " / " + getTargetHtml(2, "4");
                break;
            case 6:
                sTarget = getTargetHtml(2, "우") + " / " + getTargetHtml(1, "3");
                break;
            case 7:
                sTarget = getTargetHtml(2, "우") + " / " + getTargetHtml(2, "4");
                break;
        }

    }
    return sTarget;
}

function getBetResultHtml(gameId, list) {
    var html = "";
    if (list) {
        html += '<div class="betting_history">';
        html += '<div class="inner">';
        html += '<div class="betting_history_wrap">';
        html += '<div class="betting_history_tit">';

        html += '<p class="round">회차</p>';
        html += '<p class="time">베팅시간</p>';
        html += '<div class="con">';
        html += '<p class="game">게임분류</p>';
        html += '<p class="bet">베팅내역</p>';
        html += '<p class="bet">경기결과</p>';
        html += '<p class="rate">배당률</p>';
        html += "</div>";
        html += '<p class="money">베팅금액</p>';
        html += '<p class="money">적중/손실</p>';
        html += '<p class="rst">적중여부</p>';
        if(typeof mCancelEnable !== 'undefined' && mCancelEnable)
            html += '<p class="money">베팅취소</p>';

        html += "</div>";

        html += '<div class="betting_history_con">';
        html += "<ul>";
        if (list.length == 0) {
            html += '<li class="none"><p>베팅 내역이 없습니다.</p></li>';
        } else {
            list.forEach((element) => {

                html += '<li class="checked_li" id="' + element.bet_fid + '">';
                html += '<input type="hidden" name="cbox' + element.bet_fid + '" value="' + element.bet_fid + '">';

                if (gameId == 1) {
                    html += '<p class="round">' + element.bet_round_fid.slice(0, -3) + "<em>" + element.bet_round_fid.slice(-3) + "</em></p>";
                } else {
                    html += '<p class="round">' + element.bet_round_no + "</p>";
                }

                html += '<p class="time">' + element.bet_time.slice(5) + "</p>";
                html += '<div class="con">';

                html += '<p class="game">' + getModeName(gameId, element.bet_mode) + "</p>";

                html += '<p class="bet">' + getTargetName(gameId, element.bet_mode, element.bet_target) + "</p>";
                if (element.bet_state > 1) {
                    html += '<p class="bet">' + getResultName(gameId, element.bet_mode, element.bet_result) + "</p>";
                } else {
                    html += '<p class="bet"></p>';
                }

                html += '<p class="rate">' + element.bet_ratio + "</p>";
                html += "</div>";

                html += '<p class="money">' + numberWithCommas(element.bet_money) + "</p>";
                if (element.bet_state == 3) {
                    html += '<p class="money hit">+' + element.bet_win_money + "</p>";
                    html += '<p class="rst hit">적중</p>';
                } else if (element.bet_state == 2) {
                    html += '<p class="money">-' + element.bet_money + "</p>";
                    html += '<p class="rst">미적중</p>';
                } else {
                    html += '<p class="money"></p>';
                    html += '<p class="rst">베팅</p>';
                }
                if(typeof mCancelEnable !== 'undefined' && mCancelEnable){
                    if(element.bet_round_no == mRound.round_no && mRoundState == 1)
                        html += "<button class='btn' onclick='reqBetCancel(" + element.bet_fid + ", this);'>베팅취소</button>";
                    else html += '<p class="money"></p>';
                }
                html += "</li>";
            });
        }
        html += "</ul>";
        html += "</div>";

        html += "</div>";
        html += "</div>";
        html += "</div>";

        html += '<div class="betting_history_m">';
        html += "<ul>";
        if (list.length == 0) {
            html += '<li class="none"><p>베팅 내역이 없습니다.</p></li>';
        } else {
            list.forEach((element) => {
                html += '<li class="checked_li_m" id="' + element.bet_fid + '">';
                html += '<input type="hidden" name="cbox' + element.bet_fid + '" id="box_' + element.bet_fid + '" value="' + element.bet_fid + '"/>';

                html += '<div class="betting_history_m_top">';
                html += '<p class="game">' + getGameName(gameId) + "</p>";

                if (element.bet_state == 3) {
                    html += '<p class="rst_txt hit">' + "적중" + "</p>";
                } else if (element.bet_state == 2) {
                    html += '<p class="rst_txt">' + "미적중" + "</p>";
                } else {
                    html += '<p class="rst_txt">' + "베팅" + "</p>";
                }
                html += "</div>";

                html += '<div class="betting_history_m_con">';

                html += "<div><dl><dt>회차</dt>";
                if (gameId == 1) {
                    html += "<dd>" + element.bet_round_fid.slice(0, -3) + "<em>" + element.bet_round_fid.slice(-3) + "</em></dd>";
                } else {
                    html += '<dd>' + element.bet_round_no + "</dd>";
                }
                html += "</dl></div>";

                html += "<div><dl><dt>베팅시간</dt>";
                html += "<dd>" + element.bet_time.slice(5) + "</dd>";
                html += "</dl></div>";

                html += "<div><dl><dt>게임분류</dt>";
                html += '<dd class="game">' + getModeName(gameId, element.bet_mode) + "</dd></dl>";

                html += "<dl><dt>베팅</dt>";
                html += "<dd>" + getTargetName(gameId, element.bet_mode, element.bet_target) + "</dd></dl>";
                if (element.bet_state > 1) {
                    html += "<dl><dt>경기결과</dt>";
                    html += "<dd>" + getResultName(gameId, element.bet_mode, element.bet_result) + "</dd></dl>";
                }
                html += "</div>";

                html += "<div><dl><dt>배당률</dt>";
                html += "<dd>" + element.bet_ratio + "</dd>";
                html += "</dl><dl><dt>베팅금액</dt>";
                html += "<dd>" + numberWithCommas(element.bet_money) + "</dd>";
                html += "</dl></div>";

                html += "</div>";

                html += '<div class="betting_history_m_bot">';
                html += "<dl>";
                if(typeof mCancelEnable !== 'undefined' && mCancelEnable && mRoundState == 1 && element.bet_round_no == mRound.round_no)
                    html += "<button class='btn' onclick='reqBetCancel(" + element.bet_fid + ", this);'>베팅취소</button>";
                else {
                    html += "<dt>적중/손실</dt>";
                    if (element.bet_state == 3) {
                        html += '<dd class="hit">+' + numberWithCommas(element.bet_win_money) + "</dd>";
                    } else if (element.bet_state == 2) {
                        html += "<dd>-" + numberWithCommas(element.bet_money) + "</dd>";
                    } else {
                        html += "<dd>0</dd>";
                    }
                }

                html += "</dl>";
                html += "</div>";
                html += "</li>";
            });
        }
        html += "</ul>";
        html += "</div>";
    }

    return html;
}

function setLimitAmount($iType){
    if(mConfig == null)
        return;
    let minBet = 0;
    let maxBet = 0;
    let maxWin = 0;

    switch($iType){
        case 1: 
            minBet = parseInt(mConfig.game_min_bet_money);
            maxBet = parseInt(mConfig.game_max_bet_money);
            maxWin = parseInt(mConfig.game_max_win_money);
            break;
        case 2: 
            minBet = parseInt(mConfig.game_min2_bet_money);
            maxBet = parseInt(mConfig.game_max2_bet_money);
            maxWin = parseInt(mConfig.game_max2_win_money);
            break;
        case 3: 
            minBet = parseInt(mConfig.game_min3_bet_money);
            maxBet = parseInt(mConfig.game_max3_bet_money);
            maxWin = parseInt(mConfig.game_max3_win_money);
            break;
        case 4: 
            minBet = parseInt(mConfig.game_min4_bet_money);
            maxBet = parseInt(mConfig.game_max4_bet_money);
            maxWin = parseInt(mConfig.game_max4_win_money);
            break;
        case 5: 
            minBet = parseInt(mConfig.game_min5_bet_money);
            maxBet = parseInt(mConfig.game_max5_bet_money);
            maxWin = parseInt(mConfig.game_max5_win_money);
            break;
        case 6: 
            minBet = parseInt(mConfig.game_min6_bet_money);
            maxBet = parseInt(mConfig.game_max6_bet_money);
            maxWin = parseInt(mConfig.game_max6_win_money);
            break;
        case 7: 
            minBet = parseInt(mConfig.game_min7_bet_money);
            maxBet = parseInt(mConfig.game_max7_bet_money);
            maxWin = parseInt(mConfig.game_max7_win_money);
            break;
        default : 
            minBet = parseInt(mConfig.game_min_bet_money);
            maxBet = parseInt(mConfig.game_max_bet_money);
            maxWin = parseInt(mConfig.game_max_win_money);
            break;
    }

    $("#bet_min").data("amount", minBet);
    if(minBet <= 0 ){
        $("#bet_min").text("무제한");
    }
    else $("#bet_min").text(minBet.toLocaleString() + " 원");
    $("#bet_max").data("amount", maxBet);
    if(maxBet <= 0 ){
        $("#bet_max").text("무제한");
    }
    else $("#bet_max").text(maxBet.toLocaleString() + " 원");
    
    $("#dist_max").data("amount", maxWin);
    if(maxWin <= 0 ){
        $("#dist_max").text("무제한");
    }
    else $("#dist_max").text(maxWin.toLocaleString() + " 원");

}