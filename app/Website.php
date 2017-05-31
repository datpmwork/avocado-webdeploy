<?php

namespace App;

use App\Events\WebsiteUpdated;
use App\Jobs\ProcessNewWebsite;
use App\Jobs\RestartApache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Website extends Model
{
    protected $fillable = ['name', 'type'];

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        parent::creating(function(Website $website) {
            # Create Web Username Owner from Name
            $website->username = str_slug($website->name);
            $website->password = substr(str_replace("$", "", Hash::make(str_random(8))), 0, 8);
            if (env('APP_ENV') != 'local') {
                # Check for Unique
                while (true) {
                    $result = shell_exec("grep -c '^{$website->username}:' /etc/passwd");
                    if ($result == "0\n") {
                        # Stop While
                        break;
                    } else {
                        $website->username .= "-" . substr(Hash::make(str_random(8)), 0, 3);
                    }
                }
            }

            $website->activity_logs = "Waiting to initialize...\n";
        });

        parent::created(function(Website $website) {
            # Process Create Init Directory
            dispatch(new ProcessNewWebsite($website));
        });

        parent::updating(function(Website $website) {
            if ($website->isDirty('is_on')) {
                dispatch(new RestartApache($website));
            }
        });
    }

    # Mutators

    # Helpers

}
