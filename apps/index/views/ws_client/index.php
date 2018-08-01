<html>
<head>
    <title>WebSocket</title>
</head>
<body>
<button id="join">Join</button>
<button id="send">Send</button>
<script>
    var webSocket = function () {
        ws = new WebSocket("ws://192.168.1.54:9502?mixssid=<?=$session?>");
        ws.onopen = function () {
            console.log("连接成功");
        };
        ws.onmessage = function (e) {
            console.log("收到服务端的消息：" + e.data);
        };
        ws.onclose = function () {
            console.log("连接关闭");
        };
    };
    webSocket();

    document.getElementById('join').onclick = function () {
        ws.send('{"event":"joinRoom","params":{"room_id":88888}}');
    };

    document.getElementById('send').onclick = function () {
        ws.send('{"event":"messageEmit","params":{"to_uid":1008,"message":"Hello World"}}');
    };

</script>
</body>
</html>
