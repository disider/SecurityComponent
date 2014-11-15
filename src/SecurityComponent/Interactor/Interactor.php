<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;

interface Interactor
{
    public function process(Request $request, Presenter $presenter);
}