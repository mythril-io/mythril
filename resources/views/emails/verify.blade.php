<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.1/css/bulma.min.css">
</head>
<body>

<div>
	<section class="hero is-primary">
	  <div class="hero-body">
	    <div class="container">
	      <h1 class="title">
	        {{ config('app.name') }}.io
	      </h1>
	      <h2 class="subtitle">
	        One Last Step
	      </h2>
	    </div>
	  </div>
	</section>
	<section class="section">
		<div class="container">
				Hey {{ $user->username }},
			    <br><br>
			    We're really glad you've joined the community! 
			    <br>
			    Before you can start using your account, we just need you to confirm your email address to prove that you're a human and not a bot.
			    <br><br>

				<div class="field is-grouped is-grouped-centered">
					<a class="button is-primary" href="{{ url('/verify', $verification_code)}}">Confirm Email</a>
				</div>

			    <br/>
			    Thanks,<br/>
				{{ config('app.name') }}
		</div>
	</section>

	<footer class="footer">
	  <div class="container">
	    <div class="content has-text-centered">
	      <p>
	        &copy; {{ config('app.name') }}
	      </p>
	    </div>
	  </div>
	</footer>
    
</div>

</body>
</html>