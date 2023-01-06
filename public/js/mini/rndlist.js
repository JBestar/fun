$(document).ready(function() {
    requestTotalPage();
});

$(function() {

    $(".search_tab a").click(function(e) {
        e.preventDefault();

        $(".search_tab a").removeClass("on");
        $(this).addClass("on");
        requestTotalPage();
    });
});

function getDate() {
    if ($(".search_tab .on").length > 0) {
        $date = $(".search_tab .on").attr("id");
        return $date.replace(/[/]/g, "-");
    }
    return "";
}

function getGameId() {
    return $(".result_list").attr("id");
}

function requestPageInfo() {
    var nPage = getActivePage();
    var jsonData = { "game": getGameId(), "date": getDate(), "count": CountPerPage, "page": nPage };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/api/page_round",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showPage(jResult.game, jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function requestTotalPage() {
    var jsonData = { "count": CountPerPage, "date": getDate(), "game": getGameId() };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: '/api/count_round',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data;
                setFirstPage();
                requestPageInfo();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function showPage(game, data) {

    if (game == "undefined" || game == null)
        return;

    var html = "";
    let nums;
    if (data) {
        html += '<div class="all_result">';
        html += '<div class="inner">';
        html += '<div class="all_result_wrap">';
        html += '<div class="all_result_tit">';
        html += '<p class="round">회차</p>';
        html += '<p class="time">경기시간</p>';
        if (game == 2 || game == 6) {
            html += '<p class="rst">좌우</p>';
            html += '<p class="rst">3/4</p>';
            html += '<p class="rst">홀짝</p>';
        } else {
            html += '<p class="win">당첨번호결과</p>';
            html += '<p class="rst_c">숫자 합</p>';
            html += '<p class="rst_c">숫자 홀/짝</p>';
            html += '<p class="rst_c">파워볼</p>';
            html += '<p class="rst_c">파워볼 홀/짝</p>';
            html += '<p class="group">대/중/소</p>';
        }

        html += '</div>';
        html += '<div class="all_result_con">';
        html += '<ul>';

        if (data.length == 0) {
            html += '<li class="none"><p>게임결과가 없습니다.</p></li>';
        } else {
            data.forEach((round) => {
                html += '<li>';
                if (game == 1)
                    html += '<p class="round">' + round.round_fid.substr(0, 4) + '<em>' + round.round_fid.substr(4) + '</em></p>';
                else html += '<p class="round">' + round.round_num + '</p>';
                html += '<p class="time">' + getRoundTime(game, round).substring(5) + '</p>';
                if (game == 2 || game == 6) {
                    if (round.round_state == 1) {
                        if (round.round_result_1 == "P")
                            html += '<p class="rst"> <span class="rst_ico blue">좌</span></p>';
                        else html += '<p class="rst"> <span class="rst_ico red">우</span></p>';
                        if (round.round_result_2 == "P")
                            html += '<p class="rst"> <span class="rst_ico blue">3</span></p>';
                        else html += '<p class="rst"> <span class="rst_ico red">4</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst"> <span class="rst_ico blue">홀</span></p>';
                        else html += '<p class="rst"> <span class="rst_ico red">짝</span></p>';
                    } else {
                        html += '<p class="rst">-</p>';
                        html += '<p class="rst">-</p>';
                        html += '<p class="rst">-</p>';
                    }
                } else {
                    if (round.round_state == 1) {
                        nums = getRoundNum(round);
                        html += '<p class="win">' + nums[0] + '</p>';
                        html += '<p class="rst_c"><span class="rst_ico gray">' + nums[1] + '</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">홀</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">짝</span></p>';

                        html += '<p class="rst_c"><span class="rst_ico gray">' + round.round_power + '</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">홀</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">짝</span></p>';

                        if (round.round_result_5 == "L")
                            html += '<p class="group"><span class="rst_ico green">대</span> (81~130)</p>';
                        else if (round.round_result_5 == "M")
                            html += '<p class="group"><span class="rst_ico green">중</span> (65~80)</p>';
                        else
                            html += '<p class="group"><span class="rst_ico green">소</span> (15~64)</p>';

                    } else {
                        html += '<p class="win">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="group">-</p>';
                    }
                }

                html += '</li>';
            });
        }
        html += '</ul></div></div></div></div>';
        html += '<div class="all_result_m">';
        html += '<div class="inner">';
        html += '<div class="all_result_wrap">';
        html += '<div class="all_result_tit">';
        if (game == 2 || game == 6) {
            html += '<div class="row">';
            html += '<p class="round">회차</p>';
            html += '<p class="win">경기시간</p>';
            html += '<p class="rst_c">결과</p>';
            html += '</div></div>';
        } else {
            html += '<div class="row">';
            html += '<p class="round">회차</p>';
            html += '<p class="win">당첨번호결과</p>';
            html += '<p class="rst_c">숫자 합</p>';
            html += ' <p class="rst_c">숫자 홀/짝</p>';
            html += '</div>';
            html += '<div class="row">';
            html += '<p class="rst_c">파워볼</p>';
            html += '<p class="rst_c">파워볼 홀/짝</p>';
            html += '<p class="group">대/중/소</p>';
            html += '</div></div>';
        }

        html += '<div class="all_result_con">';
        html += '<ul>';
        if (data.length == 0) {
            html += '<li class="none"  style="display:grid;"><p>게임결과가 없습니다.</p></li>';
        } else {
            data.forEach((round) => {
                html += '<li class="row_wrap">';
                html += '<div class="row">';
                if (game == 1)
                    html += '<p class="round">' + round.round_fid.substr(0, 4) + '<em>' + round.round_fid.substr(4) + '</em></p>';
                else html += '<p class="round">' + round.round_num + '</p>';
                if (game == 2 || game == 6) {
                    html += '<p class="rst_c">' + getRoundTime(game, round).substring(5) + '</p>';
                    html += '<p class="rst_c">';
                    if (round.round_state == 1) {
                        if (round.round_result_1 == "P")
                            html += '<span class="rst_ico blue">좌</span>';
                        else html += '<span class="rst_ico red">우</span>';
                        if (round.round_result_2 == "P")
                            html += '<span class="rst_ico blue">3</span>';
                        else html += '<span class="rst_ico red">4</span>';
                        if (round.round_result_3 == "P")
                            html += '<span class="rst_ico blue">홀</span>';
                        else html += '<span class="rst_ico red">짝</span>';
                    } else {
                        html += "-";
                    }
                    html += '</p>';
                } else {
                    if (round.round_state == 1) {
                        nums = getRoundNum(round);
                        html += '<p class="win">' + nums[0] + '</p>';
                        html += '<p class="rst_c"><span class="rst_ico gray">' + nums[1] + '</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">홀</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">짝</span></p>';

                        html += '</div><div class="row">';
                        html += '<p class="rst_c"><span class="rst_ico gray">' + round.round_power + '</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">홀</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">짝</span></p>';

                        if (round.round_result_5 == "L")
                            html += '<p class="group"><span class="rst_ico green">대</span> (81~130)</p>';
                        else if (round.round_result_5 == "M")
                            html += '<p class="group"><span class="rst_ico green">중</span> (65~80)</p>';
                        else
                            html += '<p class="group"><span class="rst_ico green">소</span> (15~64)</p>';
                        html += '</div>';

                    } else {
                        html += '<p class="win">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '</div><div class="row">';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="group">-</p>';
                        html += '</div>';

                    }
                }

                html += '</li>';
            });
        }
        html += '</ul></div></div></div></div>';

    }
    $(".result_list").html(html);
}

function getRoundNum(round) {
    var sNum = "";
    var sSum = 0;
    arrNormal = round.round_normal.split(",");
    if (arrNormal.length >= 5) {

        arrNormal.forEach((num) => {
            sNum += parseInt(num) + ",";
            sSum += parseInt(num);
        });
        sNum += round.round_power;
    } else {
        sNum = "-";
        sSum = "-";
    }
    return [sNum, sSum];
}

function getRoundTime(game, round) {
    let rt = 5;
    if (game == 5) {
        rt = 2;
    } else if (game == 6 || game == 10 || game == 12) {
        rt = 3;
    }

    let hh = parseInt(round.round_num * rt / 60);
    let mm = (round.round_num * rt) % 60;
    var dt = new Date(round.round_date + " " + hh + ":" + mm + ":0");
    if(game == 1 || game == 2)      //동행복권
        dt.setSeconds(dt.getSeconds() - 25);

    var year = dt.getFullYear().toString();
    var month = (dt.getMonth() + 101).toString().slice(-2);
    var date = (dt.getDate() + 100).toString().slice(-2);
    var hour = (dt.getHours() + 100).toString().slice(-2);
    var min = (dt.getMinutes() + 100).toString().slice(-2);
    var sec = (dt.getSeconds() + 100).toString().slice(-2);

    return year + "-" + month + "-" + date + " " + hour + ":" + min + ":" + sec;

}