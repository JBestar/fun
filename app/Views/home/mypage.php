<!DOCTYPE html>
<html lang="ko" class=""  style="background-color: #1B2430;">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=no" />
        <script src="/js/vue.js"></script>
        <script src="/js/vuejs-paginate.js"></script>

        <link rel="stylesheet" type="text/css" href="/js/semantic-ui/semantic.css" />

        <link rel="stylesheet" href="/css/jquery-ui.css?ver=1" />
        <link rel="stylesheet" href="/css/devel.css?v=<?=time()?>" />

        <!-- JS FILES -->
        <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
        <script type="text/javascript" src="/js/jquery-ui.js"></script>
        <script type="text/javascript" src="/js/jquery-form/jquery.form.js"></script>
        <script type="text/javascript" src="/js/jquery-form/jquery.validate.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>

        <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/uikit@latest/dist/css/uikit.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit-icons.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="/js/uikit/uikit.min.css" />
        <script src="/js/uikit/uikit.min.js"></script>
        <script src="/js/uikit/uikit-icons.min.js"></script>

        <script type="text/javascript" src="/js/jquery-form/jquery.form.js"></script>
        <script type="text/javascript" src="/js/jquery-form/jquery.validate.js"></script>
        <!--semantic ui-->
        <!--ui.table-->
        <script src="/js/semantic-ui/semantic.js"></script>
        <script>
            Vue.component("paginate", VuejsPaginate);
        </script>

        <style>
                        
            @media screen and (min-width:680px) { 
                ::-webkit-scrollbar {width:10px; height:3px; }
                ::-webkit-scrollbar-track {background:#1e1e1e; border-radius:2px 2px 0 0; }
                ::-webkit-scrollbar-thumb {background:#383838; border-radius:2px; }
                ::-webkit-scrollbar-thumb:hover {background:#383838; }
            }
            /* modal header semantic -> uikir first*/
            h1:last-child,
            h2:last-child,
            h3:last-child,
            h4:last-child,
            h5:last-child {
                margin-top: 0px;
            }

            .uk-modal-header {
                background: steelblue;
            }

            .uk-modal-title {
                color: white;
            }

            .uk-close {
                color: white;
            }

            .ui.table {
                font-size: 0.85em;
            }

            .ui.tabular.menu .item.active {
                background: steelblue;
                color: white;
                font-weight: bold;
            }
            .ui.tab .inline.field .ui.button {
                margin-left:10px;
            }
            .inline.field span {
                color:#aaa;
                margin-right:10px;
            }

            .red {
                color: #ff0000;
            }

            .blue {
                color: #0000ff;
            }

            .green {
                color: #00ff00;
            }

            .yellow {
                color: #ffff00;
            }

            .loading {
                background: #1B2430;
            }
            .ui.table.no_border thead th, .ui.table.no_border th, .ui.table.no_border td{
                border:none;
            }
            .ui.celled.table tr th:first-child, .ui.celled.table tr td:first-child{
                border-left: 1px solid #064663;
            }
            .ui.form input[type="date"]{
                color:#eeeeee;
                background:#24425b;
            }
            .ui.form input[type="text"], .ui.form input[type="password"], .ui.form input[type="number"]{
                color:#eeeeee;
                background:#24425b;
            }
            .ui.message, .ui.form textarea{
                color:white;
                background:#24425b;
                font-size: 1.1em;
            }
            .ui.form .inline.field .input{
                width:calc(100% - 80px);
            }
            #dashboard .ui.table thead th{
                background:#24425b;
            }

        </style>
        <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
            <link rel="stylesheet" href="/css/darkmode.css?v=3" />
        <?php else : ?>
            <link rel="stylesheet" href="/css/darkmode.css?ver=<?=time()?>" />
        <?php endif ?>
        <script src="/js/darkmode.js"></script>
    </head>
    <body style="">
        <div id="dashboard" class="ui loading segment" style="margin: 0px; ">
            <div class="ui message inverted"></div>
            <div class="ui grid top attached tabular menu grey">
                <a data-tab="my_info" class="item ">회원정보</a> 
                <!-- <a data-tab="my_cashbook" class="item">거래</a> 
                <a data-tab="my_betting" class="item">베팅</a>  -->
                <a data-tab="my_charge" class="item">입금내역</a>
                <a data-tab="my_exchange" class="item">출금내역</a> 
                <a data-tab="my_memo" class="item">&nbsp;&nbsp;&nbsp;&nbsp;쪽지&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <a data-tab="my_qna" class="item">고객센터</a>
                <a data-tab="notice" class="item">공지사항</a>
            </div>
            
            <div data-tab="my_info" class="ui tab segment ">
                <table class="ui celled table no_border">
                    <tbody>
                        <tr>
                            <td class="collapsing">회원아이디 ( 닉네임 )</td>
                            <td>
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="ui teal label">
                                            {{ myInfo.user_id }}
                                            <div class="ui detail">( {{ myInfo.user_name }} )</div>
                                        </div>
                                    </div>
                                    <div class="ten wide column">
                                        <div id="btnChangePwd"  uk-toggle="target: #change_pwd" tabindex="0" aria-expanded="false"  class="ui tiny orange labeled icon button">
                                            <i class="key icon"></i> <span class="hideOnMobile">비번변경</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing">보유머니</td>
                            <td>
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="ui teal basic label">
                                            {{ myInfo.user_money }}
                                            <div class="detail">보유머니</div>
                                        </div>
                                    </div>
                                    <div class="ten wide column">
                                        <div id="btnRequestCharge" uk-toggle="target: #request_charge" tabindex="0" aria-expanded="false" class="ui tiny blue labeled icon button">
                                            <i class="cloud download icon"></i> <span class="hideOnMobile">입금</span>
                                        </div>
                                        <div id="btnRequestExchange" uk-toggle="target: #request_exchange" tabindex="0" aria-expanded="false" class="ui tiny green labeled icon button">
                                            <i class="cloud upload icon"></i> <span class="hideOnMobile">출금</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing">포인트</td>
                            <td>
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="ui teal basic label">
                                            {{ myInfo.user_point }}
                                            <div class="detail">포인트</div>
                                        </div>
                                    </div>
                                    <div class="ten wide column">
                                        <!-- <div id="btnRequestCash" uk-toggle="target: #request_cash" tabindex="0" aria-expanded="false" class="ui tiny yellow labeled icon button"> -->
                                        <div id="btnRequestCash" tabindex="0" class="ui tiny yellow labeled icon button">
                                            <i class="refresh icon"></i> <span class="hideOnMobile">머니로 전환</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing">추천인</td>
                            <td>
                                {{ myInfo.user_emp }}
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing">휴대폰</td>
                            <td>{{ myInfo.user_phone }}</td>
                        </tr>
                        <tr>
                            <td class="collapsing">계좌정보</td>
                            <td>
                                <div class="ui divided selection list">
                                    <span class="" style="margin-right:20px;">
                                        <div class="ui horizontal basic label"> 계좌주 </div>
                                        {{ myInfo.user_bank_own }}
                                    </span>
                                    <span class="" style="margin-right:20px;">
                                        <div class="ui horizontal basic label">계좌번호</div>
                                        {{ myInfo.user_bank_num }}
                                    </span>
                                    <span class="" style="margin-right:20px;">
                                        <div class="ui horizontal basic label">계좌은행</div>
                                        {{ myInfo.user_bank_name }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing">가입일</td>
                            <td>{{ myInfo.user_join_at }}</td>
                        </tr>
                        <tr>
                            <td class="collapsing">마지막로그인</td>
                            <td>
                                <div class="ui divided selection list">
                                    <span class="">
                                        {{ myInfo.user_login_last }}
                                        <div class="ui horizontal basic label"  style="margin-left:20px;"> IP : {{ myInfo.user_ip_last }} </div>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- <div data-tab="my_cashbook" id="my_cashbook" class="ui tab segment">
                <div class="ui form">
                    <div class="fields">
                        <div class="field">
                            <label>날짜</label>
                            <div class="inline field">
                                <input type="date" v-model="start.cash"/> <span>~</span> <input type="date" v-model="end.cash"/> 
                                <button class="ui blue button">검색</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="ui compact line table">
                    <thead>
                        <tr>
                            <th>구분</th>
                            <th>이전금액</th>
                            <th>금액</th>
                            <th>이후금액</th>
                            <th>일시</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in cashList">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        :page-count="totalPageCount.cash"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationCash"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_betting" id="my_betting" class="ui tab segment">
                <div class="ui form">
                    <div class="fields">
                        <div class="field">
                            <label>날짜</label>
                            <div class="inline field">
                                <input type="date" v-model="start.betting"/> <span>~</span> <input type="date" v-model="end.betting"/> 
                                <button class="ui blue button">검색</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="ui line table">
                    <thead>
                        <tr>
                            <th>게임</th>
                            <th>벳</th>
                            <th>결과</th>
                            <th>결과일시</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in bettingList">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        :page-count="totalPageCount.betting"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationBetting"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div> -->
            <div data-tab="my_charge" id="my_charge" class="ui tab segment">
                <div class="ui form">
                    <div class="fields">
                        <div class="field">
                            <div class="inline field">
                                <div class="ui mini icon input">
                                    <input type="date" v-model="start.charge"/> 
                                </div>
                                <span>~</span> 
                                <div class="ui mini icon input">
                                    <input type="date" v-model="end.charge"/> 
                                </div>
                                <button class="ui tiny blue button" v-on:click="getMyChargeList">검색</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="ui line table">
                    <thead>
                        <tr>
                            <th>구분</th>
                            <th>입금금액</th>
                            <th>신청일시</th>
                            <th>입금성명</th>
                            <th>현재상태</th>
                            <th>승인일시</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in chargeList">
                            <td> {{ strChargeType(item.charge_action_state) }} </td>
                            <td> {{ item.charge_money }} </td>
                            <td> {{ item.charge_time_require }} </td>
                            <td> {{ item.charge_mb_realname }} </td>
                            <td v-html="strChState(item.charge_action_state)"> </td>
                            <td> {{ item.charge_time_process }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.charge"
                        :page-count="totalPageCount.charge"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationCharge"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_exchange" id="my_exchange" class="ui tab segment">
                <div class="ui form">
                    <div class="fields">
                        <div class="field">
                            <!-- <label>신청일</label> -->
                            <div class="inline field">
                                <div class="ui mini icon input">
                                    <input type="date" v-model="start.exchange"/> 
                                </div>
                                <span>~</span> 
                                <div class="ui mini icon input">
                                    <input type="date" v-model="end.exchange"/> 
                                </div>
                                <button class="ui tiny blue button" v-on:click="getMyExchangeList">검색</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="ui line table">
                    <thead>
                        <tr>
                            <th>구분</th>
                            <th>출금금액</th>
                            <th>신청일시</th>
                            <th>계좌</th>
                            <th>현재상태</th>
                            <th>승인일시</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in exchangeList">
                            <td> {{ strExchangeType(item.exchange_action_state) }} </td>
                            <td> {{ item.exchange_money }} </td>
                            <td> {{ item.exchange_time_require }} </td>
                            <td> {{ item.exchange_bank_name }} : {{ item.exchange_bank_account }} : {{ item.exchange_bank_serial }} </td>
                            <td v-html="strChState(item.exchange_action_state)"> </td>
                            <td> {{ item.exchange_time_process }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.exchange"
                        :page-count="totalPageCount.exchange"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationExchange"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_login" id="my_login" class="ui tab segment"></div>
            <div data-tab="my_memo" id="my_memo" class="ui tab segment">
                <div class="ui form">
                    <div class="inline field">
                        <div class="ui mini icon input">
                            <input type="date" v-model="start.memo"/> 
                        </div>
                        <span>~</span> 
                        <div class="ui mini icon input">
                            <input type="date" v-model="end.memo"/> 
                        </div>
                        <button class="ui tiny blue button" v-on:click="getMyMemoList">검색</button>
                        <button class="ui tiny red right floated button" onclick="deleteMemo(0)"> 전체 삭제</button>
                        <button class="ui tiny green right floated button" onclick="readMemo(0)"> 전체 읽기</button>
                    </div>
                </div>
                <table class="ui unstackable accordion celled table">
                    <thead>
                        <tr>
                            <th>발송인</th>
                            <th>제목</th>
                            <th>보낸일시</th>
                            <th>확인</th>
                            <th>삭제</th>
                        </tr>
                    </thead>
                    <tbody v-for="item in memoList">
                        <tr class="ui title" v-on:click="viewMemo(item.notice_fid, item.notice_read_count)">
                            <td><span>관리자</span></td>
                            <td><span>{{ item.notice_title }}</span> <i class="ui dropdown icon"></i></td>
                            <td><span>{{ item.notice_time_create }}</span></td>
                            <td v-html="strMsgCheck(item.notice_read_count)"> </td>
                            <td><div class="ui orange horizontal label" v-on:click="deleteMemo(item.notice_fid)">삭제</div></td>
                        </tr>
                        <tr>
                            <td colspan="100%" class="full-width transition hidden">
                                <div class="ui message">
                                    <div class="header">{{ item.notice_title }}</div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_content"></p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.memo"
                        :page-count="totalPageCount.memo"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationMemo"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_qna" id="my_qna" class="ui tab segment">
                <div class="ui form">
                    <div class="inline field">
                        <div class="ui mini icon input">
                            <input type="date" v-model="start.qna"/>
                        </div>
                        <span>~</span> 
                        <div class="ui mini icon input">
                            <input type="date" v-model="end.qna"/> 
                        </div>
                        <button class="ui tiny blue button" v-on:click="getMyQnaList" style="margin-bottom:10px" >검색</button>
                        <button class="ui tiny red right floated button" onclick="deleteCus(0)"><i class="ui times circle icon"></i> 전체 삭제</button>
                        <button uk-toggle="target:#qnaWriteModal" class="ui tiny blue right floated button" aria-expanded="false"><i class="pencil alternate icon"></i> 1:1 문의</button>
                        <button class="ui tiny green right floated button" onclick="requestAccount()"><i class="ui question circle icon"></i> 계좌 문의</button>
                    </div>
                </div>
                <table class="ui unstackable accordion celled table">
                    <thead>
                        <tr>
                            <th>작성자</th>
                            <th>문의제목</th>
                            <th>문의일시</th>
                            <th>답변</th>
                            <th>삭제</th>
                        </tr>
                    </thead>
                    <tbody v-for="item in qnaList">
                        <tr class="ui title" >
                            <td><span>{{ item.notice_mb_uid }}</span></td>
                            <td><span>{{ item.notice_title }}</span> <i class="ui dropdown icon"></i></td>
                            <td><span>{{ item.notice_time_create }}</span></td>
                            <td v-html="strQnaCheck(item.notice_state_active)"> </td>
                            <td><div class="ui orange horizontal label" v-on:click="deleteCus(item.notice_fid)">삭제</div></td>
                        </tr>
                        <tr>
                            <td colspan="100%" class="full-width transition hidden">
                                <div class="ui message">
                                    <div class="header">문의내용</div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_content"></p>
                                    <div class="header">답변내용</div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_answer"></p>
                                    
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.qna"
                        :page-count="totalPageCount.qna"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationQna"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="notice" id="notice" class="ui tab segment">
                <div class="ui form">
                    <div class="fields">
                        <div class="field">
                            <div class="inline field">
                                <div class="ui mini icon input">
                                    <input type="date" v-model="start.notice"/> 
                                </div>
                                <span>~</span> 
                                <div class="ui mini icon input">
                                    <input type="date" v-model="end.notice"/> 
                                </div>
                                <button class="ui tiny blue button" v-on:click="getNoticeList">검색</button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="ui unstackable accordion celled table">
                    <thead>
                        <tr>
                            <th>공지</th>
                            <th>작성자</th>
                            <th>제목</th>
                        </tr>
                    </thead>
                    <tbody v-for="item in noticeList">
                        <tr class="ui title" >
                            <td><span>[공지]</span></td>
                            <td><span>관리자</span></td>
                            <td><span>{{ item.notice_title }}</span> <i class="ui dropdown icon"></i></td>
                        </tr>
                        <tr>
                            <td colspan="100%" class="full-width transition hidden">
                                <div class="ui message">
                                    <div class="header">{{ item.notice_title }}</div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_content"></p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.notice"
                        :page-count="totalPageCount.notice"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationNotice"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
                    
            </div>
            <div id="qnaWriteModal" uk-modal="" class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="qnaForm" id="qnaForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title">1:1 문의하기</h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="field required"><label>문의제목</label> <input type="text" name="title" placeholder="문의제목" /></div>
                            <div class="field required"><label>문의하실 내용</label> <textarea name="contents" rows="5" placeholder="문의내용" class="ui-textarea"></textarea></div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">문의하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $(".menu .item").tab(); //탭메뉴 활성화
                var tab = "<?=$tab?>";
                if (tab != "") {
                    $(".menu .item").tab("change tab", tab);
                    if (tab == "charge") {
                        UIkit.modal("#request_charge").show();
                    } else if (tab == "withdraw") {
                        UIkit.modal("#request_exchange").show();
                    } else if (tab == "my_qna") {
                        objDashBoard.getMyQnaList();
                    } else if (tab == "notice") {
                        objDashBoard.getNoticeList();
                    } else if (tab == "my_memo") {
                        objDashBoard.getMyMemoList();
                    } 
                }

                $(".menu .item").on("click", function (e) {
                    var tab = $(this).data('tab');

                    if(objDashBoard.unreadMemo > 0){
                        tab = 'my_memo';
                        alert("미확인 쪽지가 있습니다.");
                        $(".menu .item").tab("change tab", tab);
                    }
                    // console.log(tab);
                    // var elem = $(".menu .item.active").tab();
                    if (tab == "my_info") {
                        objDashBoard.getMyInfo();
                    } else if (tab == "my_charge") {
                        objDashBoard.getMyChargeList();
                    } else if (tab == "my_exchange") {
                        objDashBoard.getMyExchangeList();
                    } else if (tab == "my_qna") {
                        objDashBoard.getMyQnaList();
                    } else if (tab == "notice") {
                        objDashBoard.getNoticeList();
                    } else if (tab == "my_memo") {
                        objDashBoard.getMyMemoList();
                    } 
                });

                $('input[type="number"]').on("keypress", function (e) {
                    if (e.keyCode < 48 || e.keyCode > 57) {
                        alert("숫자만 입력해주세요");
                        return false;
                    }
                });

                $(".ui.accordion").accordion({
                    closeNested: false,
                    exclusive: true,
                    onOpen: function () {},
                    selector: {
                        trigger: ".title",
                    },
                });

                // validate rule check
                var validationRules = {
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
                                prompt: "입금하실분의명칭을 입력해주세요",
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
                    title: {
                        identifier: "title",
                        rules: [
                            {
                                type: "empty",
                                prompt: "문의제목을 입력해주세요",
                            },
                        ],
                    },
                    contents: {
                        identifier: "contents",
                        rules: [
                            {
                                type: "empty",
                                prompt: "문의하실 내용을 입력해주세요",
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

                $(".ui.form").form({
                    fields: validationRules,
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
                        if (response.status == "success") {
                            UIkit.modal.alert("정상적으로 입금요청이 접수 되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                                // location.reload();
                            });
                        } else if (response.status == "fail") {
                            alert(response.msg);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
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
                        if (response.status == "success") {
                            UIkit.modal.alert("정상적으로 출금요청이 접수 되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                                objDashBoard.getMyInfo();
                                //location.reload();
                            });
                        } else if (response.status == "fail") {
                            alert(response.msg);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
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

                $("#btnRequestCash").on("click", function(e) {

                    UIkit.modal.confirm("포인트를 머니로 전환하시겠습니까?", {labels: {'ok': '확인', 'cancel': '취소'}}).then(
                        function () {
                            $.ajax({
                                dataType: "json",
                                type: "POST",
                                url: "/api/change_point",
                                // data: $(this).serialize(),
                                success: function (response) {
                                    if (response.status == "success") {
                                        UIkit.modal.alert("머니로 전환되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                                            objDashBoard.getMyInfo();
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
                   
                });

                $("#qnaForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/write_customer",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#qnaForm").valid();
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            UIkit.modal.alert("문의가 접수되었습니다.", {labels: {'ok': '확인'}}).then(function () {
                                objDashBoard.getMyQnaList();
                                //location.reload();
                            });
                        } else {
                            alert(response.msg);
                        }
                    },
                });
            });

            function requestAccount() {

                let title = "[빠른문의] 입금계좌요청";
                let content = "빠른문의 : 입금계좌요청";
                if (confirm(title + " \n\n를 보내시겠습니까?") == false) return false;

                $.post(
                    "/api/request_account3",
                    {
                        title: title,
                        content: content,
                    },
                    function (response) {
                        if (response.status == "success") {
                            UIkit.modal.alert("계좌해답이 도착하였습니다.", {labels: {'ok': '확인'}}).then(function () {
                                objDashBoard.getMyQnaList();
                            });
                        } else {
                            alert(response.message);
                        }
                    },
                    "json"
                );
            }
                  
            function readMemo(idx) {

                UIkit.modal.confirm("전체 읽기를 하시겠습니까?", {labels: {'ok': '확인', 'cancel': '취소'}}).then(
                    function () {
                        $.post(
                            "/api/check_message",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyMemoList();
                                } else {
                                    alert(response.msg);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }
            function deleteMemo(idx) {
                if(idx==0 && objDashBoard.unreadMemo > 0){
                    alert("미확인 쪽지가 있습니다.\n확인후 삭제해주세요.");
                    return;
                }

                let msg = "삭제하시겠습니까?";
                if(idx == 0)
                    msg = "전체 삭제를 하시겠습니까?";

                UIkit.modal.confirm(msg, {labels: {'ok': '확인', 'cancel': '취소'}}).then(
                    function () {
                        $.post(
                            "/api/delete_message",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyMemoList();
                                } else {
                                    alert(response.msg);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }

            function deleteCus(idx) {

                let msg = "삭제하시겠습니까?";
                if(idx == 0)
                    msg = "전체 삭제를 하시겠습니까?";

                UIkit.modal.confirm(msg, {labels: {'ok': '확인', 'cancel': '취소'}}).then(
                    function () {
                        $.post(
                            "/api/delete_customer",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyQnaList();
                                } else {
                                    alert(response.msg);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }
        </script>

        <script>
            var objDashBoard = new Vue({
                el: "#dashboard",
                data: {
                    myInfo: [],
                    inSafe: 0,
                    unreadMemo: 0,
                    chargeList: [],
                    exchangeList: [],
                    bettingList: [],
                    cashList: [],
                    pointList: [],
                    memoList: [],
                    qnaList: [],
                    noticeList: [],
                    eventList: [],
                    favoriteQuestionList: [],
                    countAll: {
                        charge: 0,
                        exchange: 0,
                        betting: 0,
                        cash: 0,
                        point: 0,
                        memo: 0,
                        qna: 0,
                        notice: 0,
                        event: 0,
                    },
                    curPage: {
                        charge: 1,
                        exchange: 1,
                        betting: 1,
                        cash: 1,
                        point: 1,
                        memo: 1,
                        qna: 1,
                        notice: 1,
                        event: 1,
                    },
                    totalPageCount: {
                        charge: 1,
                        exchange: 1,
                        betting: 1,
                        cash: 1,
                        point: 1,
                        memo: 1,
                        qna: 1,
                        notice: 1,
                        event: 1,
                    },
                    rowCount: 5,
                    start: {
                        charge: "<?=$start_at?>",
                        exchange: "<?=$start_at?>",
                        betting: "<?=$start_at?>",
                        cash: "<?=$start_at?>",
                        point: "<?=$start_at?>",
                        memo: "<?=$start_at?>",
                        qna: "<?=$start_at?>",
                        notice: "<?=$start_at?>",
                        event: "<?=$start_at?>",
                    },
                    end: {
                        charge: "<?=$end_at?>",
                        exchange: "<?=$end_at?>",
                        betting: "<?=$end_at?>",
                        cash: "<?=$end_at?>",
                        point: "<?=$end_at?>",
                        memo: "<?=$end_at?>",
                        qna: "<?=$end_at?>",
                        notice: "<?=$end_at?>",
                        event: "<?=$end_at?>",
                    },
                },
                methods: {
                    paginationCharge: function (pageNum) {
                        this.curPage.charge = pageNum;
                        this.getMyChargeList();
                    },
                    paginationExchange: function (pageNum) {
                        this.curPage.exchange = pageNum;
                        this.getMyExchangeList();
                    },
                    paginationBetting: function (pageNum) {
                        this.curPage.betting = pageNum;
                        this.getBettingList();
                    },
                    paginationCash: function (pageNum) {
                        this.curPage.betting = pageNum;
                        this.getMyCashList();
                    },
                    paginationPoint: function (pageNum) {
                        this.curPage.betting = pageNum;
                        this.getMyPointList();
                    },
                    paginationMemo: function (pageNum) {
                        this.curPage.memo = pageNum;
                        this.getMyMemoList();
                    },
                    paginationQna: function (pageNum) {
                        this.curPage.qna = pageNum;
                        this.getMyQnaList();
                    },
                    paginationNotice: function (pageNum) {
                        this.curPage.notice = pageNum;
                        this.getNoticeList();
                    },
                    paginationEvent: function (pageNum) {
                        this.curPage.event = pageNum;
                        this.getEventList();
                    },
                    getMyInfo: function () {
                        $.get(
                            "/api/myinfo",
                            function (response) {
                                $("#dashboard").removeClass('loading');
                                if (response.status == "success") {
                                    objDashBoard.myInfo = response.data;

                                    $("#dashboard .inverted").text(`${ objDashBoard.myInfo.user_id } (${ objDashBoard.myInfo.user_name }) 님 반갑습니다.`);

                                } 
                            },
                            "json"
                        );
                    },
                    getMyChargeList: function () {
                        $.get(
                            "/api/page_charge",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.charge,
                                start_at: this.start.charge + " 00:00:00",
                                end_at: this.end.charge + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.chargeList = response.rows;
                                    objDashBoard.countAll.charge = response.totalRows;
                                    objDashBoard.totalPageCount.charge = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                } else {
                                    // alert(response.message);
                                }
                            },
                            "json"
                        );
                    },
                    getMyExchangeList: function () {
                        $.get(
                            "/api/page_exchange",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.exchange,
                                start_at: this.start.exchange + " 00:00:00",
                                end_at: this.end.exchange + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.exchangeList = response.rows;
                                    objDashBoard.countAll.exchange = response.totalRows;
                                    objDashBoard.totalPageCount.exchange = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                } else {
                                    // alert(response.message);
                                }
                            },
                            "json"
                        );

                        // $.ajax({

                        //     dataType: "json",
                        //     url: "/api/page_exchange",
                        //     type: "POST",
                        //     data: {
                        //         rowCount: this.rowCount,
                        //         page: this.curPage.exchange,
                        //         start_at: this.start.exchange + " 00:00:00",
                        //         end_at: this.end.exchange + " 23:59:59",
                        //     },
                        //     beforeSubmit: function () {
                        //         //return $('#formLogin').valid();
                        //     },
                        //     success: function (response) {
                        //         console.log(response);
                        //     },
                        //     error: function(request, status, error) {
                        //         console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                        //     }
                        // });
                    },
                    getMyCashList: function () {
                        // $.get(
                        //     "/mypage/cash",
                        //     {
                        //         rowCount: this.rowCount,
                        //         page: this.curPage.cash,
                        //         start_at: this.start.cash + " 00:00:00",
                        //         end_at: this.end.cash + " 23:59:59",
                        //     },
                        //     function (response) {
                        //         if (response.status == "200") {
                        //             objDashBoard.cashList = response.rows;
                        //             objDashBoard.countAll.cash = response.totalRows;
                        //             objDashBoard.totalPageCount.cash = Math.ceil(response.totalRows / objDashBoard.rowCount);
                        //         } else {
                        //             alert(response.message);
                        //         }
                        //     },
                        //     "json"
                        // );
                    },
                    getBettingList: function () {
                        // $.get(
                        //     "/mypage/bet",
                        //     {
                        //         rowCount: this.rowCount,
                        //         page: this.curPage.betting,
                        //         start_at: this.start.betting + " 00:00:00",
                        //         end_at: this.end.betting + " 23:59:59",
                        //     },
                        //     function (response) {
                        //         if (response.status == "200") {
                        //             objDashBoard.bettingList = response.rows;
                        //             objDashBoard.countAll.betting = response.totalRows;
                        //             objDashBoard.totalPageCount.betting = Math.ceil(response.totalRows / objDashBoard.rowCount);
                        //         } else {
                        //             alert(response.message);
                        //         }
                        //     },
                        //     "json"
                        // );
                    },
                    getMyMemoList: function () {
                        $.get(
                            "/api/page_message",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.memo,
                                start_at: this.start.memo + " 00:00:00",
                                end_at: this.end.memo + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.memoList = response.rows;
                                    objDashBoard.countAll.memo = response.totalRows;
                                    objDashBoard.totalPageCount.memo = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                    objDashBoard.unreadMemo = response.unread;
                                } else {
                                    // alert(response.message);
                                }
                            },
                            "json"
                        );
                    },
                    getMyQnaList: function () {
                        $.get(
                            "/api/page_customer",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.qna,
                                start_at: this.start.qna + " 00:00:00",
                                end_at: this.end.qna + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.qnaList = response.rows;
                                    objDashBoard.countAll.qna = response.totalRows;
                                    objDashBoard.totalPageCount.qna = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                } else {
                                    // alert(response.message);
                                }
                            },
                            "json"
                        );
                    },
                    getNoticeList: function () {
                        $.get(
                            "/api/page_notice",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.notice,
                                start_at: this.start.notice + " 00:00:00",
                                end_at: this.end.notice + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.noticeList = response.rows;
                                    objDashBoard.countAll.notice = response.totalRows;
                                    objDashBoard.totalPageCount.notice = Math.ceil(response.totalRows / objDashBoard.rowCount);

                                    setTimeout(openFirstNotice, 500);
                                } else {
                                    // alert(response.message);
                                }
                            },
                            "json"
                        );
                    },
                    getEventList: function () {
                        // $.get(
                        //     "/mypage/event",
                        //     {
                        //         rowCount: this.rowCount,
                        //         page: this.curPage.event,
                        //         start_at: this.start.event + " 00:00:00",
                        //         end_at: this.end.event + " 23:59:59",
                        //     },
                        //     function (response) {
                        //         if (response.status == "success") {
                        //             objDashBoard.eventList = response.rows;
                        //             objDashBoard.countAll.event = response.totalRows;
                        //             objDashBoard.totalPageCount.event = Math.ceil(response.totalRows / objDashBoard.rowCount);

                        //             setTimeout(openFirstEvent, 500);
                        //         } else {
                        //             // alert(response.message);
                        //         }
                        //     },
                        //     "json"
                        // );
                    },
                    changeSafe: function () {
                        // if (this.inSafe < 0) {
                        //     alert("금고에 보관하실 금액을 0보다 크게 입력해주세요");
                        //     return;
                        // }
                        // UIkit.modal.confirm("금고보관금액을 " + this.myInfo.cash_safe + " 에서 " + this.inSafe + " 로 변경하시겠습니까?").then(
                        //     function () {
                        //         $.post(
                        //             "/mypage/insafe",
                        //             { insafe: objDashBoard.inSafe },
                        //             function (response) {
                        //                 if (response.status == 200) {
                        //                     alert("Success");
                        //                     objDashBoard.getMyInfo();
                        //                 } else {
                        //                     alert(response.message);
                        //                 }
                        //             },
                        //             "json"
                        //         );
                        //     },
                        //     function () {
                        //         //취소
                        //     }
                        // );
                    },
                    viewMemo: function (idx, read) {
                        if(read > 0)
                            return;
                        
                        $.post(
                            "/api/check_message",
                            {
                                idx: idx,
                            },
                            function (response) {
                                objDashBoard.getMyMemoList();
                            },
                            "json"
                        );
                    
                    },
                    deleteMemo: function (idx) {
                        UIkit.modal.confirm("삭제하시겠습니까?", {labels: {'ok': '확인', 'cancel': '취소'}}).then(
                            function () {
                                $.post(
                                    "/api/delete_message",
                                    {
                                        idx: idx,
                                    },
                                    function (response) {
                                        if (response.status == "success") {
                                            objDashBoard.getMyMemoList();
                                        } else {
                                            alert(response.msg);
                                        }
                                    },
                                    "json"
                                );
                            },
                            function () {}
                        );
                    },
                    deleteCus: function (idx) {
                        UIkit.modal.confirm("삭제하시겠습니까?", {labels: {'ok': '확인', 'cancel': '취소'}}).then(
                            function () {
                                $.post(
                                    "/api/delete_customer",
                                    {
                                        idx: idx,
                                    },
                                    function (response) {
                                        if (response.status == "success") {
                                            objDashBoard.getMyQnaList();
                                        } else {
                                            alert(response.msg);
                                        }
                                    },
                                    "json"
                                );
                            },
                            function () {}
                        );
                    },
                    getFavoriteQuestion: function () {
                        // $.get(
                        //     "/api/qna/favorite/question",
                        //     {},
                        //     function (response) {
                        //         objDashBoard.favoriteQuestionList = response.rows;
                        //     },
                        //     "json"
                        // );
                    },
                    fastQuestion: function (index, idx) {
                        // let title = this.favoriteQuestionList[index].title;
                        // let contents = "빠른문의 : " + this.favoriteQuestionList[index].title;
                        // if (confirm("문의 : " + title + " \n를 보내시겠습니까?") == false) return false;

                        // $.post(
                        //     "/mypage/qna",
                        //     {
                        //         title: title,
                        //         contents: contents,
                        //     },
                        //     function (response) {
                        //         if (response.status == 200) {
                        //             alert("정상적으로 처리 되었습니다");
                        //             objDashBoard.getMyQnaList();
                        //         } else {
                        //             alert(response.message);
                        //         }
                        //     },
                        //     "json"
                        // );
                    },
                    viewQna: function (idx) {
                        // $.post(
                        //     "/mypage/qna/view",
                        //     {
                        //         idx: idx,
                        //     },
                        //     function (response) {
                        //         try {
                        //             $("#qna_unread_" + idx).remove();
                        //             --objDashBoard.myInfo.answered_qna;
                        //         } catch (e) {}
                        //     },
                        //     "json"
                        // );
                    },
                    strChargeType: function (type){
                        if(type == 5){
                            return "직입금";
                        } else return "신청입금";
                    },
                    strExchangeType: function (type){
                        if(type == 5){
                            return "직출금";
                        } else return "신청출금";
                    },
                    strChState: function (type){
                        if(type == 2){
                            return "<span class='green'>승인</span>";
                        } else if(type == 3){
                            return "<span class='red'>거절</span>";
                        } else if(type == 5){
                            return "완료";
                        } else return "<span class='yellow'>대기</span>";
                    },
                    strMsgCheck: function (read){
                        if(read == 0){
                            return "<span class='red'>미확인</span>";
                        } else {
                            return "<span >확인</span>";
                        } 
                    },
                    strQnaCheck: function (state){
                        if(state == 0){
                            return "<span class='yellow'>대기</span>";
                        } else if(state == 1) {
                            return "<span class='green'>완료</span>";
                        } else 
                            return "<span ></span>";
                    },
                },
                mounted: function () {

                    this.getMyInfo();
                    // this.getFavoriteQuestion();
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

            function openFirstNotice() {
                $(".ui.title").first().trigger("click");
            }

            function openFirstEvent() {
                $(".ui.title").first().trigger("click");
            }
        </script>

        <div id="vue_modal">
            <div id="request_charge" uk-modal="" class="uk-modal" style="" tabindex="-1">
                <div class="uk-modal-dialog">
                    <form name="chargeForm" id="chargeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud download icon"></i> 입금요청</h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="field required">
                                <label>요청금액</label> <input type="number" name="cash" id="cash" placeholder="입금요청하실 금액을 만원단위로 입력해주세요" step="10000" />
                                <div style="padding-top: 5px; text-align:right;">
                                    <button type="button" onclick="setMoneyField('cash',10000)" class="ui inverted blue mini button">1만</button> <button type="button" onclick="setMoneyField('cash',50000)" class="ui inverted blue mini button">5만</button>
                                    <button type="button" onclick="setMoneyField('cash',100000)" class="ui inverted blue mini button">10만</button> <button type="button" onclick="setMoneyField('cash',500000)" class="ui inverted blue mini button">50만</button>
                                    <button type="button" onclick="setMoneyField('cash',1000000)" class="ui inverted blue mini button">100만</button> <button type="button" onclick="setMoneyField('cash',0)" class="ui inverted blue mini button">다시입력</button>
                                </div>
                            </div>
                            <div class="field required"><label>입금자 명</label> <input type="text" name="req_name" placeholder="입금자 명" v-model="myInfo.user_bank_own" /></div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">입금요청하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                </div>
            </div>
            <div id="request_exchange" uk-modal="" class="uk-modal" tabindex="-1" style="">
                <div class="uk-modal-dialog">
                    <form name="exchangeForm" id="exchangeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud upload icon"></i> 출금신청</h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="inline field">
                                <label>현재 보유 머니</label>
                                <div class="ui teal label">
                                    {{ myInfo.user_money }} 원
                                </div>
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
                                <div class="ui input"><input type="text" readonly="readonly" name="bank_owner" v-model="myInfo.user_bank_own" /></div>
                            </div>
                            <div class="inline field">
                                <label>은행명&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="ui input"><input type="text" name="bank_name" readonly="readonly" v-model="myInfo.user_bank_name" /></div>
                            </div>
                            <div class="inline field">
                                <label>계좌번호 </label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_num"/> </div>
                            </div>
                            <div class="inline field">
                                <label>출금비번 </label>
                                <div class="ui input"><input type="text" name="bank_passwd" /></div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">출금신청하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                </div>
            </div>
            <div id="request_cash" uk-modal="" class="uk-modal" tabindex="-1" style="">
                <div class="uk-modal-dialog">
                    <form name="changeForm" id="changeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title">포인트 전환</h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="field">
                                <label>현재 보유포인트</label>
                                <div class="ui label">
                                    {{ myInfo.user_point }} POINT
                                </div>
                            </div>
                            <div class="field required">
                                <label>전환 요청금액</label> <input type="number" name="point" id="point" value="" placeholder="전환요청하실 금액을 만원단위로 입력해주세요" class="ui text" />
                                <div style="padding-top: 5px;">
                                    <button type="button" onclick="setMoneyField('point',10000)" class="ui inverted blue mini button">1만</button> <button type="button" onclick="setMoneyField('point',50000)" class="ui inverted blue mini button">5만</button>
                                    <button type="button" onclick="setMoneyField('point',100000)" class="ui inverted blue mini button">10만</button> <button type="button" onclick="setMoneyField('point',500000)" class="ui inverted blue mini button">50만</button>
                                    <button type="button" onclick="setMoneyField('point',1000000)" class="ui inverted blue mini button">100만</button> <button type="button" onclick="setMoneyField('point',0)" class="ui inverted blue mini button">다시입력</button>
                                </div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">전환 신청하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
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
            <div id="inSafeModal" uk-modal="" class="uk-modal" tabindex="-1" style="">
                <div class="uk-modal-dialog ui form">
                    <div class="uk-modal-header"><h3 class="uk-modal-title">금고보관금액 변경</h3></div>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                    <div class="uk-modal-body">
                        <div class="field">
                            <div class="ui large teal label">
                                Cash
                                <div class="detail">4,950</div>
                            </div>
                        </div>
                        <div class="field required">
                            <div class="ui large labeled input">
                                <label for="amount" class="ui label">금고보관금액(최종)</label> <input type="number" name="amount" id="amount" placeholder="최종 금고에 보관될 금액을 만원단위로 입력해주세요" step="10000" />
                            </div>
                            <div style="padding-top: 5px;">
                                <button type="button" onclick="setMoneyField('amount',10000)" class="ui inverted blue mini button">1만</button> <button type="button" onclick="setMoneyField('amount',50000)" class="ui inverted blue mini button">5만</button>
                                <button type="button" onclick="setMoneyField('amount',100000)" class="ui inverted blue mini button">10만</button> <button type="button" onclick="setMoneyField('amount',500000)" class="ui inverted blue mini button">50만</button>
                                <button type="button" onclick="setMoneyField('amount',1000000)" class="ui inverted blue mini button">100만</button> <button type="button" onclick="setMoneyField('amount',0)" class="ui inverted blue mini button">다시입력</button>
                            </div>
                        </div>
                        <div class="ui mini red basic label">최종 금고에 보관될 금액을 입력해주세요</div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary submit button">보관하기</button>
                        <div class="ui uk-modal-close button">취소</div>
                    </div>
                </div>
            </div>
        </div>

        <script>
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
            });
        </script>
    </body>
</html>
