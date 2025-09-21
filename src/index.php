<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>FreeLetter: 모두의 뉴스레터</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 90%;
            max-width: 600px;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #007bff;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        p {
            font-size: 1.1em;
            margin-bottom: 30px;
        }
        .form-section {
            border-top: 1px solid #eee;
            padding-top: 30px;
            margin-top: 30px;
        }
        input[type="email"] {
            width: calc(100% - 100px);
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            margin-right: 10px;
        }
        button {
            padding: 12px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .unsubscribe-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 0.9em;
            color: #888;
            text-decoration: none;
            transition: color 0.3s;
        }
        .unsubscribe-link:hover {
            color: #555;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>FreeLetter: 모두의 뉴스레터</h1>
        <p>세상의 모든 뉴스레터를 자유롭게 보내고 받아보세요.</p>

        <div class="form-section">
            <h2>뉴스레터 구독하기</h2>
            <form action="subscribe.php" method="POST">
                <input type="email" name="email" placeholder="이메일 주소를 입력하세요" required>
                <button type="submit">구독하기</button>
            </form>
        </div>
        
        <a href="unsubscribe.php" class="unsubscribe-link">구독 해제하기</a>
    </div>
</body>
</html>