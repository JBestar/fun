$(document).ready(function() {
    if(is_login()){
        checkNotice();
        startWorker();
    }

    
    $('#btn_close_oneday_layer1').click(function(e) {
        setCookie('layer1_check', true, 1);
        $('#layer1').hide();
    });

    $('#btn_close_oneday_layer3').click(function(e) {
        setCookie('layer3_check', true, 1);
        $('#layer3').hide();
    });

    $('.pop_close').on('click', function() {
        $(this).parent().parent().hide();
    });
});

var worker; 
// worker 실행
function startWorker() {
    // Worker 지원 유무 확인
    if ( !!window.Worker ) {
  
        // 실행하고 있는 워커 있으면 중지시키기
        if ( worker ) {
            stopWorker();
        }
    
        worker = new Worker( '/js/worker.js' );
        worker.postMessage( '워커 실행' );    // 워커에 메시지를 보낸다.
    
        // 메시지는 JSON구조로 직렬화 할 수 있는 값이면 사용할 수 있다. Object등
        // worker.postMessage( { name : '302chanwoo' } );
    
        // 워커로 부터 메시지를 수신한다.
        worker.onmessage = function( e ) {      
            money_check();
            
        };
    }
  
}

  

// worker 중지
function stopWorker() {

    if ( worker ) {
      worker.terminate();
      worker = null;
    }
  
}


function is_login(){
    if($("#wrapper").data("login") == "1")
        return true;
    else return false;
}

function check_login(){
    if(!is_login()){
        alert("로그인 해주세요");
        return false;
    } return true;
}


function closeTimer(){
    
    stopWorker();
}

function money_check(){
    requestEggInfo();

    setTimeout(function() {
        session_check();
    }, 3000);
}


function requestEggInfo() {

    $.ajax({
        type: "POST",
        url: "/api/egginfo",
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        },
    });

}

function session_check() {
    console.log("session_check");
    $.ajax({
        type: 'POST',
        url: '/api/check_session',
        dataType: "json",
        success: function(result) {
            // console.log(result);
            if (result.status == "success") {
                updateCurrency(result.data.money, result.data.point);
                showUnread(result.data.msg, result.data.cus);
            } else if (result.status == "logout") {
                closeTimer();

                UIkit.modal.alert("세션이 만료되었습니다. 다시 로그인하세요.", {labels: {'ok': '확인'}}).then(function () {
                    reloadPage();
                });
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });

}


function checkNotice() {
    $.ajax({
        type: 'POST',
        url: '/api/check_notice',
        dataType: "json",
        success: function(data) {
            // console.log(data);
            if (data.status == "success") {
                showNotice(data.notice_main, data.notice_urgent, data.notice_bank);
            } else if (data.status == "logout") {
                
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

function updateCurrency(money, point) {
    $("#wrapper ._has_cash").html(numberWithCommas(money));
    $("#wrapper ._has_point").html(numberWithCommas(point));
}

// 금액 , 표시
function numberWithCommas(x) {
    if (isNaN(x)) {
        return '0'
    }
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

var tmViewForm = 0;
function showUnread(msg, cus) {

    if (msg > 0) {
        $("#_btn_memo").addClass('flicker');
        if(Date.now() - tmViewForm > 60000){
            tmViewForm = Date.now(); 
            SLB_POPUP('/mypage', 'my_memo');
        }
    } else {
        $("#_btn_memo").removeClass('flicker');
    }
        
    if (cus > 0) {
        $("#_btn_qna").addClass('flicker');
        if(Date.now() - tmViewForm > 60000){
            tmViewForm = Date.now(); 
            SLB_POPUP('/mypage', 'my_qna');
        }
    } else {
        $("#_btn_qna").removeClass('flicker');
    }

}

function showNotice(main, urgent, bank) {
    if (main == 0) {
       $(".scroll_area").hide();
    } else {
       $(".scroll_area").show();
    }

    if (urgent == 0 || getCookie('layer1_check') == 'true') {
        $('#layer1').hide();
    } else {
        $('#layer1').show();
    }

    if (bank == 0 || getCookie('layer3_check') == 'true') {
        $('#layer3').hide();
    } else {
        $('#layer3').show();
    }

}

// 쿠키 사용하기
function setCookie(cookie_name, value, days) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + days);
    // 설정 일수만큼 현재시간에 만료값으로 지정

    var cookie_value = escape(value) + ((days == null) ? '' : '; expires=' + exdate.toUTCString());
    document.cookie = cookie_name + '=' + cookie_value;
}

function getCookie(cookie_name) {
    var x, y;
    var val = document.cookie.split(';');

    for (var i = 0; i < val.length; i++) {
        x = val[i].substr(0, val[i].indexOf('='));
        y = val[i].substr(val[i].indexOf('=') + 1);
        x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤 공배 제거
        if (x == cookie_name) {
            return unescape(y); // unescape로 디코딩
        }
    }
}