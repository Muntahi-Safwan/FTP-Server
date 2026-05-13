<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — FTP Server</title>
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
            width: 380px;
        }

        h2 {
            color: #e94560;
            margin-bottom: 20px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0 4px;
            border: 1px solid #333;
            border-radius: 6px;
            background: #0f0f23;
            color: #fff;
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
            margin: 3px 0;
        }

        .hint {
            font-size: 12px;
            color: #888;
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

        #emailStatus {
            font-size: 12px;
            margin-top: 2px;
        }
    </style>
</head>

<body>

    <nav>
        <?php require_once __DIR__ . '/navbar.php'; ?>
    </nav>

    <div class="container">
        <div class="box">
            <h2>Register</h2>

            <?php if (!empty($_SESSION['errors'])): ?>
                <?php foreach ($_SESSION['errors'] as $e): ?>
                    <p class="error">⚠ <?= htmlspecialchars($e) ?></p>
                <?php endforeach;
                unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php $old = $_SESSION['old'] ?? [];
            unset($_SESSION['old']); ?>

            <form id="regForm" method="POST" action="index.php?page=register">

                <input type="text" name="name" placeholder="Full Name"
                    value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>

                <input type="email" name="email" placeholder="Email"
                    id="emailInput"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                <div id="emailStatus"></div>

                <input type="password" name="password"
                    placeholder="Password (min 8 chars)" id="pass1" required>

                <input type="password" name="confirm"
                    placeholder="Confirm Password" id="pass2" required>
                <span id="matchMsg" class="hint"></span>

                <select name="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="admin"
                        <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="moderator"
                        <?= ($old['role'] ?? '') === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                </select>

                <button type="submit">Register</button>
            </form>

            <p class="link">Already have an account? <a href="index.php?page=login">Login</a></p>
        </div>
    </div>



    <script>
        // ── 1. Password match live check (JS Validation - criteria #7) ──
        var pass1 = document.getElementById('pass1');
        var pass2 = document.getElementById('pass2');
        var msg = document.getElementById('matchMsg');

        function checkMatch() {
            if (pass2.value.length === 0) {
                msg.textContent = '';
                return;
            }
            if (pass1.value === pass2.value) {
                msg.style.color = '#6bffb8';
                msg.textContent = '✔ Passwords match';
            } else {
                msg.style.color = '#ff6b6b';
                msg.textContent = '✘ Passwords do not match';
            }
        }
        pass1.addEventListener('input', checkMatch);
        pass2.addEventListener('input', checkMatch);

        // ── 2. AJAX email check using XMLHttpRequest (criteria #9) ──────
        var emailInput = document.getElementById('emailInput');
        var emailStatus = document.getElementById('emailStatus');
        var emailTimer;

        emailInput.addEventListener('input', function() {
            clearTimeout(emailTimer);

            var val = emailInput.value.trim();
            if (val.length < 5) {
                emailStatus.textContent = '';
                return;
            }

            // Wait 500ms after user stops typing
            emailTimer = setTimeout(function() {

                // Build JSON data (same pattern as your course example)
                var data = {
                    "email": val
                };
                var user = JSON.stringify(data);

                var xHttp = new XMLHttpRequest();
                xHttp.open('post', 'index.php?ajax=check_email', true);
                xHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xHttp.send('user=' + user);

                xHttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        if (result.exists) {
                            emailStatus.style.color = '#ff6b6b';
                            emailStatus.textContent = '✘ Email already registered';
                        } else {
                            emailStatus.style.color = '#6bffb8';
                            emailStatus.textContent = '✔ Email is available';
                        }
                    }
                };

            }, 500);
        });

        // ── 3. Final form submit validation ─────────────────────────────
        document.getElementById('regForm').addEventListener('submit', function(e) {
            if (pass1.value.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters.');
            } else if (pass1.value !== pass2.value) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>

    <?php require_once __DIR__ . '/footer.php'; ?>
</body>

</html>

<!-- FTP-Server/src/view/register.php -->