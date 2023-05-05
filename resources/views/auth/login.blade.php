<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <style>
        body {
	margin: 0;
	padding: 0;
	background-color: #f7f7f7;
	font-family: sans-serif;
}

.login-box {
	width: 350px;
	margin: 100px auto;
	padding: 30px;
	background-color: #fff;
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
	border-radius: 5px;
}

h1 {
	margin: 0 0 30px;
	font-size: 24px;
	text-align: center;
}

form {
	display: flex;
	flex-direction: column;
}

label {
	margin-bottom: 5px;
	font-size: 18px;
}

input[type="email"],
input[type="password"] {
	padding: 10px;
	margin-bottom: 20px;
	border-radius: 5px;
	border: none;
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

button[type="submit"] {
	padding: 10px;
	background-color: black;
	color: #fff;
	border: none;
	border-radius: 5px;
	box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	font-size: 18px;
	cursor: pointer;
}

    </style>
</head>
<body>
    <div class="login-box">
		<h1>Login</h1>
		<form action="{{url('/login')}}" method="POST">
            @csrf
			<label for="username">Username</label>
			<input type="email" id="email" name="email">
			<label for="password">Password</label>
			<input type="password" id="password" name="password">
			<button type="submit">Login</button>
		</form>
	</div>
</body>
</html>
