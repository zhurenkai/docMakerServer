<?php
namespace App\Repository\Host;

use App\Model\Project;
use App\Repository\Lib\Repository;
use App\Model\Host as HostModel;

class Host extends Repository
{
    protected $model;

    public function __construct(HostModel $host)
    {
        $this->model = $host;
    }

    public function getUserHosts($user_id)
    {
        $project_hosts = Project::where('user_id', $user_id)->with('hosts')->get();
        return $project_hosts;
    }

    public function updateWithHosts($host)
    {

    }

    public function show($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return false;
        }
        return $project;
    }
}