<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Person</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
        }
        .container {
            background-color: white;
            margin-top: 40px;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 24px;
        }
        form div {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        input[type="number"],
        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="email"]:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .error {
            color: #d93025;
            font-size: 13px;
            margin-top: 4px;
        }
        .success-message {
            color: #2e7d32;
            font-weight: 600;
            text-align: center;
            margin-bottom: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 0;
            width: 100%;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Person</h1>
        <div id="message"></div>
        <form id="addPersonForm" action="process.php" method="post" novalidate>
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required />
                <div class="error" id="usernameError"></div>
            </div>
            <div>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" min="0" max="120" />
                <div class="error" id="ageError"></div>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" />
                <div class="error" id="emailError"></div>
            </div>
            <input type="submit" value="Add Person" />
        </form>
    </div>
    <script>
        const form = document.getElementById('addPersonForm');
        const messageDiv = document.getElementById('message');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            clearErrors();
            messageDiv.textContent = '';
            let valid = true;

            const username = form.username.value.trim();
            const age = form.age.value.trim();
            const email = form.email.value.trim();

            if (!username) {
                showError('usernameError', 'Username is required.');
                valid = false;
            } else if (username.length < 3) {
                showError('usernameError', 'Username must be at least 3 characters.');
                valid = false;
            }

            if (age) {
                const ageNum = Number(age);
                if (isNaN(ageNum) || ageNum < 0 || ageNum > 120) {
                    showError('ageError', 'Age must be a number between 0 and 120.');
                    valid = false;
                }
            }

            if (email) {
                const emailPattern = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
                if (!emailPattern.test(email)) {
                    showError('emailError', 'Please enter a valid email address.');
                    valid = false;
                }
            }

            if (valid) {
                form.submit();
            }
        });

        function showError(id, message) {
            const errorDiv = document.getElementById(id);
            errorDiv.textContent = message;
        }

        function clearErrors() {
            ['usernameError', 'ageError', 'emailError'].forEach(id => {
                document.getElementById(id).textContent = '';
            });
        }
    </script>
</body>
</html>
