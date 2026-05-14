<?php require_once __DIR__ . '/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — FTP Server</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #0f0f23;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 70px);
        }

        .box {
            background: #1a1a2e;
            padding: 40px;
            border-radius: 12px;
            width: 360px;
        }

        h2 {
            color: #e94560;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 4px;
            border: 1px solid #333;
            border-radius: 6px;
            background: #0f0f23;
            color: #fff;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #aaa;
            margin: 8px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #e94560;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 15px;
        }

        button:hover {
            background: #c73652;
        }

        .error {
            color: #ff6b6b;
            font-size: 13px;
            margin: 4px 0;
        }

        .flash {
            color: #6bffb8;
            font-size: 13px;
            margin: 4px 0;
        }

        .link {
            margin-top: 16px;
            font-size: 13px;
            color: #aaa;
        }

        .link a {
            color: #e94560;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="box">
            <h2>🔐 Login</h2>

            <?php if (!empty($_SESSION['flash'])): ?>
                <p class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></p>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['errors'])): ?>
                <?php foreach ($_SESSION['errors'] as $e): ?>
                    <p class="error">⚠ <?= htmlspecialchars($e) ?></p>
                <?php endforeach;
                unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="index.php?page=login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required id="loginPass">
                <div class="remember">
                    <input type="checkbox" name="remember" id="rem" style="width:auto;">
                    <label for="rem">Remember Me</label>
                </div>
                <button type="submit">Login</button>
            </form>

            <p class="link">Don't have an account? <a href="index.php?page=register">Register</a></p>
        </div>
    </div>


    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const pass = document.getElementById('loginPass').value;
            if (pass.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters.');
            }
        });
    </script>
</body>

</html>