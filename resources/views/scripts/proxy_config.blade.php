<VirtualHost *:80>
    ServerName {{ $website->username }}-{{ $deployment->branch }}.{{ $website->servername }}

    ProxyPreserveHost On
    ProxyRequests Off
    ProxyPass / http://127.0.0.1:{{ $deployment->port }}/
    ProxyPassReverse / http://127.0.0.1:{{ $deployment->port }}/

</VirtualHost>