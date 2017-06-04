<VirtualHost *:80>
    ServerName {{ $servername }}
    DocumentRoot "{{ $website->document_root }}"

    ErrorLog {{ $bash_path }}/{{ $website->username }}-error.log
    CustomLog {{ $bash_path }}/{{ $website->username }}-access.log combined

    <Directory "{{ $website->document_root }}">
        AllowOverride all
        Require all granted
        <IfModule mpm_itk_module>
            AssignUserId {{ $website->username }} {{ $website->username }}
        </IfModule>
    </Directory>
</VirtualHost>