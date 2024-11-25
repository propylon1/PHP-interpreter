<?php
// File: FrameFactory.php

namespace IPP\Student;

class FrameFactory {
    private static ?GlobalFrame $globalFrame = null;
    
    public static function getGlobalFrame(): GlobalFrame {
        if (self::$globalFrame === null) {
            self::$globalFrame = new GlobalFrame();
        }
        return self::$globalFrame;
    }

    public static function createLocalFrame(): LocalFrame {
        return new LocalFrame();
    }

    public static function createTempFrame(): TempFrame {
        return new TempFrame();
    }
}
