<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values for DOMPDF
    |
    */

    'show_warnings' => false,   // Throw an Exception on warnings from dompdf
    'orientation' => 'portrait',
    'defines' => [
        /**
         * The location of the DOMPDF font directory
         */
        'font_dir' => storage_path('fonts/'),

        /**
         * The location of the DOMPDF font cache directory
         */
        'font_cache' => storage_path('fonts/'),

        /**
         * The location of a temporary directory.
         */
        'temp_dir' => sys_get_temp_dir(),

        /**
         * \Dompdf\Image\Cache requires an absolute URL to images when rendering with some
         * backends. This constant provides a default prefix for relative URLs.
         */
        'chroot' => realpath(base_path()),

        /**
         * Whether to enable font subsetting or not.
         */
        'enable_font_subsetting' => false,

        /**
         * The PDF rendering backend to use
         */
        'pdf_backend' => 'CPDF',

        /**
         * PDFlib license key
         */
        'pdflib_license' => '',

        /**
         * html target media view which should be rendered into pdf.
         */
        'default_media_type' => 'screen',

        /**
         * The default paper size.
         */
        'default_paper_size' => 'a4',

        /**
         * The default paper orientation.
         */
        'default_paper_orientation' => 'portrait',

        /**
         * The default font family
         */
        'default_font' => 'serif',

        /**
         * Image DPI setting
         */
        'dpi' => 96,

        /**
         * Enable inline PHP
         */
        'enable_php' => false,

        /**
         * Enable inline Javascript
         */
        'enable_javascript' => true,

        /**
         * Enable remote file access
         */
        'enable_remote' => true,

        /**
         * A ratio applied to the fonts height to be more like browsers' line height
         */
        'font_height_ratio' => 1.1,

        /**
         * Use the HTML5 Lib parser
         */
        'enable_html5_parser' => true,
    ],

];
