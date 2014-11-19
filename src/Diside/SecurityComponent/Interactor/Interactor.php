<?php

namespace Diside\SecurityComponent\Interactor;

use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;

interface Interactor
{
    function process(Request $request, Presenter $presenter);
}