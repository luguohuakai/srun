<?php

namespace srun\base;

interface Group
{
    public function add($group_name, $parent_name = '/');

    public function view($name = null, $id = null, $per_page = null, $page = null);

    public function Subscribe($group_id);
}