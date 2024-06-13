<?php

namespace App\Utils;

// get the color depending of the role
function getRoleColor($role) {

    $role_color = '';
    switch ($role) {
        case "combat":
            $role_color = "#e23535"; // red
            break;
        case "exploration":
            $role_color = "#2d89e1"; // blue
            break;
        case "competition":
            $role_color = "#08b122"; // green
            break;
        case "industrial":
            $role_color = "#f8d142"; // yellow
            break;
        case "multi":
            $role_color = "#3aebc8"; // cyan
            break;
        case "ground":
            $role_color = "#a96f4c"; // brown
            break;
        case "support":
            $role_color = "#f2aae4"; // pink
            break;
        case "transport":
            $role_color = "#f68948"; // orange
            break;
        default:
            $role_color = "";
    }

    return $role_color;
}