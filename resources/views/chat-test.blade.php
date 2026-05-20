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
            background: linear-gradient(135deg, #f3efff, #faf8ff, #f7f8fc);
            color: #172033;
        }

        .page {
            max-width: 820px;
            margin: 35px auto;
            padding: 20px;
        }

        .chat-shell {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid #e7eaf3;
            border-radius: 28px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .chat-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #6F3CC3, #8B5CF6);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-title {
            margin: 0;
            font-size: 22px;
            font-weight: 900;
        }

        .settings-btn {
            border: 0;
            border-radius: 999px;
            background: rgba(255,255,255,0.18);
            color: white;
            padding: 9px 14px;
            font-weight: 800;
            cursor: pointer;
        }

        .settings-panel {
            display: none;
            padding: 18px 24px;
            background: #fbfaff;
            border-bottom: 1px solid #eee7ff;
        }

        .settings-panel.open {
            display: block;
        }

        label {
            font-weight: 800;
            font-size: 13px;
            margin-bottom: 7px;
            display: block;
        }

        input,
        textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #E7EAF3;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            margin-bottom: 14px;
        }

        input:focus,
        textarea:focus {
            border-color: #6F3CC3;
            box-shadow: 0 0 0 4px rgba(111, 60, 195, 0.10);
        }

        .hint {
            font-size: 13px;
            color: #6b7280;
            margin-top: -6px;
            margin-bottom: 12px;
        }

        .chat-box {
            height: 500px;
            overflow-y: auto;
            background: #ffffff;
            padding: 22px;
        }

        .empty-chat {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8b8fa3;
            font-weight: 700;
            text-align: center;
        }

        .message-row {
            display: flex;
            margin-bottom: 12px;
        }

        .message-row.mine {
            justify-content: flex-end;
        }

        .message-row.theirs {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 68%;
            padding: 11px 14px;
            border-radius: 20px;
            line-height: 1.5;
            font-size: 14px;
            word-wrap: break-word;
        }

        .message-row.mine .message-bubble {
            background: linear-gradient(135deg, #6F3CC3, #8B5CF6);
            color: white;
            border-bottom-right-radius: 6px;
        }

        .message-row.theirs .message-bubble {
            background: #f2edff;
            color: #172033;
            border-bottom-left-radius: 6px;
        }

        .message-meta {
            font-size: 11px;
            margin-top: 5px;
            opacity: 0.75;
        }

        .chat-image {
            max-width: 260px;
            max-height: 260px;
            border-radius: 16px;
            display: block;
            object-fit: cover;
        }

        .message-row.mine .chat-image {
            border: 2px solid rgba(255,255,255,0.35);
        }

        .message-row.theirs .chat-image {
            border: 2px solid #e6dbff;
        }

        .typing-area {
            min-height: 24px;
            padding: 0 22px 10px;
            background: #ffffff;
            color: #7c3aed;
            font-size: 13px;
            font-weight: 700;
        }

        .typing-pill {
            display: none;
            width: fit-content;
            background: #f2edff;
            border-radius: 999px;
            padding: 7px 12px;
        }

        .typing-pill.active {
            display: inline-block;
        }

        .send-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px;
            background: #fbfaff;
            border-top: 1px solid #eee7ff;
        }

        .send-row input[type="text"] {
            margin-bottom: 0;
            flex: 1;
        }

        .btn-primary {
            border: none;
            border-radius: 16px;
            padding: 0 20px;
            height: 48px;
            font-weight: 900;
            cursor: pointer;
            background: linear-gradient(135deg, #6F3CC3, #8B5CF6);
            color: white;
        }

        .voice-btn,
        .image-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            font-size: 18px;
            background: linear-gradient(135deg, #7c3aed, #9333ea);
            color: white;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .voice-btn.recording {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            animation: pulse 1s infinite;
        }

        .hidden-file-input {
            display: none;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.07);
            }

            100% {
                transform: scale(1);
            }
        }

        .voice-player {
            margin-top: 8px;
            width: 220px;
        }

        .status-line {
            padding: 10px 24px;
            font-size: 13px;
            color: #6b7280;
            background: #fff;
            border-bottom: 1px solid #f1ecff;
        }

        .status-ok {
            color: #16a34a;
            font-weight: 800;
        }

        .status-waiting {
            color: #d97706;
            font-weight: 800;
        }

        .seen-ticks {
            margin-left: 6px;
            font-weight: 800;
        }

        .reaction-picker {
            display: none;
            gap: 6px;
         margin-top: 8px;
        }

        .message-bubble:hover .reaction-picker {
            display: flex;
        }

        .reaction-btn {
            border: none;
            background: rgba(255,255,255,0.35);
            border-radius: 999px;
            padding: 4px 7px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.18s ease;
        }

        .reaction-btn:hover {
            transform: translateY(-2px) scale(1.08);
            background: rgba(255,255,255,0.65);
        }

        .message-row.theirs .reaction-btn {
            background: #ffffff;
        }

        .reactions-list {
            display: flex;
            gap: 5px;
            margin-top: 7px;
            flex-wrap: wrap;
        }

        .reaction-chip {
            background: rgba(255,255,255,0.42);
            border-radius: 999px;
            padding: 3px 7px;
            font-size: 13px;
            font-weight: 800;
        }

        .message-row.theirs .reaction-chip {
            background: #ffffff;
        }


    </style>
</head>
<body>
<div class="page">
    <div class="chat-shell">
        <div class="chat-header">
            <div>
                <h1 class="chat-title">Servixa Chat</h1>
                <div style="font-size: 13px; opacity: 0.85;">Private realtime conversation</div>
            </div>

            <button id="settings-toggle" type="button" class="settings-btn">
                ⚙ Test Settings
            </button>
        </div>

        <div id="settings-panel" class="settings-panel">
            <label>Conversation ID</label>
            <input id="conversation-id" type="number" placeholder="Example: 1">

            <div class="hint">
                Tip: you can also open this page using <b>/chat-test?conversation_id=1</b>
            </div>

            <label>Your Display Name</label>
            <input id="chat-display-name" type="text" placeholder="Example: Ayham">

            <label>User API Token</label>
            <textarea id="chat-token" style="height: 90px;" placeholder="Paste Bearer token once. It will be saved in this browser."></textarea>

            <div class="hint">
                Token is saved locally in your browser for testing only.
            </div>
        </div>

        <div id="connection-status" class="status-line">
            Status: <span class="status-waiting">Waiting for conversation ID and token</span>
        </div>

        <div id="chat-box" class="chat-box">
            <div class="empty-chat">
                Enter conversation ID and token from Test Settings, then start sending messages.
            </div>
        </div>

        <div class="typing-area">
            <span id="typing-indicator" class="typing-pill"></span>
        </div>

        <form id="chat-form" class="send-row">
            <button id="voice-record-btn" type="button" class="voice-btn">
                🎤
            </button>

            <button id="image-upload-btn" type="button" class="image-btn">
                🖼️
            </button>

            <input id="chat-image-input" type="file" accept="image/*" class="hidden-file-input">

            <input id="chat-message" type="text" placeholder="Message...">

            <button type="submit" class="btn-primary">
                Send
            </button>
        </form>
    </div>
</div>
</body>
</html>
