<?php

namespace SomeModule;

//
//
//
class SomeModule implements \Hill\IModule {
    public static function create(array $options = []) {
        echo "create some module\n";
        
        return [
            'moduleClass' => SomeModule::class,
        ];
    }
}