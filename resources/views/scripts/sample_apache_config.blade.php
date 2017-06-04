<VirtualHost *:80>
    ServerName {{ $servername }}
    DocumentRoot "{{ $website->document_root }}"

    ErrorLog {{ $base_path }}/{{ $website->username }}-error.log
    CustomLog {{ $base_path }}/{{ $website->username }}-access.log combined

    <Directory "{{ $website->document_root }}">
        AllowOverride all
        Require all granted
        <IfModule mpm_itk_module>
            AssignUserId {{ $website->username }} {{ $website->username }}
        </IfModule>
    </Directory>
</VirtualHost>