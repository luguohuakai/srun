<?php

namespace srun\base;

interface Auth
{
    public function getAccessToken();

    public function getVersion();
}