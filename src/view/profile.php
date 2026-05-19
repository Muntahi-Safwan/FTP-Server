
<?php

    require_once __DIR__ . '/navbar.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile — FTP Server</title>
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
        }

        .container {
            max-width: 620px;
            margin: 40px auto;
            padding: 0 16px;
        }

        .box {
            background: #1a1a2e;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        h2,
        h3 {
            color: #e94560;
            margin-bottom: 18px;
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

        label {
            font-size: 13px;
            color: #aaa;
        }

        button {
            padding: 10px 24px;
            background: #e94560;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 12px;
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
            margin-bottom: 12px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e94560;
            margin-bottom: 14px;
            display: block;
        }

        .role-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            background: #e94560;
            font-size: 12px;
            margin-bottom: 14px;
        }

        .hint {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Flash & Errors -->
        <?php if (!empty($_SESSION['flash'])): ?>
            <p class="flash">✔ <?= htmlspecialchars($_SESSION['flash']) ?></p>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['errors'])): ?>
            <?php foreach ($_SESSION['errors'] as $e): ?>
                <p class="error">⚠ <?= htmlspecialchars($e) ?></p>
            <?php endforeach;
            unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Profile Info & Update -->
        <div class="box">
            <h2>👤 My Profile</h2>

            <?php if (!empty($user['profile_picture'])): ?>

                <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="avatar">
            <?php else: ?>
                <div style="width:80px;height:80px;border-radius:50%;background:#333;
                        display:flex;align-items:center;justify-content:center;
                        font-size:28px;margin-bottom:14px;">👤
                </div>
            <?php endif; ?>

            <span class="role-badge">
                <?= htmlspecialchars(strtoupper($user['role'])) ?>
            </span>

            <form id="profileForm" method="POST" action="index.php?page=profile&action=update" enctype="multipart/form-data">

                <label>Full Name</label>
                <input type="text" name="name"
                    value="<?= htmlspecialchars($user['name']) ?>" required>

                <label>Email</label>
                <input type="email" name="email"
                    value="<?= htmlspecialchars($user['email']) ?>" required>

                <label>Profile Picture <span class="hint">(JPG/PNG/GIF, max 2MB)</span></label>
                <input type="file" name="profile_picture" accept="image/*">

                <button type="submit">💾 Update Profile</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="box">
            <h3>🔑 Change Password</h3>
            <form id="passForm" method="POST" action="index.php?page=profile&action=password">

                <label>Current Password</label>
                <input type="password" name="current_password"
                    placeholder="Enter current password" required>

                <label>New Password <span class="hint">(min 8 characters)</span></label>
                <input type="password" name="new_password"
                    placeholder="New password" id="np" required>

                <label>Confirm New Password</label>
                <input type="password" name="confirm_password"
                    placeholder="Repeat new password" id="cp" required>
                <span id="passMsg" class="hint"></span>

                <button type="submit">🔒 Change Password</button>
            </form>
        </div>

    </div>

    <script>
        var np = document.getElementById('np');
        var cp = document.getElementById('cp');
        var pm = document.getElementById('passMsg');

        function checkPass() {
            if (cp.value.length === 0) {
                pm.textContent = '';
                return;
            }
            pm.style.color = np.value === cp.value ? '#6bffb8' : '#ff6b6b';
            pm.textContent = np.value === cp.value ? '✔ Passwords match' : '✘ Passwords do not match';
        }
        np.addEventListener('input', checkPass);
        cp.addEventListener('input', checkPass);

        document.getElementById('passForm').addEventListener('submit', function(e) {
            if (np.value.length < 8) {
                e.preventDefault();
                alert('New password must be at least 8 characters.');
            } else if (np.value !== cp.value) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>

</html>