<html>
<head>
</head>
<body>
	Hello {{$user->first_name}}	{{$user->last_name}}, silahkan klik 
	<a href="/forgot_password/{{$token}}">disini </a>
	untuk mendapatkan password baru anda.
	<br><br>
	Jika link tidak dapat diakses, silahkan buka www.rp.rentuff.id/forgot_password/{{$token}}
</body>
</html>