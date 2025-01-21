<?php

namespace PXLS\WPRM;

class Admin{

    function __construct(){

        new Admin\EnqueAssets();

        new Admin\Menu();

        new Admin\Cpt();

    }

}