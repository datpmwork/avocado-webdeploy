<?php
/**
 * Created by ivivivn
 * User: datpm
 * Date: 12/29/17
 * Time: 9:27 PM
 */

namespace App\Http\Controllers;


use App\Jobs\UpdateContainer;
use Illuminate\Http\Request;

class GitlabController extends Controller
{
    # A new branch has create, call this for create new environment
    public function create(Request $request, $imageTag) {
        $this->dispatch(new UpdateContainer($imageTag));
    }
    
    # Update existed environment
    public function update(Request $request, $imageTag) {
    
    }
}