<p>Hello,</p>

<p>You have been invited to join <strong>{{ $invitation->organization->name }}</strong> as an agent.</p>

<p>Click the link below to accept the invitation and set your password:</p>

<p><a href="{{ $url }}">Join {{ $invitation->organization->name }}</a></p>

<p>If you did not expect this invitation, you can ignore this email.</p>

<p>Thanks,</p>
<p>{{ config('app.name') }}</p>
