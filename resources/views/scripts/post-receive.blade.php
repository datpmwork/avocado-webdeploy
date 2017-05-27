#!/bin/sh
echo "Deploying Web...."
git --work-tree={{ $website->document_root }} --git-dir={{ $website->git_root }} checkout {{ $website->checkout }} -f
cd {{ $website->document_root }}
{!! $website->deploy_scripts !!}