<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chat Test</title>
    @vite(['resources/js/chat.js'])

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #f3efff, #f7f4ff, #f7f8fc);
            color: #172033;
        }

        .page {
            max-width: 950px;
            margin: 40px auto;
            padding: 24px;
        }

        .card {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid #e7eaf3;
            border-radius: 24px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
            padding: 26px;
        }

        h1 {
            margin-top: 0;
            color: #6F3CC3;
        }

        label {
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
        }

        input,
        textarea {
            width: 100%;
            border: 1px solid #E7EAF3;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
        }

        input:focus,
        textarea:focus {
            border-color: #6F3CC3;
            box-shadow: 0 0 0 4px rgba(111, 60, 195, 0.10);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .btn {
            border: none;
            border-radius: 14px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6F3CC3, #8B5CF6);
            color: white;
        }

        .chat-box {
            height: 360px;
            overflow-y: auto;
            background: #ffffff;
            border: 1px solid #E7EAF3;
            border-radius: 22px;
            padding: 18px;
            margin: 22px 0;
        }

        .message-item {
            background: #F7F2FF;
            border: 1px solid #E6DBFF;
            border-radius: 18px;
            padding: 12px 14px;
            margin-bottom: 12px;
        }

        .message-sender {
            font-weight: 800;
            color: #6F3CC3;
            margin-bottom: 6px;
        }

        .message-body {
            color: #172033;
            line-height: 1.6;
        }

        .send-row {
            display: flex;
            gap: 12px;
        }

        .send-row input {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <h1>Realtime Chat Test</h1>

            <div class="form-group">
                <label>Conversation ID</label>
                <input id="conversation-id" type="number">
            </div>

            <div class="form-group">
                <label>User API Token</label>
                <textarea id="chat-token" style="height: 90px;"></textarea>
            </div>

            

            <div id="chat-box" class="chat-box"></div>

            <form id="chat-form" class="send-row">
                <input id="chat-message" type="text" placeholder="Write message...">
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</body>
</html>
