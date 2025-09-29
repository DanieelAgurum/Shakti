<style>
    #shakti-chatbot-center-text {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    #shakti-chatbot-circle {
        position: fixed;
        z-index: 9999;
        bottom: 20%;
        bottom: 50px;
        right: 50px;
        background: #5A5EB9;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .shakti-chatbot-box {
        display: none;
        background: #efefef;
        position: fixed;
        right: 30px;
        bottom: 20%;
        width: 350px;
        max-width: 85vw;
        max-height: 90vh;
        border-radius: .5rem;
        box-shadow: 0px 5px 35px 9px rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }

    .shakti-chatbot-box-header {
        background: #5A5EB9;
        height: 70px;
        border-top-left-radius: .5rem;
        border-top-right-radius: .5rem;
        color: white;
        text-align: center;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .shakti-chatbot-box-toggle {
        position: absolute;
        right: 10px;
        top: 15px;
        cursor: pointer;
    }

    .shakti-chatbot-box-body {
        position: relative;
        height: 370px;
        border: 1px solid #ccc;
        overflow-y: auto;
    }

    .shakti-chatbot-input {
        background: #f4f7f9;
        width: 100%;
        border-top: 1px solid #ccc;
        padding: 10px;
    }

    .shakti-chatbot-submit {
        border: none;
        background: transparent;
        color: #5A5EB9;
    }

    .shakti-chatbot-logs {
        padding: 15px;
        height: 100%;
        overflow-y: auto;
    }

    .shakti-chatbot-msg.user>.shakti-cm-msg-avatar img,
    .shakti-chatbot-msg.self>.shakti-cm-msg-avatar img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
    }

    .shakti-cm-msg-text {
        background: white;
        padding: 10px 15px;
        color: #666;
        max-width: 75%;
        border-radius: 30px;
        margin-bottom: 10px;
    }

    .shakti-chatbot-msg.self>.shakti-cm-msg-text {
        background: #5A5EB9;
        color: white;
    }

    .shakti-cm-msg-button>ul>li {
        list-style: none;
        display: inline-block;
        margin: 5px;
    }
</style>

<body>
    <div id="shakti-chatbot-body">
        <div id="shakti-chatbot-circle" class="btn btn-primary rounded-circle shadow">
            <div id="shakti-chatbot-overlay"></div>
            <i class="material-icons">speaker_phone</i>
        </div>

        <div class="shakti-chatbot-box shadow rounded">
            <div class="shakti-chatbot-box-header">
                ChatBot
                <span class="shakti-chatbot-box-toggle"><i class="material-icons">close</i></span>
            </div>
            <div class="shakti-chatbot-box-body">
                <div class="shakti-chatbot-box-overlay"></div>
                <div class="shakti-chatbot-logs"></div>
            </div>
            <div class="shakti-chatbot-input">
                <form class="shakti-chatbot-form position-relative">
                    <input type="text" id="shakti-chatbot-input" class="form-control" placeholder="Send a message..." />
                    <button type="submit" id="shakti-chatbot-submit" class="shakti-chatbot-submit btn position-absolute end-0 bottom-0 m-2">
                        <i class="material-icons">send</i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    $(function() {
        var SHAKTI_CHATBOT_INDEX = 0;

        $("#shakti-chatbot-submit").click(function(e) {
            e.preventDefault();
            var msg = $("#shakti-chatbot-input").val();
            if (msg.trim() == '') return false;
            generateShaktiMessage(msg, 'self');
            setTimeout(function() {
                generateShaktiMessage(msg, 'user');
            }, 1000);
        });

        function generateShaktiMessage(msg, type) {
            SHAKTI_CHATBOT_INDEX++;
            var str = `<div id='shakti-cm-msg-${SHAKTI_CHATBOT_INDEX}' class="shakti-chatbot-msg ${type}">
                        <span class="shakti-cm-msg-avatar">
                            <img src="https://image.crisp.im/avatar/operator/196af8cc-f6ad-4ef7-afd1-c45d5231387c/240/">
                        </span>
                        <div class="shakti-cm-msg-text">${msg}</div>
                   </div>`;
            $(".shakti-chatbot-logs").append(str);
            $("#shakti-cm-msg-" + SHAKTI_CHATBOT_INDEX).hide().fadeIn(300);
            if (type == 'self') $("#shakti-chatbot-input").val('');
            $(".shakti-chatbot-logs").stop().animate({
                scrollTop: $(".shakti-chatbot-logs")[0].scrollHeight
            }, 1000);
        }

        $("#shakti-chatbot-circle").click(function() {
            $("#shakti-chatbot-circle").toggle('scale');
            $(".shakti-chatbot-box").toggle('scale');
        });

        $(".shakti-chatbot-box-toggle").click(function() {
            $("#shakti-chatbot-circle").toggle('scale');
            $(".shakti-chatbot-box").toggle('scale');
        });
    });
</script>