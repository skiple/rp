<html>
<head>
</head>
<body>
	Hello {{$user->first_name}}	{{$user->last_name}}, silahkan klik 
	<a href="/forgot_password/{{$token}}">disini </a>
	untuk mendapatkan password baru anda.
	<br><br>
	Jika link tidak dapat diakses, silahkan buka link di bawah ini:<br>
	rp.rentuff.id/reset_password/{{$token}}
</body>
</html>