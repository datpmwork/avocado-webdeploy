<VirtualHost *:80>
    ServerName {{ $website->servername }}
    DocumentRoot "{{ $website->document_root }}"
    <Directory "{{ $website->document_root }}">
        AllowOverride all
        Require all granted
        <IfModule mpm_itk_module>
            AssignUserId {{ $website->username }} {{ $website->username }}
        </IfModule>
    </Directory>
</VirtualHost>