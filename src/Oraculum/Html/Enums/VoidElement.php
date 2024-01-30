<?php

namespace Oraculum\Html\Enums;

use Oraculum\Support\Traits\Enumerable;

enum VoidElement: string
{
    use Enumerable;

    case AREA = "area";
    case BASE = "base";
    case BR = "br";
    case COL = "col";
    case EMBED = "embed";
    case HR = "hr";
    case IMG = "img";
    case INPUT = "input";
    case LINK = "link";
    case META = "meta";
    case PARAM = "param";
    case SOURCE = "source";
    case TRACK = "track";
    case WBR = "wbr";
}