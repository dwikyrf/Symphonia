@component('mail::message')
# Halo {{ $user->name }} 👋

Terima kasih telah mendaftar di platform kami.

Silakan klik tombol di bawah untuk memverifikasi alamat email Anda:

@component('mail::button', ['url' => $url])
Verifikasi Email
@endcomponent

Jika Anda tidak merasa membuat akun ini, Anda bisa abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
