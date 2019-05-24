<?php

function gravatar_url($email, $size = 40, $default = 'https://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg')
{
	$email = md5($email);

	return "https://gravatar.com/avatar/{$email}/?" . http_build_query([
		's' => $size,
		'd' => $default,
	]);
}
